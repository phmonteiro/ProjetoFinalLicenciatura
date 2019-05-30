<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;    
use App\User;
use App\CaseManager;
use App\Http\Resources\UserResource;
use App\Http\Resources\CaseManagerResource;


class DirectorController extends Controller
{
    public function approvalRequest($id)
    {
        $user = User::findOrFail($id);
        $user->servicesApproval = 'requested';

        $user->save();
        return response()->json($user, 200);
    }
}
