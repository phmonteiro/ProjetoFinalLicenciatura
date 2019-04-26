<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Contact;
use Carbon\Carbon;
use App\Http\Resources\ContactResource;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { }

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
        return response()->json (new ContactResource($contact), 201);
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
}
