<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
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

    // public function authenticate(Request $request) {
    //     return true;
    // }
}