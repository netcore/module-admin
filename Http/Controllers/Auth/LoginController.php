<?php

namespace Modules\Admin\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class LoginController extends Controller
{
    use AuthorizesRequests, ValidatesRequests, AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // TODO we should implement helper function
        // to check whether a module is available or not
        $contentModuleActivated = class_exists('\Modules\Content\Models\Entry');
        if($contentModuleActivated) {
            $this->redirectTo = '/admin/content';
        }

        $this->middleware('guest')->except('logout');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function showLoginForm()
    {
        return view('admin::auth.login');
    }
}
