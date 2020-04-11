<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\User;
use App\History;
use Carbon\Carbon;

class CoordinatorController extends Controller
{
    public function requests()
    {
        return UserResource::collection(User::where('type', 'Estudante')->where('enee', 'awaiting')->whereNull('coordinatorApproval')->paginate(10));
    }

    public function approve($id)
    {
        $user = User::findOrFail($id);
        $user->coordinatorApproval = '1';
        $user->save();

        $history = new History();
        $history->studentEmail = $user->email;
        $history->description = "Coordenador de curso aprovou a candidatura do ENEE";
        $history->date = Carbon::now();
        $history->save();

        //EmailController::sendEmail('O coordenador de curso do estudante ' . $user->name . ' deu um parecer positivo em relação ao ENEE. Obrigado', 'email do diretor', 'Parecer do coordenador de curso', 'Parecer do coordenador de curso');

        return response()->json(200);
    }

    public function deny($id)
    {
        $user = User::findOrFail($id);
        $user->coordinatorApproval = '0';
        $user->save();

        $history = new History();
        $history->studentEmail = $user->email;
        $history->description = "Coordenador de curso reprovou a candidatura do ENEE";
        $history->date = Carbon::now();
        $history->save();

        //EmailController::sendEmail('O coordenador de curso do estudante ' . $user->name . ' deu um parecer negativo em relação ao ENEE. Obrigado', 'email do diretor', 'Parecer do coordenador de curso', 'Parecer do coordenador de curso');


        return response()->json(200);
    }
}
