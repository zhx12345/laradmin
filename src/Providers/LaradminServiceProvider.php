<?php

namespace Zhxlan\Laradmin\Providers;

use Illuminate\Support\ServiceProvider;
use Zhxlan\Laradmin\Http\Controllers\IndexController;

class LaradminServiceProvider extends ServiceProvider
{
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
    }

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
            __DIR__.'/../../config/laradmin.php' => config_path('laradmin.php'),
            __DIR__.'/../../config/area.php' => config_path('area.php'),
        ]);
    }
}
