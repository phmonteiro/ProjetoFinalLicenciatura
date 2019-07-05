<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Supports;
use App\Http\Resources\UserResource;
use App\Http\Resources\SupportResource;
use Carbon\Carbon;
use App\Teacher;
use App\Http\Resources\TeacherResource;
use App\Coordinator;
use App\Http\Resources\CoordinatorResource;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return UserResource::collection(User::Orderby('number')->paginate(10));
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
        $user = User::findOrFail($id);
        $dados = $request->validate([
            'type' => 'required',
        ]);

        $user->type = $dados['type'];
        $user->save();

        return response()->json(new UserResource($user), 200);
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

    public function getTeachers()
    {
        $client = new \GuzzleHttp\Client();
        $aux = str_split(Carbon::now()->year, 2);
        if (Carbon::now()->month >= 9 && Carbon::now()->month <= 12) {
            $yearLective = Carbon::now()->year . "" . (int) $aux[1] + 1;
            $semester = 1;
        } else {
            $yearLective = $aux[0] . "" . (int) $aux[1] - 1 . ""  . $aux[1];
            $semester = 2;
        }
        $response = $client->request("GET", 'http://www.dei.estg.ipleiria.pt/intranet/horarios/ws/inscricoes/cursos_ucs.php?anoletivo=' . $yearLective . '&periodo=S' . $semester . '');
        $aux = $response->getBody()->getContents();
        $response = explode(';', $aux);

        $teachers = array();
        for ($i = 4; $i < sizeof($response); $i += 4) {
            $aux = explode(',', $response[$i]);
            for ($h = 0; $h < sizeof($aux); $h++) {
                $teacher = new Teacher();
                $name = explode('(', $aux[$h]);
                $email = explode(')', $name[1]);
                $teacher->email = $email[0] . '@my.ipleiria.pt';
                $teacher->name = mb_convert_encoding($name[0], 'UTF-8', 'html-entities');
                $teacher->subject = mb_convert_encoding($response[$i - 1], 'UTF-8', 'html-entities');
                $teacher->subjectCode = $response[$i - 2];
                $teacher->semester =  $semester;
                $teacher->yearLective = $yearLective;
                $teacher->save();
                array_push($teachers, $teacher);
            }
        }
        return response()->json(new TeacherResource($teachers), 201);
    }

    public function addCoordinator(Request $request)
    {
        $dados = $request->validate([
            'email' => 'required|email',
            'course' => 'required|string',
            'departmentNumber' => 'required|digits:4',
            'school' => 'required|string'
        ]);

        $user = \Adldap\Laravel\Facades\Adldap::search()->find($dados['email']);
        if ($user) {
            $coordinator = new Coordinator();
            $coordinator->email = $dados['email'];
            $coordinator->course = $dados['course'];
            $coordinator->departmentNumber = $dados['departmentNumber'];
            $coordinator->school = $dados['school'];
            $coordinator->save();

            return response()->json(new CoordinatorResource($coordinator), 201);
        }

        return response()->json(['message' => 'Email do coordenador de curso não existe. Por favor introduza uma email válido (email@ipleiria.pt).'], 401);
    }
}
