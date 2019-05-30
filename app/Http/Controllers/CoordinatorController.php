<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\User;

class CoordinatorController extends Controller
{
    public function requests()
    {
        return UserResource::collection(User::where('type', 'Estudante')->where('enee', 'awaiting')->whereNull('coordinatorApproval')->paginate(10));
    }

    public function approve($id)
    {
        $user = User::findOrFail($id);

        $user->coordinatorApproval = '1';

        $user->save();

        return response()->json(200);
    }

    public function deny($id)
    {
        $user = User::findOrFail($id);

        $user->coordinatorApproval = '0';

        $user->save();

        return response()->json(200);
    }
}
