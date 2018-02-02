let mix = require('laravel-mix');

const moduleDir = __dirname;
const resPath = moduleDir + '/Resources/assets';
const compileTo = moduleDir + '/Assets';

mix

    .js(resPath + '/js/components.js', compileTo + '/js/components.js')
    .js(resPath + '/js/menu.js', compileTo + '/js/menu.js')

    /**
     * -----------------------------
     * ----- Menu Edit Page --------
     * -----------------------------
     */

    .js(resPath + '/js/menu/app.js', compileTo + '/js/menu/app.js')

    .disableNotifications();