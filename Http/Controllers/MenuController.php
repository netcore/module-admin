<?php

namespace Modules\Admin\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;
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
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $menusGrouped = Menu::with('items')->orderBy('type', 'desc')->get()->groupBy('type');

        return view('admin::menu.index', compact('menusGrouped'));
    }

    /**
     * Show the specified resource.
     *
     * @param Menu $menu
     * @return \Illuminate\View\View
     */
    public function show(Menu $menu): View
    {
        $languages = TransHelper::getAllLanguages();
        $items = $menu->items()->defaultOrder()->get()->toTree();

        $routes = [];

        foreach (Route::getRoutes() as $route) {
            if (in_array('GET', $route->methods())) {
                if ($route->getName()) {
                    $routes[$route->getName()] = [
                        'name'       => $route->getName(),
                        'parameters' => $route->parameterNames(),
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
                'html' => '<i class="fa ' . $value . '"></i> ' . $value,
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
                    'text' => $title,
                ];
            });
        }

        return view('admin::menu.show', compact('menu', 'items', 'routes', 'icons', 'pages', 'languages'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Menu $menu
     * @return \Illuminate\View\View
     */
    public function edit(Menu $menu): View
    {
        return view('admin::menu.edit', compact('menu'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Modules\Admin\Http\Requests\Admin\MenuRequest $request
     * @param \Modules\Admin\Models\Menu $menu
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(MenuRequest $request, Menu $menu): RedirectResponse
    {
        $menu->updateTranslations(
            $request->get('translations', [])
        );

        $this->clearCache();

        return back()->withSuccess('Menu successfully saved!');
    }

    /**
     * Save tree order.
     *
     * @param Request $request
     * @param int $menuId
     * @return void
     */
    public function saveOrder(Request $request, int $menuId): void
    {
        $order = $request->get('order', []);

        $newArray = json_decode($order, true);

        if (is_array($newArray)) {
            MenuItem::scoped(['menu_id' => $menuId])->rebuildTree($newArray);
        }

        $this->clearCache();
    }

    /**
     * @param SaveMenuItemRequest $request
     * @param Menu $menu
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveMenuItem(SaveMenuItemRequest $request, Menu $menu): JsonResponse
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

        $this->clearCache();

        return response()->json([
            'status' => 'success',
            'item'   => MenuItem::find($menuItem->id)
            // TODO hack, but currently works, someone please remind me to fix this
        ]);
    }

    /**
     * Delete menu item.
     *
     * @param Request $request
     * @param Menu $menu
     * @param int $itemId
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteMenuItem(Request $request, Menu $menu, $itemId): JsonResponse
    {
        if (!$menuItem = $menu->items()->find($itemId)) {
            return response()->json([
                'status' => 'error',
            ]);
        }

        $menuItem->delete();
        $this->clearCache();

        return response()->json([
            'status' => 'success',
        ]);
    }

    /**
     * Clear cached menus.
     *
     * @return void
     */
    protected function clearCache(): void
    {
        try {
            cache()->forget(Menu::$cacheKey);
        } catch (Exception $e) {
            logger()->critical('Unable to clear cached menus: ' . $e->getMessage());
        }
    }
}
