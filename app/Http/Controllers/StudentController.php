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
use App\Supports;
use App\Student_Supports;
use App\User;
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

    public function getServices()
    {
        $user = Auth::user();
        $supports = Supports::all();

        $idSupports = Student_Supports::Where('email', $user->email)->pluck('support_value')->toArray();

        $studentSupports = $supports->whereIn('value', $idSupports)->all();

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
            'responsibleName' => 'required|string',
            'responsibleEmail' => 'required|email',
            'responsibleKin' => 'required|string',
            'responsiblePhone' => 'required|integer|regex:/[0-9]{9}/',
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
        $meeting->service = $dados['service'];
        $meeting->comment = $dados['comment'];
        $meeting->save();

        $history = new History();
        $history->studentEmail = $user->email;
        $history->description = "O estudante pediu para agendar uma reunião com o" . $meeting->service;
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

        $service = new Service();
        $service->email = $user->email;
        $service->name = $user->name;
        $service->support = $dados['requestedSupport'];
        $service->reason = $dados['reason'];

        $history = new History();
        $history->studentEmail = $user->email;
        $history->description = "O estudante requereu ao diretor o apoio de " . $service->name;
        $history->date = Carbon::now();
        $history->save();

        $service->save();

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

        $teacher = DB::table('teachers')->whereIn('subjectCode', $subjects)->get();
        return response()->json($teacher, 200);
    }

    public function supportHours($id)
    {
        $student = User::findOrFail($id);

        Debugbar::info("Suport HOURS FUNCTION");
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
            echo($subjects);
            return response()->json(new SubjectResource($subjects), 201);
        }else{
            $subjects=Subject::where('studentEmail',$student->email)->get();
             Debugbar::info($subjects);

            return response()->json(new SubjectResource($subjects), 201);
        }
    }

    public function setSupportHours(Request $request)
    {
        $user = Auth::user();
        $subject = Subject::where('studentEmail', $user->email)->pluck('hours');
        $subjectName = Subject::where('studentEmail', $user->email)->pluck('nome');

        if ($request->hours > 40) {
            return response()->json(['message' => 'Erro, número máximo  de horas excedido. Por favor tente novamente.'], 406);
        }

        $aux = 0;
        for ($i = 0; $i < sizeof($subject); $i++) {
            if (strcmp($subjectName[$i], $request->nome) != 0) {
                $aux += $subject[$i];
            }
        }
        if ($aux + $request->hours <= 40) {
        Debugbar::info($request->nome);
            $subject = Subject::Where('studentEmail', $user->email)->where('nome', $request->nome)->first();
            $subject->hours = $request->hours;
            $subject->save();
            $history = new History();
            $history->studentEmail = $user->email;
            $history->description = "O estudante pediu " . $subject->hours . " horas de acompanhamento para a unidade curricular de " . $subject->nome . "";
            $history->date = Carbon::now();
            $history->save();

            $teacher = Teacher::where('subjectCode', $subject->subjectCode)->get();
           // $tutor = Tutor::where('studentEmail', $user->email)->get();
            $caseManager = CaseManager::where('studentEmail', $user->email)->first();

            for ($i = 0; $i < sizeof($teacher); $i++) {
                if (true) {
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
        return UserResource::collection(User::where('type', 'Estudante')->where('enee', 'approved')->where('school', Auth::user()->school)->paginate(10));
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

}
