<?php

namespace Modules\Admin\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Facades\Blade;
use Illuminate\Foundation\AliasLoader;
use Modules\Admin\Console\PublishTests;
use Modules\Admin\Repositories\MenuRepository;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot(\Illuminate\Routing\Router $router, \Illuminate\Contracts\Http\Kernel $kernel)
    {
        //$this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        //$this->registerFactories();
        //$this->registerBladeExtenders();
        $this->registerMiddlewares($router, $kernel);

        $this->commands([
            PublishTests::class
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(\HieuLe\Active\ActiveServiceProvider::class);
        $this->app->register(\Collective\Html\HtmlServiceProvider::class);
        $this->app->register(\DaveJamesMiller\Breadcrumbs\BreadcrumbsServiceProvider::class);

        AliasLoader::getInstance()->alias('Form', \Collective\Html\FormFacade::class);
        AliasLoader::getInstance()->alias('Html', \Collective\Html\HtmlFacade::class);

        $this->app->singleton('MenuRepository', function ($app) {
            return new MenuRepository();
        });
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__ . '/../Config/config.php' => config_path('netcore/module-admin.php'),
        ], 'config');

        $this->mergeConfigFrom(__DIR__ . '/../Config/config.php', 'admin');

        config()->set('breadcrumbs.view', 'admin::_partials._breadcrumbs');
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/admin');

        $sourcePath = __DIR__ . '/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/admin';
        }, config('view.paths')), [$sourcePath]), 'admin');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/admin');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'admin');
        } else {
            $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'admin');
        }
    }

    /**
     * Register an additional directory of factories.
     * @source https://github.com/sebastiaanluca/laravel-resource-flow/blob/develop/src/Modules/ModuleServiceProvider.php#L66
     */
    public function registerFactories()
    {
        if (!app()->environment('production')) {
            app(Factory::class)->load(__DIR__ . '/Database/factories');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    public function registerBladeExtenders()
    {
        //
    }

    public function registerMiddlewares($router, $kernel)
    {
        //global middleware
        //$kernel->prependMiddleware(\Path\To\Your\Middleware\custom_auth::class);
        //$kernel->pushMiddleware(\Path\To\Your\Middleware\custom_auth::class);

        //router middleware
        //$router->middleware('can.admin', \Modules\Admin\Http\Middleware\Admin\canAuthorizeInAdmin::class);
        //$router->middleware('auth.admin', \Modules\Admin\Http\Middleware\Admin\isAdmin::class);

        //
        $router->aliasMiddleware('can.admin', \Modules\Admin\Http\Middleware\Admin\canAuthorizeInAdmin::class);
        $router->aliasMiddleware('auth.admin', \Modules\Admin\Http\Middleware\Admin\isAdmin::class);
    }
}
