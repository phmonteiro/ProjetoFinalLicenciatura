<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\CaseManagerResource;
use App\CaseManager;
use App\User;

class CaseManagerController extends Controller
{
    public function index()
    {
        return CaseManagerResource::collection(CaseManager::Orderby('studentName')->paginate(10));
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
        
        $caseManager->save();

        return response()->json(new CaseManagerResource($caseManager), 200);
    }
}
