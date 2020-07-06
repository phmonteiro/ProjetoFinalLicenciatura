<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use Mail;
use App\User;
use App\Nee;
use GuzzleHttp\Client;
use Carbon\Carbon;
use App\Http\Controllers\EmailController;
use Barryvdh\Debugbar\Facade as Debugbar;

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

                $client = new \GuzzleHttp\Client();
                $response = $client->request("POST", 'http://www.dei.estg.ipleiria.pt/intranet/horarios/ws/inscricoes/webserviceDadosAlunos.php', [
                'form_params' => [
                     'login' => $numAluno,
                     'password' => $request->password
                ]]);

                $jsonfied = $response->getBody()->getContents();
                $parts = explode("[",$jsonfied);
                $jsonfied = substr_replace($parts[1] ,"",-1);
                $webServiceUserInfo = json_decode($jsonfied,true);


                $user->residence = $webServiceUserInfo['DS_MORADA'];
                $user->zipCode = $webServiceUserInfo['CD_POSTAL'].'-'.$webServiceUserInfo['CD_SUBPOS'];
                $user->phoneNumber = $webServiceUserInfo['NR_TELEMOV'];
                $user->birthDate = $webServiceUserInfo['DT_NASCIME']['date'];
                $user->nif = $webServiceUserInfo['NR_CONTRIB'];
                $user->area = $webServiceUserInfo['DISTRITO'];

                if(Carbon::parse($user->eneeExpirationDate)->isPast()){
                    $user->enee = "expired";
                    $user->save();
                }

                $user->save();

                $mostRecentAccountId = User::where('nif','=',$webServiceUserInfo['NR_CONTRIB'])->whereNull('inactive')->max('id');
                $allUserAccountIds = User::where('nif','=',$webServiceUserInfo['NR_CONTRIB'])->whereNull('inactive')->pluck('id')->toArray();
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
