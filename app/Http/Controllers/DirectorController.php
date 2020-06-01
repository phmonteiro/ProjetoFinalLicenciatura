<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
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
    public function addStudentSupport($id)
    {
        $service = Service::findOrFail($id);
        $service->aprovedDate = Carbon::now();
        $service->save();

        $studentSupport = new Student_Supports();
        $studentSupport->email = $service->email;
        $studentSupport->support_value = $service->support;
        $studentSupport->save();

        $history = new History();
        $apoio = Supports::findOrFail($service->support);
        $history->studentEmail = $service->email;
        $history->description = "O diretor atribui o apoio " . $apoio->text;
        $history->date = Carbon::now();
        $history->save();

        //EmailController::sendEmail('O diretor atribuiu-lhe um novo apoio. Obrigado', $service->email, 'Atribuição de novo apoio', 'Atribuição de novo apoio');

        return response()->json($studentSupport, 200);
    }

    public function rejectStudentSupport($id)
    {
        $service = Service::findOrFail($id);
        $service->rejectedDate = Carbon::now();
        $service->save();

        $history = new History();
        $apoio = Supports::findOrFail($service->support);
        $history->studentEmail = $service->email;
        $history->description = "O diretor rejeitou o pedido do apoio " . $apoio->text . " ao estudante.";
        $history->date = Carbon::now();
        $history->save();

        //EmailController::sendEmail('O diretor rejeitou o seu pedido de um novo apoio. Obrigado', $service->email, 'Atribuição de novo apoio rejeitada', 'Atribuição de novo apoio rejeitada');

        return response()->json($service, 200);
    }

    public function supportRequests()
    {
        $requests = DB::table('services')
            ->join('users', 'users.email', '=', 'services.email')
            ->join('supports', 'value', '=', 'services.support')
            ->whereNull('services.aprovedDate')
            ->whereNull('services.rejectedDate')
            ->select('services.*', 'users.name', 'supports.text')
            ->paginate(10);
        return response()->json($requests, 200);
    }

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
        ]);

        $user = User::Where('email', $dados['email'])->first();
        $user->enee = "approved";
        $user->type = "Estudante";
        $user->dateEneeApproved = Carbon::now();

        

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
            foreach ($newSupports as &$support) { //Duvida no &$
                Student_Supports::where('email', $dados['email'])->where('support_value', $support)->delete();
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
