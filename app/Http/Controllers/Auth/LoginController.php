<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
class LoginController extends Controller {

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/ac/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest')->except('logout');
    }
    
    /**
     * @return view
     */
    public function index() {
        return $this->showLoginForm();
    }
    
    /**
     * {@inheritdoc}
     */
    protected function guard() {
        return Auth::guard('web');
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request) {
        $credentials           = $request->only($this->username(), 'password');
        $credentials['status'] = 'A';

        return $credentials;
    }

    public function username() {
        return 'login';
    }

    protected function attemptLogin(Request $request) {
        $usuario = DB::select("
            SELECT *
              FROM usuarios
             WHERE TRUE
               AND login = '" . request()->get('login') . "'
               AND usuario_tipo_id IN(0, 3)
        ");

        if(is_array($usuario) && count($usuario) == 0) {
            return false;
        }

        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }
    
}