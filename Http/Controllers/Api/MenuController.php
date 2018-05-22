<?php

namespace Modules\Admin\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Modules\Admin\Models\Menu;

class MenuController extends Controller
{
    /**
     * @param null $locale
     * @return mixed
     */
    public function getMenus($locale = null)
    {
        if (!$locale) {
            $locale = app()->getLocale();
        }

        try {
            $menus = Menu::with([
                'items' => function ($q) {
                    return $q->where('is_active', 1);
                }
            ])->where('type', 'public')->get()->map(function ($menu) use ($locale) {
                return $menu->formatResponse($locale);
            });

            return response()->json([
                'success' => true,
                'data'    => $menus
            ]);
        } catch (\Exception $e) {
            logger()->error($e);

            return response()->json([
                'success' => false,
                'data'    => []
            ]);
        }
    }

    /**
     * @param null $key
     * @param null $locale
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMenu($key = null, $locale = null)
    {
        if (!$locale) {
            $locale = app()->getLocale();
        }

        try {
            $menu = Menu::with([
                'items' => function ($q) {
                    return $q->where('is_active', 1);
                }
            ])->where('type', 'public')->where('key', $key)->first();

            if (!$menu) {
                return response()->json([
                    'success' => false,
                    'data'    => []
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data'    => $menu->formatResponse($locale)
            ]);
        } catch (\Exception $e) {
            logger()->error($e);

            return response()->json([
                'success' => false,
                'data'    => []
            ]);
        }
    }
}
