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
            $tree = '';

            foreach ($items as $item) {
                $active = '';
                $url    = 'javascript:;';

                if( $item->type == 'route' ){
                    $active = (active_class(if_route($item->value)));
                    $url    = route($item->value);
                }

                //@TODO: submenu item resolvers for active class
                $tree .= '<li class="px-nav-item' . ($item->children->count() ? ' px-nav-dropdown' : '') . ' ' . $active . '">';

                    $tree .= '<a href="' . $url . '">';

                        if( $item->icon ){
                            $tree .= '<i class="px-nav-icon ' . $item->icon . '"></i>';
                        }

                        $tree .= '<span class="px-nav-label">' . $item->name . '</span>';

                    $tree .= '</a>';

                    //submenus
                    if( $item->children->count() ){
                        $tree .= '<ul class="px-nav-dropdown-menu">';
                        $tree .= $pixelAdminMenu($item->children);
                        $tree .= '</ul>';
                    }


                $tree .= '</li>';

            }

            $tree .= '';

            return $tree;
        };

        if( $items = $menu->items()->defaultOrder()->get() ){
            $leftAdminMenu = $pixelAdminMenu(
                $items->toTree()
            );
        }

        $view->with('leftAdminMenu', $leftAdminMenu);
    }
}
