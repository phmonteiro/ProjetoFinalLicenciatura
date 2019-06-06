<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\CaseManager;
use App\Http\Resources\UserResource;
use App\Http\Resources\CaseManagerResponsibleResource;
use App\Tutor;
use App\Student_Supports;

class DirectorController extends Controller
{
    public function approvalRequest($id)
    {
        $user = User::findOrFail($id);
        $user->servicesApproval = 'requested';

        $user->save();
        return response()->json($user, 200);
    }

    public function updateEnee(Request $request)
    {
        $dados = $request->validate([
            'email' => 'required|email',
            'supports' => '',
            'tutor' => '',
        ]);

        $user = User::Where('email', $dados['email'])->first();
        $user->enee = "approved";


        if ($dados['tutor'] != null) {
            $tutor = new Tutor();
            $tutor->studentEmail = $user->email;
            $tutor->tutorEmail = $dados['tutor'];
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

        return response()->json(new UserResource($user), 200);
    }
}
