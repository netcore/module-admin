## Administration system

This module adds an administration panel to the site, which gives access to other module administration and menu editing. From here you will be able to edit the site content if Content module is present, add/change the menu items etc. 

## Pre-installation
This package is part of Netcore CMS ecosystem and is only functional in a project that has following packages installed:

https://github.com/netcore/netcore

https://github.com/netcore/module-user

https://github.com/netcore/module-setting

https://github.com/nWidart/laravel-modules

## Installation
 
 Require this package with composer:
 ```$xslt
 composer require netcore/module-admin
```
 Publish config, assets, migrations. Migrate and seed:
 
 ```$xslt
 php artisan module:publish Admin
 php artisan module:publish-migration Admin
 php artisan migrate
 php artisan module:seed Admin
```

## Usage

You can edit the admin menu and client menus in the Menus section

Managing menus:
![Menus](https://www.dropbox.com/s/pyu527891vxps6x/Screenshot%202017-11-08%2009.49.15.png?raw=1)


## Seeding

You can add new menus by creating seeders

```PHP

$menus = [
    [
        name => 'leftAdminMenu',
        type => 'admin',
        items => [
                'name'   => 'Dashboard',
                'icon'   => 'ion-ios-pulse-strong',
                'type'   => 'route',
                'value'  => 'admin::dashboard.index',
                'module' => 'Admin',
                'is_active' => 1,
                'parameters' => json_encode([])
            ],
            [
                'name'   => 'Menus',
                'icon'   => 'ion-navicon-round',
                'type'   => 'route',
                'value'  => 'admin::menu.index',
                'module' => 'Admin',
                'is_active' => 1,
                'active_resolver' => 'admin::menu.*',
                'parameters' => json_encode([])
            ]
    ],
    [
        name => 'mainClientMenu',
        type => 'public',
        items => [
             [
                 'name'   => 'Homepage',
                 'icon'   => 'fa-globe',
                 'type'   => 'url',
                 'value'  => '/',
                 'parameters' => json_encode([])
             ],
         ]
    ]
];

foreach( $menus as $key => $menu ) {
    $menu = Menu::firstOrCreate([
        'name' => $menu['name'],
        'type' => $menu['type']
    ]);

    foreach( $menu['items'] as $item ){
        $menu->items()->firstOrCreate($item);
    }
}

```
