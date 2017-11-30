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
        $google_analytics_client_id = setting()->get('google_analytics_client_id');

        return view('admin::index', compact('google_analytics_client_id'));
    }
}
