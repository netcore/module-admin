<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{

    protected $table = 'netcore_admin__menus';

    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(MenuItem::class);
    }

    /**
     * @return array
     */
    public function getItemTree()
    {
        $tree = function ($items) use (&$tree) {
            $menuItems = [];

            /*
             * About menu 'active' class resolver
             * https://www.hieule.info/products/laravel-active-version-3-released
             */

            foreach ($items as $item) {
                //@TODO: submenu item resolvers for active class

                $menuItems[] = [
                    'id'       => $item->id,
                    'name'     => $item->name,
                    'icon'     => $item->icon,
                    'type'     => $item->type,
                    'target'   => $item->target,
                    'url'      => $item->url,
                    'active'   => $item->active,
                    'module'   => $item->module,
                    'children' => $item->children->count() ? $tree($item->children) : []
                ];
            }

            return $menuItems;
        };

        $itemTree = [];
        if ($items = $this->items()->active()->where('is_active', 1)->defaultOrder()->get()) {
            $itemTree = $tree(
                $items->toTree()
            );
        }

        return $itemTree;
    }
}
