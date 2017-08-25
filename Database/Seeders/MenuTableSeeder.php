<?php

namespace Modules\Admin\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Models\Menu;

class MenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $menus = [
            'leftAdminMenu' => [
                [
                    'name'   => 'Dashboard',
                    'icon'   => 'ion-ios-pulse-strong',
                    'type'   => 'route',
                    'value'  => 'admin::dashboard.index',
                    'module' => 'Admin'
                ],
                [
                    'name'   => 'Menus',
                    'icon'   => 'ion-navicon-round',
                    'type'   => 'route',
                    'value'  => 'admin::menu.index',
                    'module' => 'Admin'
                ]
            ],
            'topleftAdminMenu' => [
                [
                    'name'   => 'Homepage',
                    //'icon'   => 'ion-android-unlock',
                    'type'   => 'url',
                    'value'  => '/',
                    //'module' => 'User'
                ],
            ],
            'main' => [
                [
                    'name'  => 'Home',
                    'type'  => 'url',
                    'value' => '/'
                ]
            ]
        ];

        foreach( $menus as $name => $items ) {
            $menu = Menu::firstOrCreate([
                'name' => $name
            ]);

            foreach( $items as $item ){
                $menu->items()->firstOrCreate($item);
            }
        }
    }
}
