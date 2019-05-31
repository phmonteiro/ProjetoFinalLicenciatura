<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Contact;
use App\Meeting;
use App\Tutor;
use App\CaseManager;
use Carbon\Carbon;
use App\Http\Resources\ContactResource;
use App\Http\Resources\MeetingResource;
use App\Http\Resources\HistoryResource;
use App\Http\Resources\UserResource;
use PDF;
use App\MedicalFile;
use ZipArchive;
use App\History;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return UserResource::collection(User::where('type', 'Estudante')->where('enee', 'approved')->paginate(10));
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
        //
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

    public function approve($id)
    {
        $user = User::findOrFail($id);

        $user->servicesApproval = 'approved';

        $user->save();

        return response()->json(200);
    }

    public function deny($id)
    {
        $user = User::findOrFail($id);

        $user->servicesApproval = 'denied';

        $user->save();

        return response()->json(200);
    }

    public function getServicesRequests()
    {
        return UserResource::collection(User::where('type', 'Estudante')->where('enee', 'awaiting')->where('servicesApproval', 'requested')->paginate(10));
    }


    public function finalizeMeeting(Request $request, $id)
    {
        $meeting = Meeting::findOrFail($id);
        $dados = $request->validate([
            'info' => 'required|string',
            'date' => 'required|date'
        ]);
        $meeting->info = $dados['info'];
        $meeting->date = $dados['date'];
        $meeting->save();

        $user = User::findOrFail($meeting->studentId);

        $history = new History();
        $history->studentEmail = $user->email;
        $history->description = "O " . $meeting->service . " agendou uma reunião com o estudante";
        $history->date = Carbon::now();
        $history->save();

        return response()->json($meeting, 200);
    }

    public function meetings()
    {
        return MeetingResource::collection(Meeting::whereNull('date')->paginate(10));
    }

    public function contact(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $dados = $request->validate([
            'information' => 'required|string',
            'nextContact' => 'required|date|after:today',
            'service' => 'required|string',
            'decision' => 'required|string'
        ]);

        $contact = new Contact();
        $contact->studentEmail = $user->email;
        $contact->date = Carbon::now();
        $contact->service = $dados['service'];
        $contact->information = $dados['information'];
        $contact->nextContact = $dados['nextContact'];
        $contact->decision = $dados['decision'];
        $contact->save();

        $history = new History();
        $history->studentEmail = $user->email;
        $history->description = "O " . $contact->service . " teve uma reunião com o estudante";
        $history->date = Carbon::now();
        $history->save();

        return response()->json(new ContactResource($contact), 201);
    }

    public function getHistory($id)
    {
        $user = User::findOrFail($id);
        $histories = History::where('studentEmail', $user->email)->orderBy('date', 'desc')->get();
        return response()->json(new HistoryResource($histories), 200);
    }

    public function editContact(Request $request, $id)
    {
        $dados = $request->validate([
            'nextContact' => 'required|date|after:today',
        ]);
        $contact = Contact::findOrFail($id);
        $contact->nextContact = $dados['nextContact'];
        $contact->save();

        $history = new History();
        $history->studentEmail = $contact->studentEmail;
        $history->description = "O " . $contact->service . " alterou a data da reunião com o estudante";
        $history->date = Carbon::now();
        $history->save();

        return response()->json(new ContactResource($contact), 201);
    }

    public function downloadPDF($id)
    {
        $user = User::findOrFail($id);
        $tutor = Tutor::where('studentEmail', $user->email)->get();
        $caseManager = CaseManager::where('studentEmail', $user->email)->get();
        $pdf = PDF::loadView('pdf.PDF', compact('user', 'caseManager', 'tutor'));
        $filename = base_path('storage/app/public/medicalHistory/' . $user->number . '.pdf');
        $pdf->save($filename);
        $files = MedicalFile::where('email', $user->email)->get();
        /*$filesDownload = new Collection();
        foreach ($files as $key => $file) {
            $filesDownload->push(base_path('storage/app/public/medicalHistory/' . $file->fileName));
        }
        $filesDownload->push($filename);*/

        $public_dir = base_path('storage/app/public/zip');
        // Zip File Name
        $zipFileName = $user->number . '.zip';
        // Create ZipArchive Obj
        $zip = new ZipArchive;
        if ($zip->open($public_dir . '/' . $zipFileName, ZipArchive::CREATE) === TRUE) {
            // Add Multiple file   
            foreach ($files as $file) {
                $zip->addFile(base_path('storage/app/public/medicalReport/' . $file->fileName), $file->fileName);
            }
            $zip->addFile($filename, $user->number . '.pdf');
            $zip->close();
        }
        // Set Header
        $headers = array(
            'Content-Type' => 'application/octet-stream',
        );
        $filetopath = $public_dir . '/' . $zipFileName;
        // Create Download Response
        if (file_exists($filetopath)) {
            return response()->download($filetopath, $zipFileName, $headers);
        }
    }
}
