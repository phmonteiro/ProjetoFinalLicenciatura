<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\CaseManager;
use App\Supports;
use App\Http\Resources\UserResource;
use App\Http\Resources\SupportResource;
use Carbon\Carbon;
use App\Teacher;
use App\Http\Resources\TeacherResource;
use App\Coordinator;
use App\Http\Resources\CoordinatorResource;
use Storage;
use File;
use Barryvdh\Debugbar\Facade as Debugbar;
use Auth;
use Config;
use stdClass;


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

            $admin = User::where('type','Administrador')->first();

            if($admin!=null){
//               EmailController::sendEmail('Foi adicionado à Plataforma de Acompanhamento de Estudantes com Necessidades Específicas (ENE) do IPLeiria, como Coordenador de Curso. Se tiver alguma dúvida contacte o admnistrador da Plataforma através do email '.$admin->email.'.', $user->email, 'Atribuição de função na Plataforma 100%IN', 'Atribuição de função na Plataforma 100%IN');
            }else{
//               EmailController::sendEmail('Foi adicionado à Plataforma de Acompanhamento de Estudantes com Necessidades Específicas (ENE) do IPLeiria, como Coordenador de Curso. Se tiver alguma dúvida contacte o admnistrador da Plataforma.', $user->email, 'Atribuição de função na Plataforma 100%IN', 'Atribuição de função na Plataforma 100%IN');
            }

            return response()->json(new CoordinatorResource($coordinator), 201);
        }

        return response()->json(['message' => 'Email do coordenador de curso não existe. Por favor introduza uma email válido (email@ipleiria.pt).'], 401);
    }

   public function setHoursLimit(Request $request){
        $limite = $request->newLimitHours;

        \Debugbar::info($request);

        Storage::put("limite_horas.txt",$limite);

        return response()->json(200);
   }

   public function getHoursLimit(Request $request){
    $fileName="limite_horas.txt";

    if(Storage::exists($fileName)){

        $content = Storage::get($fileName);

        return response()->json($content,200);
    }else{
        return response()->json(['message'=>"Ficheiro não encontrado!"],500);
    }

   }

   public function setResponsibleCM(Request $request){

        $user = User::where("email","=",$request->emailResponsibleCaseManager)->first();

        if($user == null){
            $users = \Adldap\Laravel\Facades\Adldap::search()->find($request->emailResponsibleCaseManager);

            $user = null;

            $user = new User();

            $user->email = $users->mail[0];
            $user->name = $users->displayname[0];
            $user->type = 'CaseManagerResponsible';
            $user->course = $users->description[0];
            $user->school = $users->company[0];
            $user->number = $users->mailnickname[0];
            $user->departmentNumber = $users->departmentnumber[0];
            $user->firstLogin = 1;
            $user->save();

            $admin = User::where('type','Administrador')->first();

            if($admin!=null){
//               EmailController::sendEmail('Foi adicionado à Plataforma de Acompanhamento de Estudantes com Necessidades Específicas (ENE) do IPLeiria, como Responsável dos Gestores de Caso. Se tiver alguma dúvida contacte o admnistrador da Plataforma através do email '.$admin->email.'.', $user->email, 'Atribuição de função na Plataforma 100%IN', 'Atribuição de função na Plataforma 100%IN');
            }else{
//               EmailController::sendEmail('Foi adicionado à Plataforma de Acompanhamento de Estudantes com Necessidades Específicas (ENE) do IPLeiria, como Responsável dos Gestores de Caso. Se tiver alguma dúvida contacte o admnistrador da Plataforma.', $user->email, 'Atribuição de função na Plataforma 100%IN', 'Atribuição de função na Plataforma 100%IN');
            }

            return response()->json("Responsible Case Manager adicionado com sucesso!",200);
        }else{
        \Debugbar::info($user);

            if($user->type != "CaseManager"){
                $user->type = "CaseManagerResponsible";

//                 EmailController::sendEmail('Foi adicionado à Plataforma de Acompanhamento de Estudantes com Necessidades Específicas (ENE) do IPLeiria, como Responsável dos Gestores de Caso. Se tiver alguma dúvida contacte o admnistrador da Plataforma.', $user->email, 'Atribuição de função na Plataforma 100%IN', 'Atribuição de função na Plataforma 100%IN');

                $user->save();
            }else{
                $cm = CaseManager::where("caseManagerEmail","=",$request->emailResponsibleCaseManager)->where("emailMainCaseManager","=",$request->emailResponsibleCaseManager)->get();

                if(!$cm->isEmpty()){
                    return response()->json(['message' => "O colaborador (".$request->emailResponsibleCaseManager.") já está definido como Gestor de Caso de ".count($cm)." alunos. Entre em contacto com o Responsável dos Gestores de Caso para que efectue uma substituição permanente e assim libertar o colaborador em questão para assumir a função de Responsável de Gestores de Caso.  !"],201);
                }else{
                    $user->type="CaseManagerResponsible";
                    $user->save();

//                     EmailController::sendEmail('Foi adicionado à Plataforma de Acompanhamento de Estudantes com Necessidades Específicas (ENE) do IPLeiria, como Responsável dos Gestores de Caso. Se tiver alguma dúvida contacte o admnistrador da Plataforma.', $user->email, 'Atribuição de função na Plataforma 100%IN', 'Atribuição de função na Plataforma 100%IN');

                    return response()->json("Responsible Case Manager adicionado com sucesso!",200);
                }
            }
        }
    }

    public function getWebServiceConfigs(){
        $linkWSDadosAlunos = config("global.ws_dadosAluno_link");
        $fieldsWSDadosAlunos = config('global.ws_dadosAluno_fields');
        $fieldNamesWSDadosAlunos = config('global.ws_dadosAluno_fieldNames');

        $arr = array();

        for($i = 0; $i < sizeof($fieldsWSDadosAlunos); $i ++){
            $obj = new stdClass();
            $obj->name = $fieldNamesWSDadosAlunos[$i];
            $obj->jsonParameter =$fieldsWSDadosAlunos[$i];

            array_push($arr,$obj);
        }

        return response()->json([$linkWSDadosAlunos,$arr], 200);
    }

    public function setWebServiceConfigs(Request $request){
            $dados = $request->validate([
                'parameters' => '',
                'linkWebService' => '',
            ]);

        $fieldNames = array();
        $fields = array();

        foreach($dados['parameters'] as $param){
            array_push($fieldNames,$param['name']);
            array_push($fields,$param['jsonParameter']);
        }

        config(['global.ws_dadosAluno_link' => $dados['linkWebService']]);
        config(['global.ws_dadosAluno_fields' => $fields]);
        config(['global.ws_dadosAluno_fieldNames' => $fieldNames]);

        $fp = fopen(base_path() .'/config/global.php' , 'w');
        fwrite($fp, '<?php return ' . var_export(config('global'), true) . ';');
        fclose($fp);

        return response()->json(200);
    }

    public function addService(Request $request){
        $dados = $request->validate([
            'email' => 'required',
            'service' => 'required',
        ]);

         $service = User::where('email',$dados['email'])->first();

        if($service !== null){
            if($user->type===$dados['service']){
                return response()->json(409);
            }else{
                return response()->json(418);
            }
        }

        $users = \Adldap\Laravel\Facades\Adldap::search()->find($dados['email']);

//         \Debugbar::info($users);

        $user = null;

        $user = new User();

        $user->email = $users->mail[0];
        $user->name = $users->displayname[0];
        $user->type = $dados['service'];
        $user->course = $users->description[0];
        $user->school = $users->company[0];
        $user->number = $users->mailnickname[0];
        $user->departmentNumber = $users->departmentnumber[0];
        $user->firstLogin = 1;
        $user->save();

        $admin = User::where('type','Administrador')->first();

        if($admin!=null){
//           EmailController::sendEmail('Foi adicionado à Plataforma de Acompanhamento de Estudantes com Necessidades Específicas (ENE) do IPLeiria, como um dos seus Serviços de apoio. Se tiver alguma dúvida contacte o admnistrador da Plataforma através do email '.$admin->email.'.', $user->email, 'Atribuição de função na Plataforma 100%IN', 'Atribuição de função na Plataforma 100%IN');
        }else{
//           EmailController::sendEmail('Foi adicionado à Plataforma de Acompanhamento de Estudantes com Necessidades Específicas (ENE) do IPLeiria, como um dos seus Serviços de apoio. Se tiver alguma dúvida contacte o admnistrador da Plataforma.', $user->email, 'Atribuição de função na Plataforma 100%IN', 'Atribuição de função na Plataforma 100%IN');
        }



        return response()->json(200);
    }
}
