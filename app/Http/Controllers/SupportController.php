<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support;
use App\Student_Supports;
use App\Http\Resources\SupportResource;
use App\User;
use App\SupportCategory;
use App\Tutor;
use App\History;
use App\Http\Resources\UserResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Barryvdh\Debugbar\Facade as Debugbar;


class SupportController extends Controller
{
    public function getSupportCategories()
    {
        return SupportCategory::orderBy('name', 'asc')->get();
    }

    public function getSupports()
    {
    \Debugbar::info(DB::table('supports')->join('support_categories','supports.id_category','=','support_categories.id')->select('supports.name as supportName','support_categories.name as categoryName','supports.id')->get());

        return DB::table('supports')->join('support_categories','supports.id_category','=','support_categories.id')->select('supports.name as supportName','support_categories.name as categoryName','supports.id as supportId','supports.id_category as categoryId')->get();
    }


    public function getSupportsByStudent($email)
    {
        return Student_Supports::where('email', $email)->pluck('id_support')->toArray();
    }



    public function updateStudentSupports(Request $request)
    {
        $dados = $request->validate([
            'teachers' => '',
            'email' => 'required|email',
            'duration' => 'required|string',
            'date' => '',
            'tutor' => ''

        ]);

        $user = User::Where('email', $dados['email'])->first();
        $user->enee = "approved";

        if ($dados['date']) {
            $dado = $request->validate([
                'date' => 'after:today'
            ]);
            $user->eneeExpirationDate = $dado['date'];
        }else{
            $user->eneeExpirationDate = null;
        }
        $user->dateEneeApproved = Carbon::now();
        $user->save();


        if ($dados['tutor'] != null) {
            $actualTutor = Tutor::where('studentEmail','=',$user->email)->first();

            if($actualTutor){
                $actualTutor->delete();
            }

            $tutor = new Tutor();
            $tutor->studentEmail = $user->email;
            $tutor->tutorEmail = $dados['tutor'];
            $tutor->save();

            $history = new History();
            $history->studentEmail = $user->email;
            $history->description = "O Diretor atribui o Professor Orientador " . $tutor->tutorEmail;
            $history->date = Carbon::now();
            $history->save();

            //EmailController::sendEmailWithCC('O diretor atribui-lhe um professor tutor. Obrigado', $user->email, 'Atribuição de um novo professor tutor', 'Atribuição de um novo professor tutor',  $tutor->studentEmail);
        }

        $history = new History();
        $history->studentEmail = $user->email;
        $history->description = "O diretor aprovou o pedido de ENE";
        $history->date = Carbon::now();
        $history->save();

        //Email para o aluno
        //EmailController::sendEmail('O seu pedido para estatuto de estudante com necessidades educativas especias foi aprovado com sucesso. Obrigado', $user->email, 'Candidatura a estatuto de ENEE', 'Candidatura a estatuto de ENEE');

        //Email para os professores
        if($dados['teachers']){
        for ($i = 0; $i < sizeof($dados['teachers']); $i++) {
            //EmailController::sendEmail('O seu estudante ' . $user->name . ' obteve o estatuto de estudante com necessidades educativas especias. Obrigado', $dados['teachers'][$i], 'Aluno com estatuto de ENEE', 'Aluno com estatuto de ENEE');
        }
       }
        //Email para o coordenador de curso

        //Email para os serviços académicos

        return response()->json(new UserResource($user), 200);
    }

    public function reproveSubscription(Request $request, $id)
    {
        $dados = $request->validate([
            'comment' => '',
        ]);
        $user = User::findOrFail($id);
        $user->enee = "rejected";
        $user->coordinatorApproval = null;
        $user->directorComment = $dados['comment'];
        $user->save();

        $history = new History();
        $history->studentEmail = $user->email;
        $history->description = "O diretor recusou o pedido de ENE";
        $history->date = Carbon::now();
        $history->save();

        //EmailController::sendEmail('O seu pedido para estatuto de estudante com necessidades educativas especias foi recusado. Obrigado', $user->email, 'Candidatura a estatuto de ENEE', 'Candidatura a estatuto de ENEE');


        return response()->json(new UserResource($user), 200);
    }

    public function supportCategoryCreate(Request $request)
    {
        $dados = $request->validate([
            'text' => 'required|string'
        ]);

        $newSupport = new SupportCategory();
        $newSupport->name = $dados['text'];
        $newSupport->save();

        return response()->json(new SupportResource($newSupport), 201);
    }

        public function createSupport(Request $request)
        {
            $dados = $request->validate([
                'name' => 'required|string',
                'id_category' => 'required'
            ]);

            $newSupport = new Support();
            $newSupport->name = $dados['name'];
            $newSupport->id_category = $dados['id_category'];
            $newSupport->save();

            $support = DB::table('supports')->join('support_categories','supports.id_category','=','support_categories.id')->select('supports.name as supportName','support_categories.name as categoryName','supports.id as supportId')->where('supports.id','=',$newSupport->id)->get();


            return response()->json(new SupportResource($support), 201);
        }

    public function supportCategoryUpdate(Request $request, $id)
    {
        $support = SupportCategory::findOrFail($id);

        $dados = $request->validate([
            'name' => 'required',
        ]);

        $support->name = $dados['name'];
        $support->save();

        return response()->json(new SupportResource($support), 200);
    }

    public function updateSupport(Request $request, $id)
    {
        $support = Support::findOrFail($id);

        \Debugbar::info($request);

        $dados = $request->validate([
            'name' => 'required',
            'id_category' => 'required'
        ]);

        $support->name = $dados['name'];
        $support->id_category = $dados['id_category'];
        $support->save();

        return response()->json(new SupportResource($support), 200);
    }


    public function supportCategoryDelete($id)
    {
        $support = SupportCategory::findOrFail($id);

        $support->delete();

        return response()->json(new SupportResource($support), 200);
    }

    public function deleteSupport($id)
    {
        $support = Support::findOrFail($id);

        $support->delete();

        return response()->json(new SupportResource($support), 200);
    }

    public function getSupportsByCategory(){

            $categories = SupportCategory::all();
            $supports = Support::all();

            foreach($categories as $category){
                $category->supports=array();

                $supportsAux = array();

                foreach($supports as $support){

                    if($support->id_category === $category->id){
                        array_push($supportsAux,$support);
                    }

                }

                $category->supports = array_unique($supportsAux);
            }

            return response()->json($categories,200);
    }


}
