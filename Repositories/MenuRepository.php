<?php

namespace Modules\Admin\Repositories;

use Exception;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Modules\Admin\Models\Menu;
use Modules\Admin\Models\MenuItem;
use Netcore\Translator\Helpers\TransHelper;

class MenuRepository
{
    /**
     * Menu key.
     *
     * @var string
     */
    protected $key;

    /**
     * Cached menus collection.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $cachedMenus;

    /**
     * MenuRepository constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $loadWith = ['translations', 'items' => function(HasMany $hasMany) {
            return $hasMany->active()->with('translations')->defaultOrder();
        }];

        try {
            $this->cachedMenus = cache()->rememberForever(Menu::$cacheKey, function () use ($loadWith) {
                return Menu::with($loadWith)->get();
            });
        } catch (Exception $e) {
            $this->cachedMenus = Menu::with($loadWith)->get();
        }

        $this->key = '';
    }

    /**
     * Set the menu key.
     *
     * @param $key
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get the menu with given key.
     *
     * @param string|null $key
     * @return \Illuminate\Support\Collection|\Modules\Admin\Models\Menu
     */
    public function get($key = null)
    {
        if (!$key) {
            return $this->getAll();
        }

        return $this->cachedMenus->where('key', $key)->first();
    }

    /**
     * Get all menus.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAll(): Collection
    {
        return $this->cachedMenus;
    }

    /**
     * Render menu.
     *
     * @return string
     */
    public function render(): string
    {
        if ($this->key) {
            logger()->warning('Couldn\'t render the menu, because the menu ' . $this->key . ' doesn\'t exist');
        } else {
            logger()->warning('Couldn\'t render the menu without a key');
        }

        return '';
    }

    /**
     * Get item tree.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getItemTree(): Collection
    {
        return collect();
    }

    /**
     * Seed page menus.
     *
     * @param $menus
     * @return void
     * @throws \Exception
     */
    public function seed($menus): void
    {
        if (!is_iterable($menus)) {
            throw new Exception('Invalid data given!');
        }

        foreach ($menus as $menuData) {
            $menu = Menu::firstOrCreate(array_except($menuData, ['name']));
            $translations = [];

            foreach (TransHelper::getAllLanguages() as $language) {
                $translations[$language->iso_code] = [
                    'name' => $menuData['name'],
                ];
            }

            $menu->updateTranslations($translations);
        }
    }

    /**
     * Seed menu items for specific menu.
     *
     * @param $menusWithItems
     * @return void
     * @throws \Exception
     */
    public function seedItems($menusWithItems): void
    {
        if (!is_iterable($menusWithItems)) {
            throw new Exception('Invalid data given!');
        }

        foreach ($menusWithItems as $menuKey => $items) {
            if ($menu = Menu::where('key', $menuKey)->first()) {
                $this->seedItemsRecursively($menu, $items);
            }
        }
    }

    /**
     * Seed menu items with children recursively.
     *
     * @param \Modules\Admin\Models\Menu $menu
     * @param array $items
     * @param null|\Modules\Admin\Models\MenuItem $parent
     * @return void
     */
    protected function seedItemsRecursively(Menu $menu, array $items, $parent = null): void
    {
        foreach ($items as $item) {
            $row = $menu->items()->create(
                array_except($item, ['name', 'value', 'parameters', 'children'])
            );

            $translations = [];

            foreach (TransHelper::getAllLanguages() as $language) {
                $name = $item['name'];
                $value = $item['value'];

                // Check if name is passed as string or array with translations.
                if (is_array($name) || $name instanceof Collection) {
                    $name = $name[$language->iso_code];
                }

                // Check if value is passed as string or array with translations.
                if (is_array($value) || $value instanceof Collection) {
                    $value = $value[$language->iso_code];
                }

                $translations[$language->iso_code] = [
                    'name'       => $name,
                    'value'      => $value,
                    'parameters' => $item['parameters'],
                ];
            }

            $row->updateTranslations($translations);

            // Child of parent.
            if ($parent instanceof MenuItem) {
                $parent->appendNode($row);
            }

            if (isset($item['children']) && is_array($item['children'])) {
                $this->seedItemsRecursively($menu, $item['children'], $row);
            }
        }
    }
}