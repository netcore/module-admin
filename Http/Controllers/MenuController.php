<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Models\Menu;
use Modules\Admin\Models\MenuItem;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $menus = Menu::all();

        return view('admin::menu.index', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin::menu.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     *
     * @return Response
     */
    public function show($id)
    {
        $menu = Menu::findOrFail($id);
        $items = $menu->items()->defaultOrder()->get()->toTree();

        return view('admin::menu.show', compact('menu', 'items'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit()
    {
        return view('admin::menu.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     */
    public function destroy()
    {
        //
    }

    /**
     * @param Request $request
     */
    public function saveOrder(Request $request)
    {
        $order = $request->get('order', null);

        $newArray = json_decode($order, true);

        if (is_array($newArray)) {
            MenuItem::rebuildTree($newArray);
        }
    }
}
