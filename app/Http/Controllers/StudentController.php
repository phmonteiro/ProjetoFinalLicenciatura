<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ContactResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\MeetingResource;
use App\Meeting;
use App\Contact;
use App\User;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $enees = User::where('enee', '1')->get();
        return new UserResource($enees);
    }

    public function myMeetings($id)
    {
        $user = User::findOrFail($id);

        $meetings = Meeting::all();
        return new MeetingResource($meetings);
    }

    public function setMeeting(Request $request, $id){
        $user = User::findOrFail($id);
        $dados = $request->validate([
            'service' => 'required|string',
            'comment' => 'required|string'
        ]);
        
        $meeting = new Meeting();
        $meeting->studentId = $id;
        $meeting->email = $user->email;
        $meeting->service = $dados['service'];
        $meeting->comment = $dados['comment'];

        $meeting->save();
        return response()->json (new MeetingResource($meeting), 201);

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

    public function getContacts($id)
    {
        $user = User::findOrFail($id);
        $contacts = Contact::where('studentEmail', $user->email)->orderBy('date', 'desc')->get();

        return new ContactResource($contacts);
    }
}
