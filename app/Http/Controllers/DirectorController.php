<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;    
use App\User;
use App\CaseManager;
use App\Http\Resources\UserResource;
use App\Http\Resources\CaseManagerResource;


class DirectorController extends Controller
{
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
        $caseManager->approved = 0;
        //mandar alerta
        
        $caseManager->save();

        return response()->json(new CaseManagerResource($caseManager), 200);
    }
}
