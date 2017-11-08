## Administration Panel

This module adds an administration panel to the site, which gives access to other module administration and menu editing. From here you will be able to edit the site content if Content module is present, add/change the menu items etc. 

## Pre-installation
This package is part of Netcore CMS ecosystem and is only functional in a project that has following packages installed:

https://github.com/netcore/netcore

https://github.com/netcore/module-user

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
