<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\CaseManagerResource;
use App\CaseManager;
use App\User;
use App\Http\Resources\UserResource;

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
    public function setCM(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $dados = $request->validate([
            'cmEmail' => 'required|email',
            'studentName' => 'required'
        ]);
        $cmName = \Adldap\Laravel\Facades\Adldap::search()->users()->paginate(1000)->getResults();

        dd($cmName);
        //dd($cmName);
        $caseManager = new CaseManager();
        $caseManager->studentEmail = $user->email;
        $caseManager->studentName = $dados['studentName'];
        $caseManager->caseManagerEmail = $dados['cmEmail'];
        $caseManager->caseManagerName = $cmName[0];
        //mandar alerta

        $caseManager->save();

        return response()->json(new CaseManagerResource($caseManager), 200);
    }
}
