<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Illuminate\Support\Facades\DB;
use App\Teacher;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\EmailController;
use Illuminate\Support\HtmlString;


class notifyTeachersEachSemester extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:teachers-semester';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify teachers at the beginning of each semester of their ENE';

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

        $enes = User::where('type','=','Estudante')->where('enee','=','approved')->get();

        //$eneBySubjects =  array();
        $eneBySubjects = array();
        $subjects = array();


        foreach($enes as $ene){
            $client = new \GuzzleHttp\Client();
            $aux = str_split(Carbon::now()->year, 2);
            if (Carbon::now()->month >= 9 && Carbon::now()->month <= 12) {
                $yearLective = Carbon::now()->year + "" + (int) $aux[1] + 1;
            } else {
                $yearLective = $aux[0] + "" + (int) $aux[1] - 1 + ""  + $aux[1];
            }
            $response = $client->request("GET", 'http://www.dei.estg.ipleiria.pt/intranet/horarios/ws/inscricoes/inscricoes_cursos.php?anoletivo=' . $yearLective . '&curso=' . $ene->departmentNumber . '&estado=1&naluno=' . $ene->number . '');
            $aux = $response->getBody()->getContents();
            $response = explode(';', $aux);


            for ($i = 5; $i < sizeof($response); $i += 8) {
                $subject = trim(mb_convert_encoding($response[$i - 2], 'UTF-8', 'html-entities'));

            if(!array_key_exists(strval($subject), $eneBySubjects)){
                array_push($eneBySubjects,strval($subject));
                $eneBySubjects[strval($subject)]= array();
            }

            array_push($eneBySubjects[strval($subject)],$ene);

            array_push($subjects, $subject);

            }
        }

        $teachers = DB::table('teachers')->whereIn('subjectCode', $subjects)->get();

        foreach($teachers as $teacher){
            $subjectsOfCurrentProf = DB::table('teachers')->where('email','=',$teacher->email)->select('subjectCode','subject')->get();

            $msg =  "<pre>Caro Professor ".$teacher->name.", em continuação estão listados os ENEs (Estudantes com Necessidades Específicas) que neste Semestre vão frequentar unidades curriculares que você leciona. Tenha em atenção que eles podem ou não pertencer ao(s) seu(s) turno(s). \n";

            foreach($subjectsOfCurrentProf as $subject){

                if(array_key_exists(strval($subject->subjectCode), $eneBySubjects)){
                    $msg .= "<b>Unidade Curricular: ".$subject->subject."</b>\n";
                    $msg .= "<ul>";

                    foreach($eneBySubjects[$subject->subjectCode] as $ene){
                            $msg .= "\t<li>Nome: ".$ene->name."  Email: ".$ene->email."</li>\n";
                    }
                $msg .= "</ul>";
                }

                $msg .= "\n\n";
            }
            $msg .= "Atenciosamente,\nA equipa 100%IN</pre>";

//             EmailController::sendEmail(new HtmlString($msg), "recyclebinonline@gmail.com", 'Relatório Semestral de ENEs', 'Relatório Semestral de ENEs');
        }
        echo($msg);
    }
}
