<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\CaseManager;
use App\Substitution;
use App\User;
use Carbon\Carbon;
use App\History;
use Barryvdh\Debugbar\Facade as Debugbar;


class activeSubstitutions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'substitutions:active';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It will activate all the scheduled Case Manager substitutions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
         $subs = Substitution::where('startDate','=',Carbon::today())->where('active','=','0')->get();

        if(!$subs->isEmpty()){
         foreach($subs as $sub){
           $cms = CaseManager::where('studentEmail','=', $sub->emailStudent)->get();

         foreach($cms as $cm){

                if($cm->caseManagerEmail === $sub->emailSubstituteCM){
                    continue;
                }

               $cm->caseManagerEmail= $sub->emailSubstituteCM;
               $cm->caseManagerName= $sub->nameSubstituteCM;

               $history = new History();

               if($sub->type==="temporary"){
                    $cm->emailMainCaseManager = $sub->emailMainCM;
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

                $sub->active = 1;
                $sub->save();

             }
            }
        }else{
            error_log("Não existem substituições agendadas para hoje.");
        }
    }
}
