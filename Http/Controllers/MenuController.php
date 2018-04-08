<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Requests\Admin\MenuRequest;
use Modules\Admin\Http\Requests\Admin\SaveMenuItemRequest;
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
        $menusGrouped = Menu::with('items')->orderBy('type', 'desc')->get()->groupBy('type');

        return view('admin::menu.index', compact('menusGrouped'));
    }

    /**
     * Show the specified resource.
     *
     * @param Menu $menu
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Menu $menu)
    {
        $languages = languages();
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
            $pages = Entry::currentRevision()->active()->get()->map(function ($entry) {
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
     * @param Menu $menu
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Menu $menu)
    {
        return view('admin::menu.edit', compact('menu'));
    }

    /**
     * Update the specified resource in storage.
     * @param MenuRequest $request
     * @param Menu $menu
     * @return
     */
    public function update(MenuRequest $request, Menu $menu)
    {
        $menu->updateTranslations($request->get('translations', []));

        return back()->withSuccess('Menu successfully saved!');
    }

    /**
     * @param Request $request
     * @param $menuId
     * @return void
     */
    public function saveOrder(Request $request, $menuId)
    {
        $order = $request->get('order', []);

        $newArray = json_decode($order, true);

        if (is_array($newArray)) {
            MenuItem::scoped([ 'menu_id' => $menuId ])->rebuildTree($newArray);
        }
    }

    /**
     * @param SaveMenuItemRequest $request
     * @param Menu                $menu
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveMenuItem(SaveMenuItemRequest $request, Menu $menu)
    {
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
            'item'   => MenuItem::find($menuItem->id)
            // TODO hack, but currently works, someone please remind me to fix this
        ]);
    }

    /**
     * @param Request $request
     * @param Menu    $menu
     * @param         $itemId
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteMenuItem(Request $request, Menu $menu, $itemId)
    {
        $menuItem = $menu->items()->find($itemId);
        if (!$menuItem) {
            return response()->json(['status' => 'error']);
        }

        $menuItem->delete();

        return response()->json(['status' => 'success']);
    }
}
