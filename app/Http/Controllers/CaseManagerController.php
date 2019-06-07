<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use App\Http\Resources\UserResource;
use App\Http\Resources\ContactResource;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\User;
use App\CaseManager;
use App\Contact;
use App\Contacts_Files;


class CaseManagerController extends Controller
{

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
            'information' => 'required'
        ]);

       

        $contact = new Contact();
        $contact->studentEmail = $dados['email'];
        if($dados['interactionDate']==null){
            $contact->date = Carbon::now();
        } else {
            $contact->date = $dados['interactionDate'];
        }
        $contact->service = $dados['service'];
        $contact->decision = $dados['decision'];
        $contact->information = $dados['information'];
        $contact->nextContact = $dados['nextInteraction'];

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

        return response()->json(new ContactResource($contact), 200);
    }
}
