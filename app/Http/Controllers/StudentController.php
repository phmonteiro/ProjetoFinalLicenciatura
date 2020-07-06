<?php
/* eslint-disable */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ContactResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\MeetingResource;
use App\Http\Resources\ZipCodeResource;
use App\Meeting;
use App\Tutor;
use App\Contact;
use App\ZipCode;
use App\Support;
use App\Substitution;
use App\ServiceRequest;
use App\Student_Supports;
use App\User;
use App\Schedule;
use App\EneeDiagnostic;
use App\Http\Resources\ServiceResource;
use App\Service;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Carbon;
use App\MedicalFile;
use App\Nee;
use App\History;
use Illuminate\Support\Facades\Auth;
use App\Subject;
use App\Http\Resources\SubjectResource;
use PHPUnit\Framework\Constraint\IsEqual;
use App\Http\Resources\NeeResource;
use App\Http\Resources\SupportResource;
use App\Teacher;
use Illuminate\Support\Facades\DB;
use App\CaseManager;
use Barryvdh\Debugbar\Facade as Debugbar;

class StudentController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return UserResource::collection(User::where('type', 'Estudante')->where('school', Auth::user()->school)->where('enee', 'awaiting')->orWhere('enee', 'reproved')->paginate(10));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json(new UserResource($user), 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $user = Auth::user();
        $dados = $request->validate([
            'email' => 'required|email',
            'secondEmail' => 'nullable|email',
            'responsibleName' => 'required|string',
            'responsibleEmail' => 'required|email',
            'responsibleKin' => 'required|string',
            'responsiblePhone' => 'required|integer|regex:/[0-9]{9}/',
            'emergencyName' => 'required|string',
            'emergencyPhone' => 'required|integer|regex:/[0-9]{9}/',
            'emergencyEmail' => 'required|email',
            'emergencyKin' => 'required|string',
            'phoneNumber' => 'required|integer|regex:/[0-9]{9}/'
        ]);

        $user->secondEmail = $dados['secondEmail'];
        $user->responsibleName = $dados['responsibleName'];
        $user->responsibleEmail = $dados['responsibleEmail'];
        $user->responsibleKin = $dados['responsibleKin'];
        $user->responsiblePhone = $dados['responsiblePhone'];
        $user->emergencyName = $dados['emergencyName'];
        $user->emergencyPhone = $dados['emergencyPhone'];
        $user->emergencyEmail = $dados['emergencyEmail'];
        $user->emergencyKin = $dados['emergencyKin'];
        $user->phoneNumber = $dados['phoneNumber'];

        $history = new History();
        $history->studentEmail = $user->email;
        $history->description = "O estudante atualizou o seu perfil.";
        $history->date = Carbon::now();
        $history->save();

        $user->save();
        return response()->json(new UserResource($user), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getContacts()
    {
        $user = Auth::user();
        return ContactResource::collection(Contact::where('studentEmail', $user->email)->orderBy('date', 'desc')->paginate(10));
    }

    public function getSupportsByStudent()
    {
        $user = Auth::user();
        $supports = Support::all();

        $idSupports = Student_Supports::Where('email', $user->email)->pluck('id_support')->toArray();

        $studentSupports = $supports->whereIn('id', $idSupports)->all();

        return response()->json(new SupportResource($studentSupports));
    }

    public function subscription(Request $request)
    {
        $user = Auth::user();

        $dados = $request->validate([
            'name' => 'required|string',
            'number' => 'required|integer',
            'email' => 'required|email',
            'phoneNumber' => 'required|size:9',
            'birthDate' => 'required|date',
            'residence' => 'required|string',
            'zipCode' => 'required|regex:"\d\d\d\d[-]\d\d\d"',
            'area' => 'required|string',
            'identificationDocument' => 'required|string',
            'identificationNumber' => 'required|integer',
            'enruledYear' => 'required|size:4',
            'curricularYear' => 'required|integer|min:0',
            'responsibleName' => 'string',
            'responsibleEmail' => 'email',
            'responsibleKin' => 'string',
            'responsiblePhone' => 'integer|regex:/[0-9]{9}/',
            'emergencyName' => 'required|string',
            'emergencyPhone' => 'required|integer|regex:/[0-9]{9}/',
            'emergencyEmail' => 'required|email',
            'emergencyKin' => 'required|string',
            'gender' => 'required|string',
            'nif' => 'required|size:9',
            'niss' => 'required|size:11',
            'sns' => 'required|size:9',
            'educationalSupport' => '',
            'neeTypeDisease' => 'required_if:neeTypeAnotherDisease,true|string',
            'functionalAnalysis' => '',
        ]);

        for ($i = 0; $i < $request->numberPhotos; $i++) {
            $file = Input::file('photo' . $i);
            $ext = $file->getClientOriginalExtension();
            $uploadedFile = "MedicalReport - " . $dados['number'] . "-" . $i . '.' . $ext;
            Storage::disk('public')->put('medicalReport/' . $uploadedFile, File::get($file));
            $medicalFile = new MedicalFile();
            $medicalFile->email = $user->email;
            $medicalFile->fileName = $uploadedFile;
            $medicalFile->save();
        }

        $user->phoneNumber = $dados['phoneNumber'];
        $user->birthDate = (new Carbon($dados['birthDate']))->format('Y-m-d');
        $user->residence = $dados['residence'];
        $user->zipCode = $dados['zipCode'];
        $user->area = $dados['area'];
        $user->identificationDocument = $dados['identificationDocument'];
        $user->identificationNumber = $dados['identificationNumber'];
        $user->enruledYear = $dados['enruledYear'];
        $user->curricularYear = $dados['curricularYear'];
        $user->responsibleName = $dados['responsibleName'];
        $user->responsibleEmail = $dados['responsibleEmail'];
        $user->responsibleKin = $dados['responsibleKin'];
        $user->responsiblePhone = $dados['responsiblePhone'];
        $user->emergencyName = $dados['emergencyName'];
        $user->emergencyPhone = $dados['emergencyPhone'];
        $user->emergencyEmail = $dados['emergencyEmail'];
        $user->emergencyKin = $dados['emergencyKin'];
        $user->gender = $dados['gender'];
        $user->nif = $dados['nif'];
        $user->niss = $dados['niss'];
        $user->sns = $dados['sns'];
        $user->educationalSupport = $dados['educationalSupport'];
        $user->functionalAnalysis = $dados['functionalAnalysis'];
        $user->enee = 'awaiting';
        $user->dateEneeRequested = Carbon::now();
        $user->typeApplication = "Normal";

        $fileName="limite_horas.txt";

        if(Storage::exists($fileName)){
            $content = Storage::get($fileName);
            $user->supportHours = (int) $content;
        }else{
             $user->supportHours = null;

        }

        if ($request->neeTypeSight  == "true") {
            $nee = new Nee();
            $nee->studentEmail = $user->email;
            $nee->name = 'Visão';
            $nee->save();
        }

        if ($request->neeTypeEaring  == "true") {
            $nee = new Nee();
            $nee->studentEmail = $user->email;
            $nee->name = 'Audição';
            $nee->save();
        }

        if ($request->neeTypeMotor  == "true") {
            $nee = new Nee();
            $nee->studentEmail = $user->email;
            $nee->name = 'Motora';
            $nee->save();
        }

        if ($request->neeTypeAnotherDisease  == "true") {
            $nee = new Nee();
            $nee->studentEmail = $user->email;
            $nee->name = "Outro";
            $nee->otherName = $dados["neeTypeDisease"];
            $nee->save();
        }

        if ($request->neeTypeCommunication  == "true") {
            $nee = new Nee();
            $nee->studentEmail = $user->email;
            $nee->name = 'Dislexia/Disortografia/Disgrafia';
            $nee->save();
        }

        if ($request->neeTypeLearning  == "true") {
            $nee = new Nee();
            $nee->studentEmail = $user->email;
            $nee->name = 'Síndrome de Asperger/Deficit atenção';
            $nee->save();
        }

        if ($request->neeTypeMental  == "true") {
            $nee = new Nee();
            $nee->studentEmail = $user->email;
            $nee->name = 'Doenças do Foro Psicológico/neurológico/psiquiátrico';
            $nee->save();
        }

        $user->save();

        $history = new History();
        $history->studentEmail = $user->email;
        $history->description = "Candidatou-se ao estatuto de estudante com necessidade educativas especiais";
        $history->date = Carbon::now();
        $history->save();

        //Estudante a dizer que pedido foi enviado com sucesso
        //Diretor e coordenador de curso recebem email a dizer que têm um novo pedido de estatuto na plataforma

        //EmailController::sendEmail('O seu pedido para estatuto de estudante com necessidades educativas especias foi enviado com sucesso. Obrigado', $user->email, 'Candidatura a estatuto de ENEE', 'Candidatura a estatuto de ENEE');
        //EmailController::sendEmailWithCC('Tem uma nova candidatura ao pedido de estatuto de estudante com necessidades educativas especiais, para tratar na sua área pessoal. Obrigado', 'email do diretor', 'Novo pedido de ENEE', 'Novo pedido de ENEE', ' email do coordenador');

        return response()->json(new UserResource($user), 201);
    }



    public function getResidence($residence, $area)
    {
        $residence = ZipCode::where('art_desig', $residence)->where('dsc_pos', $area)->first();
        return response()->json(new ZipCodeResource($residence), 201);
    }


    public function myMeetingsStudent()
    {
        $user = Auth::user();
        return MeetingResource::collection(Meeting::where('email', $user->email)->Orderby('date')->paginate(10));
    }

    public function setMeeting(Request $request)
    {
        $user = Auth::user();
        $dados = $request->validate([
            'service' => 'required|string',
            'comment' => 'required|string'
        ]);

        $meeting = new Meeting();
        $meeting->studentId = $user->id;
        $meeting->email = $user->email;
        $meeting->name = $user->name;
        $meeting->requestDate = Carbon::now();
        $meeting->service = $dados['service'];
        $meeting->comment = $dados['comment'];
        $meeting->save();

        $history = new History();
        $history->studentEmail = $user->email;
        $history->description = "O estudante pediu para agendar uma reunião com " . $meeting->service;
        $history->date = Carbon::now();
        $history->save();

        $caseManagers = CaseManager::where('studentEmail', $user->email)->get();

        for ($i = 0; $i < sizeof($caseManagers); $i++) {
            //EmailController::sendEmail('O aluno ' . $user->name . ' pediu para agendar uma reunião. Obrigado', $caseManagers[$i]->email, 'Pedido de reunião', 'Pedido de reunião');
        }

        return response()->json(new MeetingResource($meeting), 201);
    }

    public function setService(Request $request)
    {
        $user = Auth::user();
        $dados = $request->validate([
            'requestedSupport' => 'required',
            'reason' => 'required|string'
        ]);


        foreach($dados['requestedSupport'] as $request){
           $service = new Service();
           $service->email = $user->email;
           $service->name = $user->name;
           $service->support = $request;
           $service->reason = $dados['reason'];
           $service->save();
        }

        $history = new History();
        $history->studentEmail = $user->email;
        $history->description = "O estudante requereu ao diretor o apoio de " . $service->name;
        $history->date = Carbon::now();
        $history->save();


        $caseManagers = CaseManager::where('studentEmail', $user->email)->get();

        for ($i = 0; $i < sizeof($caseManagers); $i++) {
            //EmailController::sendEmailWithCC('O aluno ' . $user->name . ' pediu um novo apoio. Obrigado', 'email do diretor', 'Pedido de novo apoio', 'Pedido de novo apoio', $caseManagers[$i]->email);
        }

        return response()->json(new ServiceResource($service), 201);
    }

    public function getStudentTutor($id)
    {
        $tutorEmail = Tutor::where('studentEmail', $id)->first('tutorEmail');
        return response()->json($tutorEmail, 200);
    }

    public function getTeacherStudent($id)
    {
        $user = User::findOrFail($id);
        $client = new \GuzzleHttp\Client();
        $aux = str_split(Carbon::now()->year, 2);
        if (Carbon::now()->month >= 9 && Carbon::now()->month <= 12) {
            $yearLective = Carbon::now()->year . "" . (int) $aux[1] + 1;
        } else {
            $yearLective = $aux[0] . "" . (int) $aux[1] - 1 . ""  . $aux[1];
        }
        $response = $client->request("GET", 'http://www.dei.estg.ipleiria.pt/intranet/horarios/ws/inscricoes/inscricoes_cursos.php?anoletivo=' . $yearLective . '&curso=' . $user->departmentNumber . '&estado=1&naluno=' . $user->number . '');
        $aux = $response->getBody()->getContents();
        $response = explode(';', $aux);
        $subjects = array();

        for ($i = 5; $i < sizeof($response); $i += 8) {
            $subject = trim(mb_convert_encoding($response[$i - 2], 'UTF-8', 'html-entities'));
            array_push($subjects, $subject);
        }

        $teachers = DB::table('teachers')->whereIn('subjectCode', $subjects)->get();
        return response()->json($teachers, 200);
    }

    public function supportHours($id)
    {
        $student = User::findOrFail($id);

        if (!Subject::where('studentEmail', $student->email)->exists()) {

            $client = new \GuzzleHttp\Client();
            $aux = str_split(Carbon::now()->year, 2);

            if (Carbon::now()->month >= 9 && Carbon::now()->month <= 12) {

                $yearLective = Carbon::now()->year . "" . (int) $aux[1] + 1;
            } else {

                $yearLective = $aux[0] . "" . (int) $aux[1] - 1 . ""  . $aux[1];
            }

            $response = $client->request("GET", 'http://www.dei.estg.ipleiria.pt/intranet/horarios/ws/inscricoes/inscricoes_cursos.php?anoletivo=' . $yearLective . '&curso=' . $student->departmentNumber . '&estado=1&naluno=' . $student->number . '');
            $aux = $response->getBody()->getContents();
            $response = explode(';', $aux);
            $subjects = array();
            Debugbar::info($response);
            for ($i = 5; $i < sizeof($response); $i += 8) {
                $subject = new Subject();
                $subject->studentEmail = Auth::user()->email;
                $subject->nome = trim(mb_convert_encoding($response[$i], 'UTF-8', 'html-entities'));
                $subject->semester = $response[$i + 1];
                $subject->hours = 0;
                $subject->subjectCode = trim(mb_convert_encoding($response[$i - 2], 'UTF-8', 'html-entities'));
                $subject->yearLective = $yearLective;
                $subject->save();
                array_push($subjects, $subject);
            }
//             echo($subjects);
            return response()->json(new SubjectResource($subjects), 201);
        }else{
            $subjects=Subject::where('studentEmail',$student->email)->get();

            return response()->json(new SubjectResource($subjects), 201);
        }
    }

    public function setSupportHours(Request $request)
    {
        $user = Auth::user();
        $subject = Subject::where('studentEmail', $user->email)->pluck('hours');
        $subjectName = Subject::where('studentEmail', $user->email)->pluck('nome');

        $horasApoioUsadas = 0;
        for ($i = 0; $i < sizeof($subject); $i++) {
            if (strcmp($subjectName[$i], $request->nome) != 0) {
                $horasApoioUsadas += $subject[$i];
            }
        }

        if ($request->hours > ($user->supportHours-$horasApoioUsadas)) {
            return response()->json(['message' => 'Erro, número máximo  de horas excedido. Por favor tente novamente.'], 406);
        }


        if ($horasApoioUsadas + $request->hours <= $user->supportHours) {
            $subject = Subject::Where('studentEmail', $user->email)->where('nome', $request->nome)->first();
            $subject->hours = $request->hours;
            $subject->save();
            $history = new History();
            $history->studentEmail = $user->email;
            $history->description = "O estudante pediu " . $subject->hours . " horas de acompanhamento para a unidade curricular de " . $subject->nome . "";
            $history->date = Carbon::now();
            $history->save();

            $teacher = Teacher::where('subjectCode', $subject->subjectCode)->get();
            $tutor = Tutor::where('studentEmail', $user->email)->get();
            $caseManager = CaseManager::where('studentEmail', $user->email)->first();

            for ($i = 0; $i < sizeof($teacher); $i++) {
                if ($tutor) {
                    //troller::sendEmailWithCC('O estudante ' . $user->name . ' pediu para ter um acompanhamento individualizado de ' . $subject->hours . ' na UC de ' . $subject->nome . '. Obrigado', $teacher[$i]->email, 'Pedido de acompanhamento individualizado', 'Pedido acompanhamento individualizado', $tutor->tutorEmail);
                } else {
                    //EmailController::sendEmailWithCC('O estudante ' . $user->name . ' pediu para ter um acompanhamento individualizado de ' . $subject->hours . ' na UC de ' . $subject->nome . '. Obrigado', $teacher[$i]->email, 'Pedido de acompanhamento individualizado', 'Pedido acompanhamento individualizado', $caseManager->caseManagerEmail);
                }
            }

            return response()->json(new SubjectResource($subject), 200);
        } else {
            return response()->json(['message' => 'Erro, número máximo  de horas excedido. Por favor tente novamente.'], 406);
        }
    }

    public function enee()
    {
        return UserResource::collection(User::where('type', 'Estudante')->where('enee', 'approved')->where('school', Auth::user()->school)->whereNull('inactive')->paginate(10));
    }

    public function setEneeStatusExpired($id){
        $student = User::findOrFail($id);
        $student->enee = "expired";
        $student->coordinatorApproval = null;
        $student->servicesApproval = null;


        $services = ServiceRequest::where('studentEmail','=',$student->email)->get();

        if($services!=null){
            foreach($services as $service){
                $service->delete();
            }
        }

        $supports = Student_Supports::where('email','=',$student->email)->get();

                if($supports!=null){
                    foreach($supports as $support){
                        $support->delete();
                    }
                }

        $nees = Nee::where('studentEmail','=',$student->email)->get();

                if($nees!=null){
                    foreach($nees as $nee){
                        $nee->delete();
                    }
                }

        $medical_files = MedicalFile::where('email','=',$student->email)->get();

                if($medical_files!=null){
                    foreach($medical_files as $medical_file){
                        $medical_file->delete();
                    }
                }

        $student->save();

        return response()->json(new UserResource($student),200);
    }

    public function getNee($id)
    {
        $user = User::findOrFail($id);
        $nees = Nee::where('studentEmail', $user->email)->get();
        return response()->json(new NeeResource($nees), 200);
    }

    public function getTotalSupportHours($id){
        $user = User::findOrFail($id);

        return response()->json($user->supportHours, 200);
    }

//     public function transferAccountStatus(Request $request){
//         $dados = $request->validate([
//             'email' => 'required',
//             'password' => 'required',
//         ]);
//
//         $oldUser = Auth::user();
//         $oldAccountEmail = $oldUser->email;
//         $oldAccountId = $oldUser->id;
//
//         if (Auth::attempt(['email' => $dados['email'], 'password' => $dados['password']])) {
//             $user = Auth::user();
//
//             if($oldUser->created_at > $user->created_at){
//                 return response()->json([message=>"Contiga antiga de utilizador mais recente do que a conta nova!"]);
//             }
//
//             if ($user->firstLogin == 1) {
//
//                 transferAccountData($oldAccountEmail,$oldAccountId,$user->email,$user->id,$user);
//
//                 $token = $user->createToken(rand())->accessToken;
//                 return response()->json(['user' => Auth::user()], 200)->header('Authorization', $token);
//             } else {
//                 $users = \Adldap\Laravel\Facades\Adldap::search()->find($dados['email']);
//                 $user->type = $users->title[0];
//                 $user->course = $users->description[0];
//                 $user->school = $users->company[0];
//                 $user->number = $users->mailnickname[0];
//                 $user->departmentNumber = $users->departmentnumber[0];
//                 $user->firstLogin = 1;
//                 $user->save();
//                 $token = $user->createToken(rand())->accessToken;
//
//                 transferAccountData($oldAccountEmail,$oldAccountId,$user->email,$user->id,$user);
//
//                 return response()->json(200);
//             }
//         } else {
//             auth()->logout();
//             return response()->json(['message' => 'Credenciais inválidas. Por favor tente novamente.'], 401);
//         }
//     }

    public function transferAccountStatus(){
        $user = Auth::user();
        $newAccountEmail = $user->email;
        $newAccountId = $user->id;

        $previousUserAccountId = User::where('nif','=',$user->nif)->where('inactive','=','1')->max('id');
        $previousUserAccount = User::where('id','=',$previousUserAccountId)->first();

        $oldAccountId = $previousUserAccount->id;
        $oldAccountEmail = $previousUserAccount->email;


        //CaseManager transfer
        $caseManager = CaseManager::where('studentEmail','=',$oldAccountEmail)->first();

        if($caseManager != null){
            $caseManager->studentEmail = $newAccountEmail;
            $caseManager->save();
        }

        //Contact transfer
        $contacts = Contact::where('studentEmail','=',$oldAccountEmail)->get();

        if($contacts != null){
            foreach($contacts as $contact){
                $contact->studentEmail = $newAccountEmail;
                $contact->save();
            }
        }

        //EneeDiagnostic transfer
        $diagnostics = EneeDiagnostic::where('studentId','=',$oldAccountId)->get();

        if($diagnostics != null){
            foreach($diagnostics as $diagnostic){
                $diagnostic->studentId = $newAccountId;
                $diagnostic->save();
            }
        }

        //Histories transfer
        $histories = History::where('studentEmail','=',$oldAccountEmail)->get();

        if($histories != null){
            foreach($histories as $history){
                $history->studentEmail = $newAccountEmail;
                $history->save();
            }
        }

        //MedicalFiles transfer
        $medicalFiles = MedicalFile::where('email','=',$oldAccountEmail)->get();

        if($medicalFiles != null){
            foreach($medicalFiles as $medicalFile){
                $medicalFile->email = $newAccountEmail;
                $medicalFile->save();
            }
        }

        //Meetings transfer
        $meetings = Meeting::where('email','=',$oldAccountEmail)->get();

        if($meetings != null){
            foreach($meetings as $meeting){
                $meeting->email = $newAccountEmail;
                $meeting->studentId = $newAccountId;
                $meeting->save();
            }
        }

        //NEEs transfer
        $nees = Nee::where('studentEmail','=',$oldAccountEmail)->get();

        if($nees != null){
            foreach($nees as $nee){
                $nee->studentEmail = $newAccountEmail;
                $nee->save();
            }
        }

        //Schedules transfer
        $schedules = Schedule::where('email','=',$oldAccountEmail)->get();

        if($schedules != null){
            foreach($schedules as $schedule){
                $schedule->email = $newAccountEmail;
                $schedule->save();
            }
        }

        //ServiceRequests transfer
        $serviceRequests = ServiceRequest::where('studentEmail','=',$oldAccountEmail)->get();

        if($serviceRequests != null){
            foreach($serviceRequests as $request){
                $request->studentEmail = $newAccountEmail;
                $request->save();
            }
        }

        //Services transfer
        $services = Service::where('email','=',$oldAccountEmail)->get();

        if($services != null){
            foreach($services as $service){
                $service->email = $newAccountEmail;
                $service->save();
            }
        }

        //Student_Supports transfer
        $studentSupports = Student_Supports::where('email','=',$oldAccountEmail)->get();

        if($studentSupports != null){
            foreach($studentSupports as $support){
                $support->email = $newAccountEmail;
                $support->save();
            }
        }
        //Subjects transfer
        $subjects = Subject::where('studentEmail','=',$oldAccountEmail)->get();

        if($subjects != null){
            foreach($subjects as $subject){
                $subject->studentEmail = $newAccountEmail;
                $subject->save();
            }
        }

        //Substitutions transfer
        $substitutions = Substitution::where('emailStudent','=',$oldAccountEmail)->get();

        if($substitutions != null){
            foreach($substitutions as $sub){
                $sub->emailStudent = $newAccountEmail;
                $sub->save();
            }
        }

        //Tutors transfer
        $tutors = Tutor::where('studentEmail','=',$oldAccountEmail)->get();

        if($tutors != null){
            foreach($tutors as $tutor){
                $tutor->studentEmail = $newAccountEmail;
                $tutor->save();
            }
        }

        $oldUser = User::where('email','=',$oldAccountEmail)->first();

//         $user->phoneNumber = $oldUser->phoneNumber;
//         $user->birthDate = $oldUser->birthDate;
//         $user->zipCode = $oldUser->zipCode;
//         $user->residence = $oldUser->residence;
//         $user->area = $oldUser->area;
        $user->identificationDocument = $oldUser->identificationDocument;
        $user->identificationNumber = $oldUser->identificationNumber;
//         $user->nif = $oldUser->nif;
        $user->niss = $oldUser->niss;
        $user->sns = $oldUser->sns;
//         $user->curricularYear = $oldUser->curricularYear;
//         $user->enruledYear = $oldUser->enruledYear;
        $user->enee = $oldUser->enee;
        $user->eneeExpirationDate = $oldUser->eneeExpirationDate;
        $user->responsibleEmail = $oldUser->responsibleEmail;
        $user->responsibleName = $oldUser->responsibleName;
        $user->responsiblePhone = $oldUser->responsiblePhone;
        $user->responsibleKin = $oldUser->responsibleKin;
        $user->emergencyEmail = $oldUser->emergencyEmail;
        $user->emergencyName = $oldUser->emergencyName;
        $user->emergencyKin = $oldUser->emergencyKin;
        $user->emergencyPhone = $oldUser->emergencyPhone;
        $user->coordinatorApproval = $oldUser->coordinatorApproval;
        $user->functionalAnalysis = $oldUser->functionalAnalysis;
        $user->servicesApproval = $oldUser->servicesApproval;
        $user->secondEmail = $oldUser->secondEmail;
        $user->gender = $oldUser->gender;
        $user->dateEneeRequested = $oldUser->dateEneeRequested;
        $user->dateEneeApproved = $oldUser->dateEneeApproved;
        $user->typeApplication = $oldUser->typeApplication;
        $user->supportHours = $oldUser->supportHours;
        $user->educationalSupport = $oldUser->educationalSupport;
        $user->transferAccountStatus = "transferred";
        $user->save();

        //enviar emails

        $history = new History();
        $history->studentEmail = $user->email;
        $history->description = "O estudante transferiu a conta " . $oldAccountEmail . " para a conta " . $newAccountEmail . "";
        $history->date = Carbon::now();
        $history->save();

        return response()->json(200);
    }

    public function getAcademicRecord($id){

            $student = User::findOrFail($id);

            $client = new \GuzzleHttp\Client();
            $aux = str_split(Carbon::now()->year, 2);

            if (Carbon::now()->month >= 9 && Carbon::now()->month <= 12) {

                $yearLective = Carbon::now()->year . "" . (int) $aux[1] + 1;
            } else {

                $yearLective = $aux[0] . "" . (int) $aux[1] - 1 . ""  . $aux[1];
            }
//          UCs inscritas
            $response = $client->request("GET", 'http://www.dei.estg.ipleiria.pt/intranet/horarios/ws/inscricoes/inscricoes_cursos.php?anoletivo=' . $yearLective . '&curso=' . $student->departmentNumber . '&estado=1&naluno=' . $student->number . '');
            $aux = $response->getBody()->getContents();
            $response = explode(';', $aux);
            $subjects = array();
            \Debugbar::info($response);

            for ($i = 5; $i < sizeof($response); $i += 8) {

                $subject = new Subject();
                $subject->studentEmail = Auth::user()->email;
                $subject->nome = trim(mb_convert_encoding($response[$i], 'UTF-8', 'html-entities'));
                $subject->semester = $response[$i + 1];
                $subject->hours = 0;
                $subject->subjectCode = trim(mb_convert_encoding($response[$i - 2], 'UTF-8', 'html-entities'));
                $subject->yearLective = $yearLective;
                $subject->estado = "Inscrito";

                array_push($subjects, $subject);
            }

//          UCs Aprovadas
            $response = $client->request("GET", 'http://www.dei.estg.ipleiria.pt/intranet/horarios/ws/inscricoes/inscricoes_cursos.php?anoletivo=' . $yearLective . '&curso=' . $student->departmentNumber . '&estado=2&naluno=' . $student->number . '');
            $aux = $response->getBody()->getContents();
            $response = explode(';', $aux);
            \Debugbar::info($response);

            for ($i = 5; $i < sizeof($response); $i += 8) {
                $subject = new Subject();
                $subject->studentEmail = Auth::user()->email;
                $subject->nome = trim(mb_convert_encoding($response[$i], 'UTF-8', 'html-entities'));
                $subject->semester = $response[$i + 1];
                $subject->hours = 0;
                $subject->subjectCode = trim(mb_convert_encoding($response[$i - 2], 'UTF-8', 'html-entities'));
                $subject->yearLective = $yearLective;
                $subject->estado = "Aprovado";

                array_push($subjects, $subject);
            }
//        UCs Reprovadas
        $response = $client->request("GET", 'http://www.dei.estg.ipleiria.pt/intranet/horarios/ws/inscricoes/inscricoes_cursos.php?anoletivo=' . $yearLective . '&curso=' . $student->departmentNumber . '&estado=3&naluno=' . $student->number . '');
        $aux = $response->getBody()->getContents();
        $response = explode(';', $aux);
        \Debugbar::info($response);

        for ($i = 5; $i < sizeof($response); $i += 8) {

            $subject = new Subject();
            $subject->studentEmail = Auth::user()->email;
            $subject->nome = trim(mb_convert_encoding($response[$i], 'UTF-8', 'html-entities'));
            $subject->semester = $response[$i + 1];
            $subject->hours = 0;
            $subject->subjectCode = trim(mb_convert_encoding($response[$i - 2], 'UTF-8', 'html-entities'));
            $subject->yearLective = $yearLective;
            $subject->estado = "Reprovado";

            array_push($subjects, $subject);
        }

        \Debugbar::info($subjects);

        return response()->json($subjects,200);
    }

    public function updateTransferredAccount(Request $request){

         $user = Auth::user();

         $dados = $request->validate([
             'phoneNumber' => 'required|size:9',
             'residence' => 'required|string',
             'zipCode' => 'required|regex:"\d\d\d\d[-]\d\d\d"',
             'area' => 'required|string',
             'identificationDocument' => 'required|string',
             'identificationNumber' => 'required|integer',
             'enruledYear' => 'required|size:4',
             'curricularYear' => 'required|integer|min:0',
             'responsibleName' => 'string',
             'responsibleEmail' => 'email',
             'responsibleKin' => 'string',
             'responsiblePhone' => 'integer|regex:/[0-9]{9}/',
             'emergencyName' => 'required|string',
             'emergencyPhone' => 'required|integer|regex:/[0-9]{9}/',
             'emergencyEmail' => 'required|email',
             'emergencyKin' => 'required|string',
             'niss' => 'required|size:11',
             'sns' => 'required|size:9',
         ]);

        $user->phoneNumber = $dados['phoneNumber'];
        $user->residence = $dados['residence'];
        $user->zipCode = $dados['zipCode'];
        $user->area = $dados['area'];
        $user->identificationDocument = $dados['identificationDocument'];
        $user->identificationNumber = $dados['identificationNumber'];
        $user->enruledYear = $dados['enruledYear'];
        $user->curricularYear = $dados['curricularYear'];
        $user->responsibleName = $dados['responsibleName'];
        $user->responsibleEmail = $dados['responsibleEmail'];
        $user->responsibleKin = $dados['responsibleKin'];
        $user->responsiblePhone = $dados['responsiblePhone'];
        $user->emergencyName = $dados['emergencyName'];
        $user->emergencyPhone = $dados['emergencyPhone'];
        $user->emergencyEmail = $dados['emergencyEmail'];
        $user->emergencyKin = $dados['emergencyKin'];
        $user->niss = $dados['niss'];
        $user->sns = $dados['sns'];
        $user->save();

        return response()->json(new UserResource($user), 201);
    }

    public function getSupportRequestsByStudent(){
        $user = Auth::user();

        $supports = Service::where('email','=',$user->email)->pluck('support');

        return response()->json($supports,200);
    }

}
