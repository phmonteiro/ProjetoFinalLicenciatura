<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;

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
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // if (Auth::user()->loginExpirationDate != null && Carbon::now()->gte(Auth::user()->loginExpirationDate)) {
            //     auth()->logout();
            //     return response()->json(['message' => 'Login expirado. Contacte o administrador.'], 419);
            // }

            $user = Auth::user();
            if ($user->firstLogin == 1) {
                return Auth::user();
            } else {
                $users = \Adldap\Laravel\Facades\Adldap::search()->find($request->email);
                $user->type = $users->title[0];
                $user->course = $users->description[0];
                $user->school = $users->company[0];
                $user->number = $users->mailnickname[0];
                $user->firstLogin = 1;
                $user->save();
                return Auth::user();
            }
        } else {
            auth()->logout();
            return response()->json(['message' => 'Credenciais inválidas. Por favor tente novamente.'], 401);
        }
    }
}
