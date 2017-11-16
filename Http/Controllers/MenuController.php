<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Requests\SaveMenuItemRequest;
use Modules\Admin\Models\Menu;
use Modules\Admin\Models\MenuItem;
use Modules\Content\Models\Entry;
use Netcore\Translator\Helpers\TransHelper;
use Nwidart\Modules\Facades\Module;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $menusGrouped = Menu::with('items')->get()->groupBy('type');

        return view('admin::menu.index', compact('menusGrouped'));
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
        $languages = TransHelper::getAllLanguages();
        $menu = Menu::findOrFail($id);
        $items = $menu->items()->defaultOrder()->get()->toTree();

        $routes = [];

        foreach (Route::getRoutes() as $route) {
            if (in_array('GET', $route->methods())) {
                if ($route->getName()) {
                    $routes[$route->getName()] = [
                        'name'       => $route->getName(),
                        'parameters' => $route->parameterNames()
                    ];
                }
            }
        }

        $routes = collect($routes);

        $icons = [];
        foreach (getFontAwesomeList() as $key => $value) {
            $icons[] = [
                'id'   => $value,
                'text' => $value,
                'html' => '<i class="fa ' . $value . '"></i> ' . $value
            ];
        }

        $icons = collect($icons);

        $pages = collect([]);
        if (Module::has('Content')) {
            $pages = Entry::whereIsActive(1)->get()->map(function ($entry) {
                $firstTranslation = $entry->translations->first();
                $title = $firstTranslation ? $firstTranslation->title : '';

                return [
                    'id'   => $entry->id,
                    'text' => $title
                ];
            });
        }

        return view('admin::menu.show', compact('menu', 'items', 'routes', 'icons', 'pages', 'languages'));
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
        $order = $request->get('order', []);

        $newArray = json_decode($order, true);

        if (is_array($newArray)) {
            MenuItem::rebuildTree($newArray);
        }
    }

    /**
     * @param Request $request
     */
    public function saveMenuItem(SaveMenuItemRequest $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $menuItem = MenuItem::find($request->get('id', 0));

        if (!$menuItem) {
            $menuItem = new MenuItem();
        }

        $type = $request->get('type');

        $module = '';
        if ($type == 'route') {
            $module = ucfirst(preg_replace('/(.+)\:\:(.+)\.(.+)/', '$2', $request->get('value', '')));
        }

        $menuItem->module = $module;
        $menuItem->icon = $request->get('icon', '');
        $menuItem->type = $type;
        $menuItem->target = $request->get('target', '_self');
        $menuItem->is_active = $request->get('is_active', 0);
        $menuItem->menu_id = $menu->id;
        $menuItem->save();

        $menuItem->updateTranslations($request->get('translations', []));

        return response()->json([
            'status' => 'success',
            'item'   => MenuItem::find($menuItem->id) // TODO hack, but currently works, someone please remind me to fix this
        ]);
    }

    /**
     * @param Request $request
     * @param         $id
     * @param         $itemId
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteMenuItem(Request $request, $id, $itemId)
    {
        $response = ['status' => 'error'];

        $menu = Menu::findOrFail($id);

        $menuItem = $menu->items()->where('id', $itemId)->first();
        if ($menuItem) {
            $menuItem->delete();

            $response = ['status' => 'success'];
        }

        return response()->json($response);
    }
}
