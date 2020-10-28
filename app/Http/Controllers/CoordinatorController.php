<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\User;
use Auth;
use App\Coordinator;
use App\History;
use Carbon\Carbon;

class CoordinatorController extends Controller
{
    public function requests()
    {
        $coordEmail = Auth::user()->email;
        $coordinator = Coordinator::where('email',$coordEmail)->first();

        return UserResource::collection(User::where('type', 'Estudante')->where('enee', 'awaiting')->where('departmentNumber',$coordinator->departmentNumber)->where('coordinatorApproval','=','-1')->paginate(10));
    }

    public function approve($id)
    {
        $user = User::findOrFail($id);
        $user->coordinatorApproval = '1';
        $user->save();

        $history = new History();
        $history->studentEmail = $user->email;
        $history->description = "Coordenador de curso aprovou a candidatura do ENE";
        $history->date = Carbon::now();
        $history->save();

        $diretor = User::where('type','Director')->first();

//         EmailController::sendEmail('O Coordenador de Curso de '.$user->course.' deu um parecer positivo em relação ao aluno ' . $user->name . '. Obrigado.', $diretor->email, '[100%IN] Parecer Obrigatório do Coordenador de Curso', '[100%IN] Parecer Obrigatório do Coordenador de Curso');

        return response()->json(200);
    }

    public function deny($id)
    {
        $user = User::findOrFail($id);
        $user->coordinatorApproval = '0';
        $user->save();

        $history = new History();
        $history->studentEmail = $user->email;
        $history->description = "Coordenador de curso reprovou a candidatura do ENE";
        $history->date = Carbon::now();
        $history->save();

        $diretor = User::where('type','Director')->first();

//         EmailController::sendEmail('O Coordenador de Curso de '.$user->course.' deu um parecer negativo em relação ao aluno ' . $user->name . '. Obrigado.', $diretor->email, '[100%IN] Parecer Obrigatório do Coordenador de Curso', '[100%IN] Parecer Obrigatório do Coordenador de Curso');

        return response()->json(200);
    }

    public function getSecondaryEmail(){
        $emailCoord = Auth::user()->email;
        $coord = Coordinator::where('email',$emailCoord)->first();
        $secondaryEmail = $coord->secondaryEmail;

        if($secondaryEmail == null){
            $secondaryEmail = "";
        }

        return response()->json($secondaryEmail,200);
    }

    public function setSecondaryEmail(Request $request){
        $dados = $request->validate([
            'secondaryEmail' => 'required',
        ]);

        $user = Auth::user();
        $user->secondEmail = $dados['secondaryEmail'];
        $user->save();

        $coordinator = Coordinator::where('email',$user->email)->first();
        $coordinator->secondaryEmail = $dados['secondaryEmail'];
        $coordinator->save();

        return response()->json(Auth::user(),200);
    }
}
