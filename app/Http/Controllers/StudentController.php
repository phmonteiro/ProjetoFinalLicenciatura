<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ContactResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\MeetingResource;
use App\Http\Resources\ZipCodeResource;
use App\Meeting;
use App\Contact;
use App\ZipCode;
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

class StudentController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return UserResource::collection(User::where('type', 'Estudante')->where('enee', 'awaiting')->orWhere('enee', 'reproved')->paginate(10));
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
    public function edit($id)
    {
        //
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

    public function getContacts($id)
    {
        $user = User::findOrFail($id);
        return ContactResource::collection(Contact::where('studentEmail', $user->email)->orderBy('date', 'desc')->paginate(10));
    }

    public function getServices($id)
    {
        $user = User::findOrFail($id);
        return ServiceResource::collection(Service::where('email', $user->email)->where('aprovedDate', '!=', 'null')->paginate(10));
    }

    public function subscription(Request $request, $id)
    {
        $user = User::findOrFail($id);
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
            'nif' => 'required|size:9',
            'niss' => 'required|size:11',
            'sns' => 'required|size:9',
            'educationalSupport' => '',
            'neeTypeDisease' => 'required_if:neeTypeAnotherDisease,true|string',
            'functionalAnalysis' => ''
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
        $user->birthDate = $dados['birthDate'];
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
        $user->nif = $dados['nif'];
        $user->niss = $dados['niss'];
        $user->sns = $dados['sns'];
        $user->educationalSupport = $dados['educationalSupport'];
        $user->functionalAnalysis = $dados['functionalAnalysis'];
        $user->enee = 'awaiting';

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
            $nee->name = $dados['neeTypeDisease'];
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

        return response()->json(new UserResource($user), 201);
    }


    public function getResidence($residence, $area)
    {
        $residence = ZipCode::where('art_desig', $residence)->where('dsc_pos', $area)->first();
        return response()->json(new ZipCodeResource($residence), 201);
    }
    public function myMeetings($id)
    {
        User::findOrFail($id);
        return MeetingResource::collection(Meeting::Orderby('date')->paginate(10));
    }

    public function setMeeting(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $dados = $request->validate([
            'service' => 'required|string',
            'comment' => 'required|string'
        ]);

        $meeting = new Meeting();
        $meeting->studentId = $id;
        $meeting->email = $user->email;
        $meeting->name = $user->name;
        $meeting->service = $dados['service'];
        $meeting->comment = $dados['comment'];
        $meeting->save();
        return response()->json(new MeetingResource($meeting), 201);
    }

    public function setService(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $dados = $request->validate([
            'name' => 'required|string',
            'reason' => 'required|string'
        ]);

        $service = new Service();
        $service->email = $user->email;
        $service->name = $dados['name'];
        $service->reason = $dados['reason'];

        $service->save();
        return response()->json(new ServiceResource($service), 201);
    }
}
