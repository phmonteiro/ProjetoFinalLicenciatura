<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\Tutor;
use App\Http\Resources\UserResource;
use App\Http\Resources\CaseManagerResponsibleResource;
use App\SupportHoursRequest;
use App\Service;
use App\Supports;
use App\Coordinator;
use App\Student_Supports;
use App\MedicalFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Teacher;
use Carbon\Carbon;
use App\ServiceRequest;
use App\History;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class TeacherController extends Controller
{
    public function getTeacherSupports(){
        $user = Auth::user();

        $supports = SupportHoursRequest::where('teacher_email','=',$user->email)->where('status','approved')->paginate(10);

        return response()->json([$supports],200);
    }

    public function createSummary($id, Request $request){
        $dados = $request->validate([
            'summary' => 'required',
            'date' => 'required',
            'hours' => 'required',
        ]);

        $summary = new SupportSummary();
        $summary->request_id = $id;
        $summary->summary = $dados['summary'];
        $summary->date = $dados['date'];
        $summary->hours = $dados['hours'];
        $summary->save();

        return response()->json(200);
    }
}
