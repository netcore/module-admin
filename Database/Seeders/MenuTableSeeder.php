<?php

namespace Modules\Admin\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Models\Menu;
use Netcore\Translator\Helpers\TransHelper;

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
            'leftAdminMenu'    => [
                [
                    'name'       => 'Dashboard',
                    'icon'       => 'ion-ios-pulse-strong',
                    'type'       => 'route',
                    'value'      => 'admin::dashboard.index',
                    'module'     => 'Admin',
                    'is_active'  => 1,
                    'parameters' => json_encode([])
                ],
                [
                    'name'            => 'Menus',
                    'icon'            => 'ion-navicon-round',
                    'type'            => 'route',
                    'value'           => 'admin::menus.index',
                    'module'          => 'Admin',
                    'is_active'       => 1,
                    'active_resolver' => 'admin::menus.*',
                    'parameters'      => json_encode([])
                ]
            ],
            'topLeftAdminMenu' => [
                [
                    'name'       => 'Homepage',
                    'type'       => 'url',
                    'value'      => '/',
                    'parameters' => json_encode([])
                ],
            ]
        ];

        if (config('netcore.module-admin.whitelist.enabled')) {
            $menus['leftAdminMenu'][] = [
                'name'            => 'IP Whitelist',
                'icon'            => 'fa fa-server',
                'type'            => 'route',
                'value'           => 'admin::whitelist.index',
                'module'          => 'Admin',
                'is_active'       => 1,
                'active_resolver' => 'admin::whitelist.*',
                'parameters'      => json_encode([])
            ];
        }

        foreach ($menus as $key => $items) {
            $menu = Menu::firstOrCreate([
                'key' => $key
            ]);

            $translations = [];
            foreach (TransHelper::getAllLanguages() as $language) {
                $translations[$language->iso_code] = [
                    'name' => ucwords(preg_replace(array('/(?<=[^A-Z])([A-Z])/', '/(?<=[^0-9])([0-9])/'), ' $0', $key))
                ];
            }
            $menu->updateTranslations($translations);

            foreach ($items as $item) {
                $row = $menu->items()->firstOrCreate(array_except($item, ['name', 'value', 'parameters']));

                $translations = [];
                foreach (TransHelper::getAllLanguages() as $language) {
                    $translations[$language->iso_code] = [
                        'name'       => $item['name'],
                        'value'      => $item['value'],
                        'parameters' => $item['parameters']
                    ];
                }
                $row->updateTranslations($translations);
            }
        }
    }
}
