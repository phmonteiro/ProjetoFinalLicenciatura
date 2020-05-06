<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\CaseManagerResponsibleResource;
use App\CaseManager;
use App\User;
use App\Http\Resources\UserResource;
use Carbon\Carbon;
use App\History;
use Barryvdh\Debugbar\Facade as Debugbar;


class CaseManagerResponsibleController extends Controller
{
    public function index()
    {
        return CaseManagerResponsibleResource::collection(CaseManager::Orderby('studentName')->paginate(10));
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

        $caseManager = CaseManager::where('studentEmail',$request->emailStudent)->first();

        $caseManager->emailCaseManagerSubstituto = $request->emailCmSubstitute;

        $caseManager->save();

        $history = new History();
        $history->studentEmail = $request->emailStudent;
        $history->description = 'Foi definido o Gestor de Caso ' .$caseManager->caseManagerName.' como substituto para o aluno '.$caseManager->studentName;
        $history->date = Carbon::now();
        $history->save();

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

    public function setCM(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $dados = $request->validate([
            'cmEmail' => 'required|email',
            'studentName' => 'required'
        ]);

        $cm = \Adldap\Laravel\Facades\Adldap::search()->find($dados['cmEmail']);

        $caseManager = new CaseManager();
        $caseManager->studentEmail = $user->email;
        $caseManager->studentName = $dados['studentName'];
        $caseManager->caseManagerEmail = $dados['cmEmail'];
        $caseManager->caseManagerName = $cm->cn[0];

        $history = new History();
        $history->studentEmail = $user->email;
        $history->description = "Foi atribuido um gestor de caso ao aluno";
        $history->date = Carbon::now();
        $history->save();

        $caseManager->save();

        //EmailController::sendEmail('Foi adicionado o gestor de caso' .  $caseManager->caseManagerName . '. Obrigado', $caseManager->studentEmail, 'Gestor de caso adicionado', 'Gestor de caso adicionado');

        return response()->json(new CaseManagerResponsibleResource($caseManager), 200);
    }
}
