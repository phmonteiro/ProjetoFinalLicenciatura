<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\CaseManagerResponsibleResource;
use App\CaseManager;
use App\User;
use App\Http\Resources\UserResource;
use Carbon\Carbon;
use App\History;

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

    public function removeCM($email)
    {
        $user = CaseManager::Where('studentEmail', $email)->first();
        $user->delete();

        return response()->json(new CaseManagerResponsibleResource($user), 200);
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

        return response()->json(new CaseManagerResponsibleResource($caseManager), 200);
    }
}
