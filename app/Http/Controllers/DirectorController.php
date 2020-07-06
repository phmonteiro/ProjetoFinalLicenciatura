<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Tutor;
use App\CaseManager;
use App\Http\Resources\UserResource;
use App\Http\Resources\CaseManagerResponsibleResource;
use App\Service;
use App\Supports;
use App\Student_Supports;
use App\MedicalFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Chumper\Zipper\Zipper;
use Carbon\Carbon;
use App\ServiceRequest;
use App\History;
use Illuminate\Support\Facades\DB;


class DirectorController extends Controller
{
    public function approvalRequest(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->servicesApproval = 'requested';

        for ($i = 0; $i < sizeOf($request->name); $i++) {
            $serviceRequest = new ServiceRequest();
            $serviceRequest->name = $request->name[$i];
            $serviceRequest->studentEmail = $user->email;
            $serviceRequest->save();

            $history = new History();
            $history->studentEmail = $user->email;
            $history->description = "O diretor pediu o parecer do " . $request->name[$i];
            $history->date = Carbon::now();
            $history->save();
        }
        $user->save();
        return response()->json($user, 200);
    }

    public function updateEnee(Request $request)
    {
        $dados = $request->validate([
            'email' => 'required|email',
            'supports' => '',
            'tutor' => ''
        ]);

        $user = User::Where('email', $dados['email'])->first();
        $user->enee = "approved";
        $user->type = "Estudante";
        $user->dateEneeApproved = Carbon::now();

        if ($dados['tutor'] != null) {
            $currentTutor = Tutor::where('studentEmail', $dados['email'])->firstOrFail();
            if ($currentTutor != null) {
                $currentTutor->tutorEmail = $dados['tutor'];
                $currentTutor->save();

                $history = new History();
                $history->studentEmail = $user->email;
                $history->description = "O diretor alterou para o tutor " . $currentTutor->tutorEmail;
                $history->date = Carbon::now();
                $history->save();

                //EmailController::sendEmailWithCC('O diretor alterou o seu professor tutor. Obrigado', $user->email, 'Atribuição de um novo professor tutor', 'Atribuição de um novo professor tutor',  $currentTutor->tutorEmail);
            } else {
                $tutor = new Tutor();
                $tutor->studentEmail = $user->email;
                $tutor->tutorEmail = $dados['tutor'];
                $tutor->save();

                $history = new History();
                $history->studentEmail = $user->email;
                $history->description = "O diretor atribui o tutor " . $tutor->tutorEmail;
                $history->date = Carbon::now();
                $history->save();

                //EmailController::sendEmailWithCC('O diretor atribui-lhe um professor tutor. Obrigado', $user->email, 'Atribuição de um novo professor tutor', 'Atribuição de um novo professor tutor',  $currentTutor->tutorEmail);
            }
        }

        $existingSupports = Student_Supports::where('email', $dados['email'])->pluck('id_support')->toArray();

        if (sizeof($existingSupports) == 0) {
            foreach ($dados['supports'] as &$support) {
                $newStudent_Supports = new Student_Supports();
                $newStudent_Supports->email = $dados['email'];
                $newStudent_Supports->id_support = $support;
                $newStudent_Supports->save();
            }
        }

        if (sizeof($existingSupports) != 0) {
            $newSupports = array_diff($dados['supports'], $existingSupports);
            foreach ($newSupports as &$support) {
                $newStudent_Supports = new Student_Supports();
                $newStudent_Supports->email = $dados['email'];
                $newStudent_Supports->id_support = $support;
                $newStudent_Supports->save();
            }
        }

        if (sizeof($existingSupports) != 0) {
            $newSupports = array_diff($existingSupports, $dados['supports']);
            foreach ($newSupports as &$support) { //Duvida no &$
                Student_Supports::where('email', $dados['email'])->where('id_support', $support)->delete();
            }
        }
        $history = new History();
        $history->studentEmail = $user->email;
        $history->description = "O diretor alterou o estatuto.";
        $history->date = Carbon::now();
        $history->save();

        return response()->json(new UserResource($user), 200);
    }

    public function downloadStudentDocuments($id)
    {
        $user = User::findOrFail($id);
        $files = MedicalFile::where('email', $user->email)->get();
        $array =  array();
        for ($i = 0; $i < sizeof($files); $i++) {
            $file = base_path('storage/app/public/medicalReport/' . $files[$i]->fileName);
            array_push($array, $file);
        }
        $seed = rand();
        $zipper = new Zipper();
        $zipper->make('medicalReport/' . $seed . '.zip')->add($array);
        $zipper->close();
        return response()->download(public_path('medicalReport/' . $seed . '.zip'));
    }
}
