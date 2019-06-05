<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\CaseManagerResource;
use App\CaseManager;
use App\User;
use App\Http\Resources\UserResource;
use Carbon\Carbon;
use App\History;

class CaseManagerController extends Controller
{
    public function index()
    {
        return CaseManagerResource::collection(CaseManager::Orderby('studentName')->paginate(10));
    }

    public function getStudents()
    {
        $students = User::where('type', 'Estudante')->where('enee', 'approved')->paginate(10);
        return response()->json(new UserResource($students), 200);
    }
    
    public function getStudentCMs($email)
    {
        return CaseManagerResource::collection(CaseManager::Where('studentEmail', $email)->paginate(10));
    }

    public function removeCM($email)
    {
        $user = CaseManager::Where('studentEmail', $email)->first();
        $user->delete();
        
        return response()->json(new CaseManagerResource($user), 200);
    }

    public function setCM(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $dados = $request->validate([
            'cmEmail' => 'required|email',
            'studentName' => 'required'
        ]);

        $cmName = User::where('email', $dados['cmEmail'])->pluck('name');
        //dd($cmName);
        $caseManager = new CaseManager();
        $caseManager->studentEmail = $user->email;
        $caseManager->studentName = $dados['studentName'];
        $caseManager->caseManagerEmail = $dados['cmEmail'];
        $caseManager->caseManagerName = $cmName[0];
        //mandar alerta

        $history = new History();
        $history->studentEmail = $user->email;
        $history->description = "Foi atribuido um gestor de caso ao aluno";
        $history->date = Carbon::now();
        $history->save();

        $caseManager->save();

        return response()->json(new CaseManagerResource($caseManager), 200);
    }
}
