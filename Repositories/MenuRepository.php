<?php

namespace Modules\Admin\Repositories;

use Modules\Admin\Models\Menu;
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
     */
    public function setKey($key)
    {
        $this->key = $key;
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
                    'name' => $menuData['name']
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
            $menu = Menu::where('key', $menuKey)->first();

            if ($menu) {
                foreach ($items as $item) {
                    $row = $menu->items()->create(array_except($item, ['name', 'value', 'parameters']));

                    $translations = [];
                    foreach (TransHelper::getAllLanguages() as $language) {
                        $translations[$language->iso_code] = [
                            'name'       => $item['name'][$language->iso_code],
                            'value'      => $item['value'][$language->iso_code],
                            'parameters' => $item['parameters']
                        ];
                    }
                    $row->updateTranslations($translations);
                }
            }
        }
    }
}