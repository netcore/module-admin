<?php

namespace Modules\Admin\Repositories;

use Illuminate\Support\Collection;
use Modules\Admin\Models\Menu;
use Modules\Admin\Models\MenuItem;
use Netcore\Translator\Helpers\TransHelper;

/**
 * Class MenuRepository
 * @package Modules\Admin\Repositories
 */
class MenuRepository
{

    /**
     * @var Menu
     */
    private $menu;

    /**
     * @var string
     */
    private $key;

    /**
     * MenuRepository constructor.
     */
    public function __construct()
    {
        $this->menu = new Menu();
        $this->key = '';
    }

    /**
     * @param $key
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @param null $key
     * @return mixed
     */
    public function get($key = null)
    {
        if (!$key) {
            return $this->getAll();
        }

        return $this->menu->where('key', $key)->first();
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        return $this->menu->get();
    }

    /**
     * @return string
     */
    public function render()
    {
        if ($this->key) {
            logger()->warning('Couldn\'t render the menu, because the menu ' . $this->key . ' doesn\'t exist');
        } else {
            logger()->warning('Couldn\'t render the menu without a key');
        }

        return '';
    }

    /**
     * Seed page menus
     *
     * @param $menus
     */
    public function seed($menus)
    {
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
     * Seed menu items for specific menu
     *
     * @param $menusWithItems
     */
    public function seedItems($menusWithItems)
    {
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
    protected function seedItemsRecursively(Menu $menu, array $items, $parent = null)
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