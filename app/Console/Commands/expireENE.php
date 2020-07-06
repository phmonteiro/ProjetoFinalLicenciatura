<?php

namespace App\Console\Commands;

use App\CaseManager;
use Illuminate\Console\Command;
use App\User;
use Illuminate\Support\Facades\DB;
use App\Teacher;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\EmailController;
use App\Nee;
use App\Student_Supports;
use App\Tutor;
use Illuminate\Support\HtmlString;


class expireENE extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expire:ene';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire ENE status and remove its\' associations.';

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

        $enes = User::where('type', '=', 'Estudante')->where('enee', '=', 'approved')->whereNotNull('eneeExpirationDate')->get();

        /*
        $eneBySubjects =  array();
        $eneBySubjects = array();
        $subjects = array();
        */

        $todayDate = Carbon::now()->format('Y-m-d');

        foreach ($enes as $ene) {


            if ($ene->eneeExpirationDate == $todayDate) {
                // get all ENEs that expire today
                $ene->eneeExpirationDate = null;
                $ene->enee = "expired";
                $ene->save();

                // get all Supports attributed to currenwt ENE and delete them
                $studentSupports = Student_Supports::where('email', '=', $ene->email);
                foreach ($studentSupports as $studentSupport) {
                    $studentSupport->delete();
                }

                // get ene's tutor and delete the association
                $eneTutor = Tutor::where('studentEmail', '=', $ene->email);
                $eneTutor->delete();

                // get ene's CM and delete the association
                $eneCm = CaseManager::where('studentEmail', '=', $ene->email);
                $eneCm->delete();

                // get all NEE attributed to current ENE and and delete them
                $nees = Nee::where('studentEmail', '=', $ene->email);
                foreach ($nees as $nee) {
                    $nee->delete();
                }
            }
        }
    }
}
