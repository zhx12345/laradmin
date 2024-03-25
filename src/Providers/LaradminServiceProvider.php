<?php

namespace Zhxlan\Laradmin\Providers;

use Illuminate\Support\ServiceProvider;
use Zhxlan\Laradmin\Http\Controllers\IndexController;
use Zhxlan\Laradmin\Http\Middleware\Authenticate;

class LaradminServiceProvider extends ServiceProvider
{
    protected array $routeMiddleware = [
        'admin.auth' => Authenticate::class
    ];


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // 注册服务绑定
        $this->app->bind('laradmin', function ($app) {
            return new IndexController();
        });
        $this->registerRouteMiddleware();

    }

    protected array $middlewareGroups = [
        'admin' => [
            'admin.auth',
        ],
    ];


    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 注册路由
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');

        // 发布配置文件
        $this->publishes([
            __DIR__ . '/../../config/laradmin.php' => config_path('laradmin.php'),
            __DIR__ . '/../../config/area.php' => config_path('area.php'),
        ]);
    }

    /**
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function registerRouteMiddleware(): void
    {
        $router = $this->app->make('router');
        foreach ($this->routeMiddleware as $key => $middleware) {
            $router->aliasMiddleware($key, $middleware);
        }
        foreach ($this->middlewareGroups as $key => $middleware) {
            $router->middlewareGroup($key, $middleware);
        }
    }

}
