<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Supports;
use App\Student_Supports;
use App\Http\Resources\SupportResource;
use App\User;
use App\Tutor;
use App\History;
use App\Http\Resources\UserResource;
use Illuminate\Support\Carbon;

class SupportController extends Controller
{
    public function index()
    {
        return Supports::orderBy('text', 'asc')->get();
    }


    public function byEmail($email)
    {
        return Student_Supports::where('email', $email)->pluck('support_value');
    }



    public function updateStudentSupports(Request $request)
    {
        $dados = $request->validate([
            'teachers' => 'required',
            'email' => 'required|email',
            'supports' => '',
            'tutor' => '',
            'duration' => 'required|string',
            'date' => ''

        ]);

        $user = User::Where('email', $dados['email'])->first();
        $user->enee = "approved";

        if ($dados['date']) {
            $dado = $request->validate([
                'date' => 'after:today'
            ]);
            $user->eneeExpirationDate = $dado['date'];
        }

        $user->save();

        if ($dados['tutor'] != null) {
            $tutor = new Tutor();
            $tutor->studentEmail = $user->email;
            $tutor->tutorEmail = $dados['tutor'];

            $history = new History();
            $history->studentEmail = $user->email;
            $history->description = "O diretor atribui o tutor " . $tutor->tutorEmail;
            $history->date = Carbon::now();
            $history->save();
        }

        $existingSupports = Student_Supports::where('email', $dados['email'])->pluck('support_value')->toArray();

        if (sizeof($existingSupports) == 0) {
            foreach ($dados['supports'] as &$support) {
                $newStudent_Supports = new Student_Supports();
                $newStudent_Supports->email = $dados['email'];
                $newStudent_Supports->support_value = $support;
                $newStudent_Supports->save();
            }
        }

        if (sizeof($existingSupports) != 0) {
            $newSupports = array_diff($dados['supports'], $existingSupports);
            foreach ($newSupports as &$support) {
                $newStudent_Supports = new Student_Supports();
                $newStudent_Supports->email = $dados['email'];
                $newStudent_Supports->support_value = $support;
                $newStudent_Supports->save();
            }
        }

        if (sizeof($existingSupports) != 0) {
            $newSupports = array_diff($existingSupports, $dados['supports']);
            foreach ($newSupports as &$support) {
                Student_Supports::where('email', $dados['email'])->where('support_value', $support)->delete();
            }
        }

        $history = new History();
        $history->studentEmail = $user->email;
        $history->description = "O diretor aprovou o pedido de ENEE";
        $history->date = Carbon::now();
        $history->save();
        //Email $dados['teachers] tem os professores que têm de ser notificados, coordenador do curso, para estudantes, serviços académicos, professor tutor


        return response()->json(new UserResource($user), 200);
    }

    public function reproveSubscription(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->enee = "reproved";
        $user->save();

        $history = new History();
        $history->studentEmail = $user->email;
        $history->description = "O diretor recusou o pedido de ENEE";
        $history->date = Carbon::now();
        $history->save();

        return response()->json(new UserResource($user), 200);
    }

    public function supportCreate(Request $request)
    {
        $dados = $request->validate([
            'text' => 'required|string'
        ]);

        $newSupport = new Supports();
        $newSupport->text = $dados['text'];
        $newSupport->save();

        return response()->json(new SupportResource($newSupport), 201);
    }

    public function supportUpdate(Request $request, $value)
    {
        $support = Supports::findOrFail($value);

        $dados = $request->validate([
            'text' => 'required',
        ]);

        $support->text = $dados['text'];
        $support->save();

        return response()->json(new SupportResource($support), 200);
    }

    public function supportDelete($value)
    {
        $support = Supports::findOrFail($value);

        $support->delete();

        return response()->json(new SupportResource($support), 200);
    }
}
