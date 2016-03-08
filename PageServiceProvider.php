<?php

namespace NineCells\Pages;

use App;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Support\ServiceProvider;
use NineCells\Admin\PackageList;
use NineCells\Auth\AuthServiceProvider;

class PageServiceProvider extends ServiceProvider
{
    private function registerPolicies(GateContract $gate)
    {
        $gate->before(function ($user, $ability) {
            if ($ability === "pages-write") {
                return $user;
            }
        });
    }

    public function boot(GateContract $gate, PackageList $packages)
    {
        $this->registerPolicies($gate);

        if (! $this->app->routesAreCached()) {
            require __DIR__ . '/Http/routes.php';
        }

        $this->loadViewsFrom(__DIR__ . '/resources/views', 'ncells');

        $this->publishes([
            __DIR__ . '/database/migrations/' => database_path('migrations')
        ], 'migrations');

        $packages->addPackageInfo('pages', 'Pages', function() {
            return 'PagesServiceProvider.php를 수정하세요';
        });
    }

    public function register()
    {
        App::register(AuthServiceProvider::class);
    }
}
