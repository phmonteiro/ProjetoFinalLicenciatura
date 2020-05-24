<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\CaseManagerResponsibleResource;
use App\CaseManager;
use App\Substitution;
use App\User;
use App\Http\Resources\UserResource;
use App\Http\Resources\SubstitutionResource;
use Carbon\Carbon;
use App\History;
use Barryvdh\Debugbar\Facade as Debugbar;


class CaseManagerResponsibleController extends Controller
{
    public function index()
    {
        return CaseManagerResponsibleResource::collection(CaseManager::Orderby('studentName')->paginate(10));
    }

    public function getAllCMs(Request $request){
        $cms = CaseManagerResponsibleResource::collection(User::where('type','CaseManager')->get());

       return response()->json($cms, 200);
    }

    public function getStudents()
    {
        $students = User::where('type', 'Estudante')->where('enee', 'approved')->paginate(10);
        return response()->json(new UserResource($students), 200);
    }

    public function getStudentCMs($email)
    {
        return CaseManagerResponsibleResource::collection(CaseManager::Where('studentEmail', $email)->paginate(10));
    }

    public function setCmSubstitute(Request $request){

        $cms = CaseManager::where('caseManagerEmail',$request->emailCurrentCaseManager)->get();



        foreach ($cms as $cm)
        {
            $substitution = new Substitution();
            $substitution->emailMainCM = $request->emailCurrentCaseManager;
            $substitution->nameMainCM = $request->nameCurrentCaseManager;
            $substitution->emailSubstituteCM = $request->emailSubstituteCaseManager;
            $substitution->nameSubstituteCM = $request->nameSubstituteCaseManager;
            $substitution->emailStudent = $cm->studentEmail;
            $substitution->nameStudent = $cm->studentName;
            $substitution->type = $request->substitutionType;

            if($substitution->type==="temporary"){
                    $substitution->startDate = $request->startDate;
                    $substitution->endDate = $request->endDate;

            }

            $substitution->save();
        }

        //mandar email a avisar

        return response()->json(200);
    }

    public function removeCM($id)
    {
        $user = CaseManager::findOrFail($id);
        $user->delete();

        $student = User::where('email', $user->studentEmail)->first();
        $history = new History();
        $history->studentEmail = $student->email;
        $history->description = "Foi removido o gestor de caso ao aluno";
        $history->date = Carbon::now();
        $history->save();

        //EmailController::sendEmail('Foi removido o gestor de caso' . $user->name . '. Obrigado', $student->email, 'Gestor de caso removido', 'Gestor de caso removido');


        return response()->json($user, 200);
    }

    public function addCM(Request $request){

        \Debugbar::info($request->cmEmail);

        $user = User::where('email',$request->cmEmail)->first();

        \Debugbar::info($user);


        if($user !== null){
            if($user->type==='CaseManager'){
                return response()->json(409);
            }else{
                return response()->json(418);
            }
        }

//         $users = \Adldap\Laravel\Facades\Adldap::search()->find($request->cmEmail);

//         $user->type = 'CaseManager';
//         $user->course = $users->description[0];
//         $user->school = $users->company[0];
//         $user->number = $users->mailnickname[0];
//         $user->departmentNumber = $users->departmentnumber[0];
//         $user->firstLogin = 1;
//         $user->save();
//         $token = $user->createToken(rand())->accessToken;


//         return response()->json(['user' => Auth::user()], 200)->header('Authorization', $token);
        return response()->json(200);
    }

        public function cancelSubstitution(Request $request){

                $mainCaseManager = User::where('email',$request->emailMainCaseManager)->first();

                $cms = CaseManager::where('emailMainCaseManager',$request->emailMainCaseManager)->get();

                $substituteName = $cms[0]->caseManagerName;

                foreach ($cms as $cm)
                {
                    $cm->caseManagerEmail= $mainCaseManager->email;
                    $cm->caseManagerName= $mainCaseManager->name;
                    $cm->emailMainCaseManager = null;
                    $cm->save();

                     $history = new History();
                     $history->studentEmail = $cm->studentEmail;
                     $history->description = 'Terminou o periodo de substituição do Gestor de Caso ' .$cm->caseManagerName.' pelo '.$substituteName.' voltou como substituto para o aluno '.$cm->studentName;
                     $history->date = Carbon::now();
                     $history->save();
                 }

            return response()->json(200);
        }

    public function setCM(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $dados = $request->validate([
            'cmEmail' => 'required|email',
            'studentName' => 'required',
            'cmName' => 'required',
        ]);

        $caseManager = new CaseManager();
        $caseManager->studentEmail = $user->email;
        $caseManager->studentName = $dados['studentName'];
        $caseManager->caseManagerEmail = $dados['cmEmail'];
        $caseManager->caseManagerName = $dados['cmName'];

        $history = new History();
        $history->studentEmail = $user->email;
        $history->description = "Foi atribuido um gestor de caso ao aluno";
        $history->date = Carbon::now();
        $history->save();

        $caseManager->save();

        //EmailController::sendEmail('Foi adicionado o gestor de caso' .  $caseManager->caseManagerName . '. Obrigado', $caseManager->studentEmail, 'Gestor de caso adicionado', 'Gestor de caso adicionado');

        return response()->json(new CaseManagerResponsibleResource($caseManager), 200);
    }

        public function getActiveSubstitutions(){

            $cms = CaseManager::whereNotNull('emailMainCaseManager')->select('caseManagerEmail','caseManagerName','emailMainCaseManager')->distinct()->get();

            foreach ($cms as $cm) {
                $user = User::where('email',$cm->emailMainCaseManager)->first();

                $cm['mainCaseManagerName'] = $user->name;
            }

            return response()->json($cms,200);
        }

        public function substitutionsHistory(){

                    return SubstitutionResource::collection(Substitution::Orderby('startDate')->paginate(10));
        }
}
