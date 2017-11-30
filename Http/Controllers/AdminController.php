<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $google_analytics_client_id = setting()->get('google-analytics-client-id');

        return view('admin::index', compact('google_analytics_client_id'));
    }

    /**
     * @return mixed
     */
    public function denied()
    {
        $user = auth()->user();
        $routes = [];

        if(\Nwidart\Modules\Facades\Module::find('Permission')) {
            $levels = $user->role->levels;
            foreach ($levels as $level) {
                foreach ($level->routes as $route) {
                    $routes[] = $route;

                }
            }
        }


        return view('admin::access-denied', compact('routes'));
    }
}
