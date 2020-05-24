<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\CaseManager;
use App\Substitution;
use App\User;
use Carbon\Carbon;
use App\History;
use Barryvdh\Debugbar\Facade as Debugbar;

class endSubstitution extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'substitutions:end';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It will end substitutions on deadline';

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
         $subs = Substitution::where('endDate','=',Carbon::today())->get();
        error_log(Carbon::today());

        if(!$subs->isEmpty()){
            foreach($subs as $sub){
              $cms = CaseManager::where('studentEmail','=', $sub->emailStudent)->get();

                foreach($cms as $cm){
                    $cm->caseManagerEmail = $sub->emailMainCM;
                    $cm->caseManagerName = $sub->nameMainCM;
                    $cm->emailMainCaseManager = null;
                    $cm->save();

                     $history = new History();
                     $history->studentEmail = $cm->studentEmail;
                     $history->description = 'Terminou o periodo de substituição do Gestor de Caso ' .$cm->caseManagerName.' pelo '.$sub->nameSubstituteCM.' para o aluno '.$cm->studentName;
                     $history->date = Carbon::now();
                     $history->save();

                     error_log("Foi terminada uma substituição!");
                }
            }


        } else {
            error_log("Não existem substituições a decorrer");
        }

    }
}
