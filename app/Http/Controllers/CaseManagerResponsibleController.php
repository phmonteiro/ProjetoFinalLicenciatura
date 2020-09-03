<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\CaseManagerResponsibleResource;
use App\CaseManager;
use App\Substitution;
use App\User;
use App\Http\Resources\UserResource;
use App\Http\Resources\SubstitutionResource;
use Carbon\Carbon;
use App\History;
use Barryvdh\Debugbar\Facade as Debugbar;
use Auth;



class CaseManagerResponsibleController extends Controller
{
    public function index()
    {
        return CaseManagerResponsibleResource::collection(CaseManager::Orderby('studentName')->paginate(10));
    }

    public function getAllCMs(Request $request){
        $cms = CaseManagerResponsibleResource::collection(User::where('type','CaseManager')->whereNull('inactive')->get());

       return response()->json($cms, 200);
    }

    public function getAllCMsActivateDeactivate(Request $request){
        $cms = CaseManagerResponsibleResource::collection(User::where('type','CaseManager')->get());

       return response()->json($cms, 200);
    }

    public function getStudents()
    {
        $enesWithCM = CaseManager::groupBy('studentEmail')->pluck('studentEmail')->toArray();

        $students = User::whereIn('email', $enesWithCM)->where('enee', 'approved')->paginate(10);

        return response()->json(new UserResource($students), 200);
    }

    public function getStudentCMs($email)
    {
        return CaseManagerResponsibleResource::collection(CaseManager::Where('studentEmail', $email)->paginate(10));
    }

    public function setCmSubstitute(Request $request){

        $cms = CaseManager::where('caseManagerEmail',$request->emailCurrentCaseManager)->get();

    // se o inicio da substituição for hoje, substitui de imediato
        if($request->startDate == Carbon::today()){
         foreach($cms as $cm){

               $cm->caseManagerEmail = $request->emailSubstituteCaseManager;
               $cm->caseManagerName = $request->nameSubstituteCaseManager;

               $history = new History();

               if($request->substitutionType==="temporary"){
                    $cm->emailMainCaseManager = $request->emailCurrentCaseManager;
                    $history->description = 'Foi definido o Gestor de Caso ' .$cm->caseManagerName.' como substituto temporário para o aluno '.$cm->studentName;
               }else{
                    $cm->emailMainCaseManager = null;
                    $history->description = 'Foi definido o Gestor de Caso ' .$cm->caseManagerName.' como substituto permanente para o aluno '.$cm->studentName;
               }

               $history->studentEmail = $cm->studentEmail;
               $history->date = Carbon::now();
               $history->save();
               $cm->save();

                error_log("Foi efetuada uma substituição!");

             }

        }


        foreach ($cms as $cm)
        {
            $substitution = new Substitution();
            $substitution->emailMainCM = $request->emailCurrentCaseManager;
            $substitution->nameMainCM = $request->nameCurrentCaseManager;
            $substitution->emailSubstituteCM = $request->emailSubstituteCaseManager;
            $substitution->nameSubstituteCM = $request->nameSubstituteCaseManager;
            $substitution->emailStudent = $cm->studentEmail;
            $substitution->nameStudent = $cm->studentName;
            $substitution->type = $request->substitutionType;

            if($substitution->type==="temporary"){
                    $substitution->startDate = $request->startDate;
                    $substitution->endDate = $request->endDate;
            }else{
                    $substitution->startDate = $request->startDate;
            }

            $substitution->save();
        }

        $responsible = User::where('type','CaseManagerResponsible')->first();
        $mainCM = User::where('email',$request->emailCurrentCaseManager)->first();
        $substituteCM = User::where('email',$request->emailSubstituteCaseManager)->first();

        //mandar email a avisar
        if($request->substitutionType==="temporary"){
//             EmailController::sendEmail('Foi definido como Substituto para o Gestor de Caso '.$mainCM->name.'. Substituição temporária de '.$request->startDate.' a '.$request->endDate.'. Qualquer dúvida pode contactar o Responsável dos Gestores de Caso através do email '.$responsible->email.'.  Obrigado', $substituteCM->email, '[100%IN] Substituição efetuada', '[100%IN] Substituição efetuada');

//             EmailController::sendEmail('Foi substituído pelo Gestor de Caso '.$substituteCM->name.'. Substituição temporária de '.$request->startDate.' a '.$request->endDate.'. Qualquer dúvida pode contactar o Responsável dos Gestores de Caso através do email '.$responsible->email.'. Obrigado', $mainCM->email, '[100%IN] Substituição efetuada', '[100%IN] Substituição efetuada');
        }else{
//             EmailController::sendEmail('Foi definido como Substituto para o Gestor de Caso '.$mainCM->name.'. Substituição permanente com início a '.$request->startDate.'. Qualquer dúvida pode contactar o Responsável dos Gestores de Caso através do email '.$responsible->email.'.  Obrigado', $substituteCM->email, '[100%IN] Substituição efetuada', '[100%IN] Substituição efetuada');

//             EmailController::sendEmail('Foi substituído pelo Gestor de Caso '.$substituteCM->name.'. Substituição permanente com início a '.$request->startDate.'. Qualquer dúvida pode contactar o Responsável dos Gestores de Caso através do email '.$responsible->email.'. Obrigado', $mainCM->email, '[100%IN] Substituição efetuada', '[100%IN] Substituição efetuada');
        }



        return response()->json(200);
    }

    public function removeCM($id)
    {
        $user = CaseManager::findOrFail($id);
        $user->delete();

        $student = User::where('email', $user->studentEmail)->first();
        $history = new History();
        $history->studentEmail = $student->email;
        $history->description = "Foi removido o gestor de caso ao aluno";
        $history->date = Carbon::now();
        $history->save();

        $responsible = User::where('type','CaseManagerResponsible')->first();

//         EmailController::sendEmail('Foi removido o Gestor de Caso ' . $user->caseManagerName . '. Para mais informações entre em contacto com o Responsável dos Gestores de Caso através do email '.$responsible->email.'. Obrigado.', $student->email, '[100%IN] Gestor de Caso removido', '[100%IN] Gestor de Caso removido');

//         EmailController::sendEmail('Foi removido como Gestor de Caso do aluno ' . $user->studentName . '. Para mais informações entre em contacto com o Responsável dos Gestores de Caso através do email '.$responsible->email.'. Obrigado.', $user->caseManagerEmail, '[100%IN] Gestor de Caso removido', '[100%IN] Gestor de Caso removido');


        return response()->json($user, 200);
    }

    public function addCM(Request $request){

        $user = User::where('email',$request->cmEmail)->first();

        if($user !== null){
            if($user->type==='CaseManager'){
                return response()->json(409);
            }else{
                return response()->json(418);
            }
        }

        $users = \Adldap\Laravel\Facades\Adldap::search()->find($request->cmEmail);

        \Debugbar::info($users);

        $user = null;

        $user = new User();

        $user->email = $users->mail[0];
        $user->name = $users->displayname[0];
        $user->type = 'CaseManager';
        $user->course = $users->description[0];
        $user->school = $users->company[0];
        $user->number = $users->mailnickname[0];
        $user->departmentNumber = $users->departmentnumber[0];
        $user->firstLogin = 1;
        $user->save();

        $responsible = User::where('type','CaseManagerResponsible')->first();

//         EmailController::sendEmail('Foi adicionado à plataforma 100%IN como Gestor de Caso. Pode aceder à Plataforma através da seguinte ligação: http://100in.dei.estg.ipleiria.pt. Para mais informações entre em contacto com o Responsável dos Gestores de Caso através do email '.$responsible->email.'. Obrigado.', $user->email, '[100%IN] Adicionado à Plataforma como Gestor de Caso', '[100%IN] Adicionado à Plataforma como Gestor de Caso');

        return response()->json(200);
    }

        public function cancelSubstitution(Request $request){

                $mainCaseManager = User::where('email',$request->emailMainCaseManager)->first();

                $cms = CaseManager::where('emailMainCaseManager',$request->emailMainCaseManager)->get();

                $substituteName = $cms[0]->caseManagerName;
                $substituteEmail = $cms[0]->caseManagerEmail;

                foreach ($cms as $cm)
                {
                    $cm->caseManagerEmail= $mainCaseManager->email;
                    $cm->caseManagerName= $mainCaseManager->name;
                    $cm->emailMainCaseManager = null;
                    $cm->save();

                     $history = new History();
                     $history->studentEmail = $cm->studentEmail;
                     $history->description = 'Terminou o periodo de substituição do Gestor de Caso ' .$cm->caseManagerName.' pelo '.$substituteName.' voltou como substituto para o aluno '.$cm->studentName;
                     $history->date = Carbon::now();
                     $history->save();
                 }

//                 EmailController::sendEmailWithCC('A substituição do Gestor de Caso '.$substituteName.' pelo Gestor de Caso '.$mainCaseManager->name.' foi terminada. Obrigado', $mainCaseManager->email, '[100%IN] Substituição terminada', '[100%IN] Substituição terminada',  $substituteEmail);

            return response()->json(200);
        }

    public function setCM(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $dados = $request->validate([
            'cmEmail' => 'required|email',
            'studentName' => 'required',
            'cmName' => 'required',
        ]);

        $caseManager = new CaseManager();
        $caseManager->studentEmail = $user->email;
        $caseManager->studentName = $dados['studentName'];
        $caseManager->caseManagerEmail = $dados['cmEmail'];
        $caseManager->caseManagerName = $dados['cmName'];

        $history = new History();
        $history->studentEmail = $user->email;
        $history->description = "Foi atribuido um gestor de caso ao aluno";
        $history->date = Carbon::now();
        $history->save();

        $caseManager->save();

//         EmailController::sendEmail('Foi adicionado o Gestor de Caso ' .  $caseManager->caseManagerName . '. Obrigado', $caseManager->studentEmail, '[100%IN] Gestor de Caso adicionado', '[100%IN] Gestor de Caso adicionado');

//         EmailController::sendEmail('Foi definido como Gestor de Caso do aluno ' .  $caseManager->studentName . '. Obrigado', $caseManager->caseManagerEmail, '[100%IN] Gestor de Caso', '[100%IN] Gestor de Caso');

        return response()->json(new CaseManagerResponsibleResource($caseManager), 200);
    }

        public function getActiveSubstitutions(){

            $cms = CaseManager::whereNotNull('emailMainCaseManager')->select('caseManagerEmail','caseManagerName','emailMainCaseManager')->distinct()->get();

            foreach ($cms as $cm) {
                $user = User::where('email',$cm->emailMainCaseManager)->first();

                $cm['mainCaseManagerName'] = $user->name;
            }

            return response()->json($cms,200);
        }

        public function substitutionsHistory(){

                    return SubstitutionResource::collection(Substitution::Orderby('startDate')->paginate(10));
        }

        public function getEneWithoutCaseManager(){

            $studentEmailsWithCaseManager = CaseManager::select('studentEmail')->get()->toArray();

            $studentsEmail = User::where('type','=','Estudante')->where('enee','=','approved')->whereNotIn('email',$studentEmailsWithCaseManager)->paginate(10);

            return response()->json($studentsEmail,200);
        }

        public function activateCaseManager(Request $request, $email){
            $cm = User::where('email',$email)->first();
            $cm->inactive = null;
            $cm->save();

//             EmailController::sendEmail('A sua conta foi ativada na plataforma 100%IN. Obrigado', $cm->email, '[100%IN] Conta ativada', '[100%IN] Conta ativada');

            return response()->json(200);
        }

        public function deactivateCaseManager(Request $request, $email){
            $cm = User::where('email',$email)->first();
            $cm->inactive = 1;
            $cm->save();

//             EmailController::sendEmail('A sua conta foi desativada na plataforma 100%IN. Obrigado', $cm->email, '[100%IN] Conta desativada', '[100%IN] Conta desativada');

            return response()->json(200);
        }

        public function getCMWithENE(){
            $cmEmails = CaseManager::all()->pluck('caseManagerEmail')->unique('caseManagerEmail');

            return response()->json($cmEmails,200);
        }
}
