<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use Mail;
use App\User;
use App\Coordinator;
use App\Nee;
use GuzzleHttp\Client;
use Carbon\Carbon;
use App\Http\Controllers\EmailController;
use Barryvdh\Debugbar\Facade as Debugbar;
use Config;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('getAuthUser', 'logout');
    }


    public function login(Request $request)
    {
//     dd($request);
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            $user = Auth::user();
            if ($user->firstLogin == 1) {
                if($user->type == "Estudante" && $user->eneeExpirationDate!=null){
                    if(Carbon::parse($user->eneeExpirationDate)->isPast()){
                        $nees = Nee::where('studentEmail','=',$user->email)->get();
                        foreach($nees as $nee){
                            $nee->delete();
                        }
                        $user->eneeExpirationDate=null;
                        $user->enee = "expired";
                        $user->save();
                    }
                }

                if($user->transferAccountStatus == "transferring"){
                    $token = $user->createToken(rand())->accessToken;
                    return response()->json(['user' => Auth::user()], 226)->header('Authorization', $token);
                }

                $token = $user->createToken(rand())->accessToken;
                return response()->json(['user' => Auth::user()], 200)->header('Authorization', $token);
            } else {


                $users = \Adldap\Laravel\Facades\Adldap::search()->find($request->email);
                $user->type = $users->title[0];
                $user->course = $users->description[0];
                $user->school = $users->company[0];
                $user->number = $users->mailnickname[0];
                $user->departmentNumber = $users->departmentnumber[0];


                $user->firstLogin = 1;

                $parts = explode("@",$request->email);
                $numAluno = $parts[0];
//                 'http://www.dei.estg.ipleiria.pt/intranet/horarios/ws/inscricoes/webserviceDadosAlunos.php'
                $client = new \GuzzleHttp\Client();
                $response = $client->request("POST", config("global.ws_dadosAluno_link"), [
                'form_params' => [
                     'login' => $numAluno,
                     'password' => $request->password
                ]]);

                $jsonfied = $response->getBody()->getContents();
                $parts = explode("[",$jsonfied);
                $jsonfied = substr_replace($parts[1] ,"",-1);
                $webServiceUserInfo = json_decode($jsonfied,true);

                $fields = config('global.ws_dadosAluno_fields');

                $user->residence = $webServiceUserInfo[$fields[0]];
                $user->zipCode = $webServiceUserInfo[$fields[1]].'-'.$webServiceUserInfo[$fields[2]];
                $user->phoneNumber = $webServiceUserInfo[$fields[3]];
                $user->birthDate = $webServiceUserInfo[$fields[4]]['date'];
                $user->nif = $webServiceUserInfo[$fields[5]];
                $user->area = $webServiceUserInfo[$fields[6]];

//                 $user->residence = $webServiceUserInfo['DS_MORADA'];
//                 $user->zipCode = $webServiceUserInfo['CD_POSTAL'].'-'.$webServiceUserInfo['CD_SUBPOS'];
//                 $user->phoneNumber = $webServiceUserInfo['NR_TELEMOV'];
//                 $user->birthDate = $webServiceUserInfo['DT_NASCIME']['date'];
//                 $user->nif = $webServiceUserInfo['NR_CONTRIB'];
//                 $user->area = $webServiceUserInfo['DISTRITO'];

                    if($user->eneeExpirationDate!=null && Carbon::parse($user->eneeExpirationDate)->isPast()){
                        $user->enee = "expired";
                        $user->save();
                    }

                $user->save();

                $mostRecentAccountId = User::where('nif','=',$user->nif)->whereNull('inactive')->max('id');
                $allUserAccountIds = User::where('nif','=',$user->nif)->whereNull('inactive')->pluck('id')->toArray();
                \Debugbar::info($allUserAccountIds);
                \Debugbar::info($mostRecentAccountId);

                $inactiveAccountIds = array_diff($allUserAccountIds,array($mostRecentAccountId));


                \Debugbar::info($inactiveAccountIds);

               foreach($inactiveAccountIds as $accountId){
                   $account = User::where('id','=',$accountId)->first();
                   $account->inactive = 1;
                   $account->save();
               }

                if($inactiveAccountIds){
                    $user->transferAccountStatus = "transferring";
                    $user->save();

                    $token = $user->createToken(rand())->accessToken;
                    return response()->json(['user' => Auth::user()], 226)->header('Authorization', $token);
                }

                //Creating Coordinator...
                $coordinator = Coordinator::where('departmentNumber',$user->departmentNumber)->first();

                if($coordinator==null){
                    $coordinator = new Coordinator();
                    $coordinator->departmentNumber = $user->departmentNumber;
                    $coordinator->course = $user->course;
                    $coordinator->school = $user->school;
                    $coordinator->save();
                }

                $token = $user->createToken(rand())->accessToken;
                return response()->json(['user' => Auth::user()], 200)->header('Authorization', $token);
            }
        } else {
            auth()->logout();
            return response()->json(['message' => 'Credenciais inválidas. Por favor tente novamente.'], 401);
        }
    }

    public function getAuthUser()
    {
        return Auth::user();
    }

    public function getActiveEmail($email){
        $inactiveAccount = User::where('email','=',$email)->first();
        $activeAccount = User::where('nif','=',$inactiveAccount->nif)->whereNull('inactive')->first();

        return response()->json($activeAccount->email, 200);
    }
}
