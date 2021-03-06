<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use App\Http\Resources\UserResource;
use App\Http\Resources\ContactResource;
use App\Http\Resources\MeetingResource;
use App\Http\Resources\EneeDiagnosticResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Chumper\Zipper\Zipper;
use App\User;
use App\CaseManager;
use App\Contact;
use App\Meeting;
use App\History;
use App\EneeDiagnostic;
use App\Contacts_Files;
use Illuminate\Support\Arr;
use App\Nee;
use App\Schedule;

class CaseManagerController extends Controller
{

    public function setPlan(Request $request)
    {
        $dados = $request->validate([
            'studentId' => 'required|integer',
            'plan' => 'required|string',
            'diagnostic' => 'required|string',
        ]);


        $plan = new EneeDiagnostic();
        $plan->studentId = $dados['studentId'];
        $plan->plan = $dados['plan'];
        $plan->diagnostic = $dados['diagnostic'];
        $plan->save();

        $student = User::findOrFail($dados['studentId']);

        $history = new History();
        $history->studentEmail = $student->email;
        $history->description = "O gestor de caso criou o plano de inclusão individual e o plano de diagnóstico.";
        $history->date = Carbon::now();
        $history->save();


        return response()->json(new EneeDiagnosticResource($plan), 201);
    }

    public function setSupportHours(Request $request, $id){
            $user = User::findOrFail($id);
            $user->supportHours=$request->newTotalHours;
            $user->save();

            $history = new History();
            $history->studentEmail = $user->email;
            $history->description = "O case manager alterou o limite de horas de suporte do enee para ".$request->newTotalHours.".";
            $history->date = Carbon::now();
            $history->save();

            return response()->json(new UserResource($user), 200);
    }

    public function myMeetings(Request $request)
    {
        $user = Auth::user();
        $students = CaseManager::where('caseManagerEmail', $user->email)->pluck('studentEmail')->toArray();

       \Debugbar::info($request->requested);

    if($request->requested==0){
        $meetings = DB::table('meetings')->whereIn('email', $students)->where('service', 'Gestor-Caso')->whereNotNull('date')->paginate(10);
}else if($request->requested==1){
        $meetings = DB::table('meetings')->whereIn('email', $students)->where('service', 'Gestor-Caso')->whereNull('date')->paginate(10);
}

        return response()->json($meetings, 200);
    }

    public function updatePlan($id, Request $request)
    {
        $plan = EneeDiagnostic::findOrFail($id);

        $dados = $request->validate([
            'plan' => 'required|string',
            'diagnostic' => 'required|string',
        ]);

        $plan->plan = $dados['plan'];
        $plan->diagnostic = $dados['diagnostic'];
        $plan->save();

        $student = User::findOrFail($plan->studentId);

        $history = new History();
        $history->studentEmail = $student->email;
        $history->description = "O gestor de caso atualizou o plano de inclusão individual e o plano de diagnóstico.";
        $history->date = Carbon::now();
        $history->save();

        return response()->json(new EneeDiagnosticResource($plan), 201);
    }

    public function getEneePlan($studentId)
    {
        return EneeDiagnosticResource::collection(EneeDiagnostic::Where('studentId', $studentId)->get());
    }

    public function setEneeMeeting($id, Request $request)
    {
        $meeting = Meeting::findOrFail($id);
        //dd($request);

        $dados = $request->validate([
            'info' => 'required|string',
            'place' => 'required|string',
            'date' => 'required',
            'time' => 'required|date_format:H:i',
        ]);

        $meeting->info = $dados['info'];
        $meeting->place = $dados['place'];
        $meeting->date = $dados['date'];
        $meeting->time = $dados['time'];
        $meeting->save();

        $history = new History();
        $history->studentEmail = $meeting->email;
        $history->description = "Reuniao marcada com " . $meeting->service;
        $history->date = Carbon::now();
        $history->save();

        //EmailController::sendEmail('Foi marcada uma reunião em' . $meeting->date . ' ás ' . $meeting->time . '. Obrigado', $meeting->email, 'Marcação de reunião', 'Marcação de reunião');


        return response()->json(new MeetingResource($meeting), 201);
    }

    public function downloadContactFiles($id)
    {
        $contact = Contact::findOrFail($id);
        $contactFiles = Contacts_Files::where('contact_id', $contact->id)->get();
        $array =  array();
        for ($i = 0; $i < sizeof($contactFiles); $i++) {
            $file = base_path('storage/app/public/interactionFiles/' . $contactFiles[$i]->filename);
            array_push($array, $file);
        }
        $seed = rand();
        $zipper = new Zipper();
        $zipper->make('interactionFiles/' . $seed . '.zip');

        $zipper->add($array)->close();

        return response()->download(public_path('interactionFiles/' . $seed . '.zip'));
    }

    public function getEneeInteractions($email)
    {
        return ContactResource::collection(Contact::Where('studentEmail', $email)->orderBy('date', 'desc')->orderBy('nextContact', 'desc')->paginate(10));
    }

    public function getCmEnee($id)
    {
        $user = User::findOrFail($id);

        $enees = $user->cm;

        $subset = $enees->map(function ($enee) {
            return collect($enee->toArray())
                ->only(['studentEmail'])
                ->all();
        });


        $eneeInfo = User::whereIn('email', $subset->toArray())->get();

        return response()->json(new UserResource($eneeInfo), 200);
    }
    public function setInteraction(Request $request)
    {
        $dados = $request->validate([
            'email' => 'required|email',
            'interactionDate' => '',
            'nextInteraction' => 'required|date',
            'service' => 'required',
            'decision' => 'required',
            'information' => 'required',
            'contactMedium' => 'required',
            'software' => '',
            'place' => ''
        ]);

        $contact = new Contact();
        $contact->studentEmail = $dados['email'];
        if ($dados['interactionDate'] == null) {
            $contact->date = Carbon::now();
        } else {
            $contact->date = $dados['interactionDate'];
        }
        $contact->service = $dados['service'];
        $contact->decision = $dados['decision'];
        $contact->information = $dados['information'];
        $contact->nextContact = $dados['nextInteraction'];
        $contact->contactMedium = $dados['contactMedium'];
        $contact->software = $dados['software'];
        $contact->place = $dados['place'];
        $contact->save();

        if ($request->numberFiles != null && $request->numberFiles > 0) {
            $contact->hasFiles = '1';
            $contact->save();

            for ($i = 0; $i < $request->numberFiles; $i++) {
                $file = Input::file('file' . $i);
                $ext = $file->getClientOriginalExtension();
                $uploadedFile = "InteractionFile - " . $contact->id . "-" . $i . '.' . $ext;
                Storage::disk('public')->put('interactionFiles/' . $uploadedFile, File::get($file));
                $interactionFile = new Contacts_Files();
                $interactionFile->contact_id = $contact->id;
                $interactionFile->filename = $uploadedFile;
                $interactionFile->save();
            }
        }
        $history = new History();
        $history->studentEmail = $dados['email'];
        $history->description = "Gestor de caso reuniu com o estudante";
        $history->date = Carbon::now();
        $history->save();


        return response()->json(new ContactResource($contact), 200);
    }

    public function statistics($stats)
    {
        if ($stats == "curso") {
            $user = User::where('type', 'Estudante')->where('enee', 'approved')->pluck('course');
            $aux = array_count_values($user->toArray());
            $values = Arr::divide($aux);

            return $values;
        }
        if ($stats == "nee") {
            $nees = Nee::all()->pluck('name');
            $aux = array_count_values($nees->toArray());
            $values = Arr::divide($aux);

            return $values;
        }
        if ($stats == "escola") {
            $user = User::where('type', 'Estudante')->where('enee', 'approved')->pluck('school');
            $aux = array_count_values($user->toArray());
            $values = Arr::divide($aux);

            return $values;
        }
        if ($stats == "sexo") {
            $user = User::where('type', 'Estudante')->where('enee', 'approved')->pluck('gender');
            $aux = array_count_values($user->toArray());
            $values = Arr::divide($aux);

            return $values;
        }
    }

    public function addEvent(Request $request)
    {
        $dados = $request->validate([
            'title' => 'required|string',
            'startDate' => 'required',
            'hours' => 'required'
        ]);

        $event = new Schedule();
        $event->email = Auth::user()->email;
        $event->title = $dados['title'];
        $event->startDate = $dados['startDate'];
        $event->hours = $dados['hours'];
        $event->save();

        //EmailController::sendEmail('Foi adicionado um evento ao seu calendário em' . $event->startDate . '. Obrigado', Auth::user()->email, 'Evento adicionado ao calendário', 'Evento adicionado ao calendário');

        return response()->json($event, 201);
    }

    public function checkSubstitution(Request $request){
        $user = Auth::user();

        $cms = CaseManager::where('emailMainCaseManager',$user->email)->first();


        if($cms===null){
            return response()->json("false",200);
        }else{
            return response()->json($cms->caseManagerName,200);
        }
    }

    public function getEvent()
    {
        $user = Auth::user();
        $students = CaseManager::where('caseManagerEmail', $user->email)->pluck('studentEmail')->toArray();
        $meetings = DB::table('meetings')->whereIn('email', $students)->whereNotNull('date')->where('service', 'Gestor-Caso')->get();
        $schedule = Schedule::where('email', $user->email)->get();


        return response()->json([$meetings, $schedule], 200);
    }

    public function getEmailCaseManagerResponsible(){
        $caseManagerResponsible = User::where('type','CaseManagerResponsible')->first();

        return response()->json($caseManagerResponsible->email, 200);
    }


}
