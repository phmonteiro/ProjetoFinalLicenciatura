<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\CaseManagerResource;
use App\CaseManager;

class CaseManagerController extends Controller
{
    public function index()
    {
        return CaseManagerResource::collection(CaseManager::where('approved', '1')->paginate(10));
    }

    public function forApproval()
    {
        return CaseManagerResource::collection(CaseManager::where('approved', '0')->paginate(10));
    }

    public function approveCM($id){
        $cm = CaseManager::findOrFail($id);
        $cm->approved = 1;
        $cm->save();
        return response()->json(new CaseManagerResource($cm), 201);
    }
}
