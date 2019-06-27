<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\CaseManager;
use App\Http\Resources\UserResource;
use App\Http\Resources\CaseManagerResponsibleResource;
use App\Tutor;
use App\Student_Supports;
use App\MedicalFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Chumper\Zipper\Zipper;
use Carbon\Carbon;
use App\ServiceRequest;
use App\History;

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
        }

        $history = new History();
        $history->studentEmail = $user->email;
        $history->description = "O diretor pediu o parecer do " . $user->serviceNameApproval;
        $history->date = Carbon::now();
        $history->save();

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
        $user->dateEneeApproved = Carbon::now();


        if ($dados['tutor'] != null) {
            $tutor = new Tutor();
            $tutor->studentEmail = $user->email;
            $tutor->tutorEmail = $dados['tutor'];

            $history = new History();
            $history->studentEmail = $user->email;
            $history->description = "O diretor atribui o tutor " . $tutor->tutorEmail;
            $history->date = Carbon::now();
            $history->save();
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
        $zipper->make('medicalReport/' . $seed . '.zip')->add($array)->close();
        return response()->download(public_path('medicalReport/' . $seed . '.zip'));
    }
}
