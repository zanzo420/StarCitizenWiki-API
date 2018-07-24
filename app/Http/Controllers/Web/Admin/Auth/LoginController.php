<?php declare(strict_types = 1);

namespace App\Http\Controllers\Web\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\StarCitizenWiki\Interfaces\AuthRepositoryInterface;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use Hesto\MultiAuth\Traits\LogsoutGuard;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class LoginController
 */
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

    use AuthenticatesUsers, LogsoutGuard {
        LogsoutGuard::logout insteadof AuthenticatesUsers;
    }

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    public $redirectTo = '/admin/dashboard';

    private $backendError = false;
    /** @var  AuthRepositoryInterface */
    private $authRepository;

    /**
     * Create a new controller instance.
     *
     * @param \App\Repositories\StarCitizenWiki\Interfaces\AuthRepositoryInterface $authRepository
     */
    public function __construct(AuthRepositoryInterface $authRepository)
    {
        parent::__construct();
        $this->middleware('admin.guest', ['except' => 'logout']);

        $this->authRepository = $authRepository;
    }

    /**
     * Get the path that we should redirect once logged out.
     * Adaptable to user needs.
     *
     * @return string
     */
    public function logoutToPath()
    {
        return '/admin/login';
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('admin');
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        try {
            $passwordValid = $this->authRepository->authenticateUsingCredentials(
                $request->get($this->username()),
                $request->get('password')
            );
        } catch (ConnectException | RequestException $e) {
            $this->backendError = true;

            return false;
        }

        if ($passwordValid) {
            return $this->guard()->attempt(
                [
                    'username' => $request->get('username'),
                    'password' => config('api.admin_password'),
                ],
                false
            );
        }

        return false;
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        if ($this->backendError) {
            $errors = ['Backend Error'];
        } else {
            $errors = [$this->username() => trans('auth.failed')];
        }

        return redirect()->route('web.admin.auth.login')->withInput($request->only($this->username()))->withErrors($errors);
    }
}