<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use App\Http\Resources\UserResource;
use App\Http\Resources\ContactResource;
use App\Http\Resources\MeetingResource;
use App\Http\Resources\EneeDiagnosticResource;
use App\Http\Resources\SupportHoursRequestResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Chumper\Zipper\Zipper;
use App\User;
use App\Subject;
use App\CaseManager;
use App\Contact;
use App\Support;
use App\Student_Supports;
use App\Service;
use App\Meeting;
use App\History;
use App\EneeDiagnostic;
use App\Contacts_Files;
use App\SupportHoursRequest;
use App\Nee;
use App\Schedule;
use App\Support_Note;

class CaseManagerController extends Controller
{

    public function approveSupportHoursRequest($requestId) {

        $newSupportHoursRequest = SupportHoursRequest::where('id', $requestId)->first();

        $newSupportHoursRequest->status = "approved";

        $subject = Subject::where('studentEmail', '=', $newSupportHoursRequest->student_email)->where('subjectCode', '=', $newSupportHoursRequest->subject_code)->first();

        $subject->hours += $newSupportHoursRequest->hours;

        $subject->save();

        return response()->json('success', 200);

    }

    public function denySupportHoursRequest(Request $request) {

        $newSupportHoursRequest = SupportHoursRequest::where('id', $request->input('id'))->first();

        $newSupportHoursRequest->status = "denied";
        $newSupportHoursRequest->decision = $request->input('decision');

        $newSupportHoursRequest->save();

        return response()->json('failure', 200);

    }

    public function getAllSupportHoursRequests(Request $request) {

        $user = Auth::user();

        if ($user->type == "CaseManager"){
            $studentEmails = CaseManager::where('caseManagerEmail', $user->email)->pluck('studentEmail')->toArray();

            $supportHoursRequests = SupportHoursRequestResource::collection(SupportHoursRequest::whereIn('student_email', $studentEmails)->where('status', '=', "waiting")->paginate(10));

            return response()->json($supportHoursRequests, 200);
        } else {
            $supportHoursRequests = SupportHoursRequestResource::collection(SupportHoursRequest::whereIn('student_email', $user->email)->paginate(10));

            \Debugbar::info($supportHoursRequests);

            return response()->json($supportHoursRequests, 200);
        }

    }


    public function getSupportHoursRequests(Request $request) {

        $user = Auth::user();
        \Debugbar::info($user);

        if ($user->type == "CaseManager"){
            $studentEmails = CaseManager::where('caseManagerEmail', $user->email)->pluck('studentEmail')->toArray();

            $supportHoursRequests = SupportHoursRequestResource::collection(SupportHoursRequest::whereIn('student_email', $studentEmails)->where('status', '=', "waiting")->paginate(10));

            return response()->json($supportHoursRequests, 200);
        } else {
            $supportHoursRequests = SupportHoursRequestResource::collection(SupportHoursRequest::where('student_email', '=', $user->email)->get());

            \Debugbar::info($supportHoursRequests);

            return response()->json($supportHoursRequests, 200);
        }
    }

    public function setPlan(Request $request)
    {
        $dados = $request->validate([
            'studentId' => 'required|integer',
            'plan' => 'required|string',
            'diagnostic' => 'required|string',
            'selectedSupports' => ''
        ]);

        $newSupports = $dados['selectedSupports'];


        $plan = new EneeDiagnostic();
        $plan->studentId = $dados['studentId'];
        $plan->plan = $dados['plan'];
        $plan->diagnostic = $dados['diagnostic'];
        $plan->save();

        $student = User::findOrFail($dados['studentId']);

        $existingSupports = Student_Supports::where('email','=',$student->email)->pluck('id_support')->toArray();

        $supportsToAdd = array_diff($newSupports, $existingSupports);
        foreach($supportsToAdd as $support){
            $studentSupport = new Student_Supports();
            $studentSupport->email = $student->email;
            $studentSupport->id_support = $support;

            $sup = Support::where('id',$support)->first();

            $history = new History();
            $history->studentEmail = $student->email;
            $history->description = "O gestor de caso adicionou o apoio ".$sup->name." ao aluno.";
            $history->date = Carbon::now();
            $history->save();

//             EmailController::sendEmail('Foi lhe concedido o apoio '.$sup->name .'. Obrigado', $student->email, '[100%IN] Apoio concedido', '[100%IN] Apoio concedido');

            $studentSupport->save();
        }

        $supportsToDelete = array_diff($existingSupports,$newSupports);

        foreach($supportsToDelete as $support){
            $studentSupport = Student_Supports::where('id_support','=',$support)->first();

            $sup = Support::where('id',$support)->first();

            $history = new History();
            $history->studentEmail = $student->email;
            $history->description = "O gestor de caso eliminou o apoio \"".$sup->name."\" do aluno.";
            $history->date = Carbon::now();
            $history->save();

//             EmailController::sendEmail('Foi lhe removido o apoio '.$sup->name .'. Obrigado', $student->email, '[100%IN] Apoio removido', '[100%IN] Apoio removido');

            $studentSupport->delete();

        }


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
            $history->description = "O Gestor de Caso alterou o limite de horas de suporte do ENE para ".$request->newTotalHours.".";
            $history->date = Carbon::now();
            $history->save();

//             EmailController::sendEmail('O seu limite de horas foi alterado para '.$user->supportHours .'. Obrigado', $user->email, '[100%IN] Limite de Horas alterado', '[100%IN] Limite de Horas alterado');

            return response()->json(new UserResource($user), 200);
    }

    public function myMeetings(Request $request)
    {
        $user = Auth::user();
        $students = CaseManager::where('caseManagerEmail', $user->email)->pluck('studentEmail')->toArray();

       \Debugbar::info($request->requested);

    if($request->requested==0){
        $meetings = DB::table('meetings')->whereIn('email', $students)->where('service', 'Gestor-Caso')->whereNotNull('date')->orderBy('date','desc')->paginate(10);
}else if($request->requested==1){
        $meetings = DB::table('meetings')->whereIn('email', $students)->where('service', 'Gestor-Caso')->whereNull('date')->orderBy('requestDate','asc')->paginate(10);
}

        return response()->json($meetings, 200);
    }

    public function updatePlan($id, Request $request)
    {
        $plan = EneeDiagnostic::findOrFail($id);

        $dados = $request->validate([
            'plan' => 'required|string',
            'diagnostic' => 'required|string',
            'selectedSupports' => ''
        ]);

        $newSupports = $dados['selectedSupports'];

        $plan->plan = $dados['plan'];
        $plan->diagnostic = $dados['diagnostic'];
        $plan->save();

        $student = User::findOrFail($plan->studentId);

        $existingSupports = Student_Supports::where('email','=',$student->email)->pluck('id_support')->toArray();

        $supportsToAdd = array_diff($newSupports, $existingSupports);

        foreach($supportsToAdd as $support){
            $studentSupport = new Student_Supports();
            $studentSupport->email = $student->email;
            $studentSupport->id_support = $support;

            $sup = Support::where('id',$support)->first();

            $history = new History();
            $history->studentEmail = $student->email;
            $history->description = "O gestor de caso adicionou o apoio ".$sup->name." ao aluno.";
            $history->date = Carbon::now();
            $history->save();

//             EmailController::sendEmail('Foi lhe concedido o apoio '.$sup->name .'. Obrigado', $student->email, '[100%IN] Apoio concedido', '[100%IN] Apoio concedido');

            $studentSupport->save();
        }

        $supportsToDelete = array_diff($existingSupports,$newSupports);

        foreach($supportsToDelete as $support){
            $studentSupport = Student_Supports::where('id_support','=',$support)->first();

            $sup = Support::where('id',$support)->first();

            $history = new History();
            $history->studentEmail = $student->email;
            $history->description = "O gestor de caso eliminou o suporte \"".$sup->name."\" do aluno.";
            $history->date = Carbon::now();
            $history->save();

//             EmailController::sendEmail('Foi lhe removido o apoio '.$sup->name .'. Obrigado', $student->email, '[100%IN] Apoio removido', '[100%IN] Apoio removido');

            $studentSupport->delete();
        }

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

//         EmailController::sendEmail('Foi marcada uma reunião para ' . $meeting->date . ' às ' . $meeting->time . '. Para mais informação aceda ao menu das reuniões na Plataforma. Obrigado', $meeting->email, '[100%IN] Marcação de reunião', '[100%IN] Marcação de reunião');


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
        return ContactResource::collection(Contact::Where('studentEmail', $email)->orderBy('date', 'desc')->paginate(10));
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
            'interactionTime' => 'required',
            'service' => 'required',
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
        $contact->information = $dados['information'];
        $contact->contactMedium = $dados['contactMedium'];
        $contact->software = $dados['software'];
        $contact->place = $dados['place'];
        $contact->time = $dados['interactionTime'];
        $contact->save();

        if($dados['service'] === "Professor Orientador"){

            $aluno = User::where('email',$contact->studentEmail)->first();

            $professorOrientador = Tutor::where('studentEmail','=',$dados['email'])->first();

            $caseManager = CaseManager::where('studentEmail',$contact->studentEmail)->first();

            if($professorOrientador != null){
                if($contact->contactMedium == 'conferencia'){
//                     EmailController::sendEmail('Foi agendada uma interação com o aluno '.$aluno->name.'
//                     Detalhes da reunião:
//                     Data: '.$contact->date.'
//                     Hora: '.$contact->time.'
//                     Meio de Contacto: Video-Conferência
//                     Software: '.$contact->software.'
//                     Informação: '.$contact->information.'
//                     Em caso de alguma dúvida contacte o Gestor de Caso através do email '.$caseManager->caseManagerEmail.'
//                     Obrigado', $professorOrientador->tutorEmail, 'Marcação de interação com ENE', 'Marcação de interação com ENE');
                }elseif($contact->contactMedium == 'presencial'){
//                     EmailController::sendEmail('Foi agendada uma interação com o aluno '.$aluno->name.'
//                     Detalhes da reunião:
//                     Data: '.$contact->date.'
//                     Hora: '.$contact->time.'
//                     Meio de Contacto: Presencial
//                     Local: '.$contact->place.'
//                     Informação: '.$contact->information.'
//                     Em caso de alguma dúvida contacte o Gestor de Caso através do email '.$caseManager->caseManagerEmail.'
//                     Obrigado', $professorOrientador->tutorEmail, 'Marcação de interação com ENE', 'Marcação de interação com ENE');
                }else{
//                     EmailController::sendEmail('Foi agendada uma interação com o aluno '.$aluno->name.'
//                     Detalhes da reunião:
//                     Data: '.$contact->date.'
//                     Hora: '.$contact->time.'
//                     Meio de Contacto: Telefone
//                     Nº de Telefone do Aluno: '.$aluno->phoneNumber.'
//                     Informação: '.$contact->information.'
//                     Em caso de alguma dúvida contacte o Gestor de Caso através do email '.$caseManager->caseManagerEmail.'
//                     Obrigado', $professorOrientador->tutorEmail, 'Marcação de interação com ENE', 'Marcação de interação com ENE');
                }

//                 EmailController::sendEmail('O seu Gestor de Caso agendou uma interação com o Professor Orientador. Para mais informações consulte a Plataforma. Obrigado', $dados['email'], '[100%IN] Marcação de interação com Professor Orientador', '[100%IN] Marcação de interação com Professor Orientador');

            }
        }

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
        $history->description = "Foi agendado uma interação entre o estudante e o ".$contact->service;
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

    public function getENEHistories($email){
        $histories = History::where('studentEmail','=',$email)->orderBy('date','desc')->get();

        return response()->json($histories,200);
    }

    public function supportRequests()
    {
        $requests = DB::table('services')
            ->join('users', 'users.email', '=', 'services.email')
            ->join('supports', 'supports.id', '=', 'services.support')
            ->whereNull('services.aprovedDate')
            ->whereNull('services.rejectedDate')
            ->select('services.*', 'users.name', 'supports.name')
            ->paginate(10);

        return response()->json($requests, 200);
    }

    public function addStudentSupport($id)
    {
        $service = Service::findOrFail($id);
        $service->aprovedDate = Carbon::now();
        $service->save();

        $studentSupport = new Student_Supports();
        $studentSupport->email = $service->email;
        $studentSupport->id_support = $service->support;
        $studentSupport->save();

        $history = new History();
        $apoio = Support::findOrFail($service->support);
        $history->studentEmail = $service->email;
        $history->description = "O Gestor de Caso atribui o apoio " . $apoio->name;
        $history->date = Carbon::now();
        $history->save();

//             EmailController::sendEmail('Foi lhe concedido o apoio '.$apoio->name .'. Obrigado', $student->email, '[100%IN] Apoio concedido', '[100%IN] Apoio concedido');

        return response()->json($studentSupport, 200);
    }

    public function rejectStudentSupport($id)
    {
        $service = Service::findOrFail($id);
        $service->rejectedDate = Carbon::now();
        $service->save();

        $history = new History();
        $apoio = Support::findOrFail($service->support);
        $history->studentEmail = $service->email;
        $history->description = "O Gestor de Caso rejeitou o pedido do apoio " . $apoio->name . " ao estudante.";
        $history->date = Carbon::now();
        $history->save();

//             EmailController::sendEmail('Foi lhe rejeitado o apoio '.$apoio->name .'. Para mais informações consulte a Plataforma. Obrigado', $student->email, '[100%IN] Apoio rejeitado', '[100%IN] Apoio rejeitado');

        return response()->json($service, 200);
    }

    public function saveSupportNote(Request $request){
        $dados = $request->validate([
            'student_email' => 'required',
            'support_id' => 'required',
            'note' => 'required|string'
        ]);

        $note = Support_Note::where('student_email',$dados['student_email'])->where('support_id',$dados['support_id'])->first();

        if($note == null){
            $support_note = new Support_Note();
            $support_note->student_email = $dados['student_email'];
            $support_note->support_id = $dados['support_id'];
            $support_note->note = $dados['note'];
            $support_note->save();

            $support_notes = Support_Note::where('student_email',$support_note->student_email)->get();

        }else{
            $note->note = $dados['note'];
            $note->save();

            $support_notes = Support_Note::where('student_email',$note->student_email)->get();
        }

        return response()->json($support_notes,200);
    }

    public function getStudentSupportNotes($id){
        $student = User::findOrFail($id);

        $support_notes = Support_Note::where('student_email',$student->email)->get();

        return response()->json($support_notes,200);
    }
}
