<?php

namespace Modules\Admin\Repositories;

use Modules\Admin\Models\Menu;

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
     * MenuRepository constructor.
     */
    public function __construct()
    {
        $this->menu = new Menu();
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
}