<?php

namespace Modules\Admin\Models;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Modules\Admin\Translations\MenuTranslation;
use Modules\Translate\Traits\SyncTranslations;

class Menu extends Model
{
    use Translatable, SyncTranslations;

    /**
     * Cache key.
     *
     * @var string
     */
    public static $cacheKey = 'menu::menus';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'netcore_admin__menus';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key',
    ];

    /**
     * Translation model class.
     *
     * @var string
     */
    public $translationModel = MenuTranslation::class;

    /**
     * Attributes that are translatable.
     *
     * @var array
     */
    public $translatedAttributes = [
        'name',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'translations',
    ];

    /** -------------------- Relations -------------------- */

    /**
     * Menu has many items.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class);
    }

    /** -------------------- Helper methods -------------------- */

    /**
     * Get menu items tree.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getItemTree(): Collection
    {
        $tree = function ($items) use (&$tree) {
            $menuItems = [];

            /*
             * About menu 'active' class resolver
             * https://www.hieule.info/products/laravel-active-version-3-released
             */
            foreach ($items as $item) {
                //@TODO: submenu item resolvers for active class

                $menuItems[] = (object)[
                    'id'       => $item->id,
                    'name'     => $item->name,
                    'icon'     => $item->icon,
                    'type'     => $item->type,
                    'target'   => $item->target,
                    'url'      => $item->url,
                    'active'   => $item->active,
                    'module'   => $item->module,
                    'children' => $item->children->count() ? $tree($item->children) : [],
                ];
            }

            return $menuItems;
        };

        $itemTree = [];

        if ($this->items->count()) {
            $itemTree = $tree($this->items->toTree());
        }

        return collect($itemTree);
    }

    /**
     * Get menu items flat tree.
     *
     * @return mixed
     */
    public function getFlattenItems()
    {
        return $this->items()->active()->where('is_active', 1)->defaultOrder()->get()->toFlatTree();
    }

    /**
     * Render menu.
     *
     * @param string|null $template
     * @param string|null $fullPath
     * @return string
     * @throws \Throwable
     */
    public function render($template = null, $fullPath = null)
    {
        if (!$fullPath) {
            $fullPath = 'templates/menu';
        }

        if (!$template) {
            $template = $this->key;
        }

        return view($fullPath . '.' . $template, [
            'items' => $this->getItemTree(),
            'menu'  => $this,
        ])->render();
    }

    /**
     * Format menu for response.
     *
     * @param $locale
     * @return array
     */
    public function formatResponse($locale): array
    {
        return [
            'id'    => $this->id,
            'key'   => $this->key,
            'name'  => optional($this->translateOrNew($locale))->name,
            'items' => $this->items->map(function ($item) use ($locale) {
                return $item->formatResponse($locale);
            }),
        ];
    }
}
