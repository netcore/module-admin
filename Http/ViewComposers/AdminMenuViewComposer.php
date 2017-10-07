<?php
namespace Modules\Admin\Http\ViewComposers;
use Illuminate\View\View;
class AdminMenuViewComposer{

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $leftAdminMenu = null;


        $menu = \Modules\Admin\Models\Menu::whereName('leftAdminMenu')->first();

        /*
         * @TODO: noteikti ir labāks veids kā buildot menu tree
         * @TODO: varbūt jātaisa json un vuejs?
         *
         * About menu 'active' class resolver
         * https://www.hieule.info/products/laravel-active-version-3-released
         */
        $pixelAdminMenu = function ($items) use (&$pixelAdminMenu) {
            $menuItems = [];


            foreach ($items as $item) {
                //@TODO: submenu item resolvers for active class

                $menuItems[] = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'icon' => $item->icon,
                    'type' => $item->type,
                    'target' => $item->target,
                    'url' => $item->url,
                    'active' => $item->active,
                    'children' => $item->children->count() ? $pixelAdminMenu($item->children) : []
                ];
            }

            return $menuItems;
        };

        if( $items = $menu->items()->where('is_active', 1)->defaultOrder()->get() ){
            $leftAdminMenu = $pixelAdminMenu(
                $items->toTree()
            );
        }

        $view->with('leftAdminMenu', collect($leftAdminMenu));
    }
}
