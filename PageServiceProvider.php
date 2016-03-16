<?php

namespace NineCells\Pages;

use App;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Support\ServiceProvider;
use NineCells\Admin\AdminManager;
use NineCells\Admin\AdminServiceProvider;
use NineCells\Admin\PackageList;
use NineCells\Auth\MemberServiceProvider;

class PageServiceProvider extends ServiceProvider
{
    private function registerPolicies(GateContract $gate, AdminManager $admin)
    {
        $gate->before(function ($user, $ability) use ($admin) {
            if ($ability === "page-admin") {
                if ($user && $admin->isAdmin($user)) {
                    return $user;
                }
            } else if ($ability === "page-write") {
                return $user;
            }
        });
    }

    public function boot(GateContract $gate, AdminManager $admin, PackageList $packages)
    {
        $this->registerPolicies($gate, $admin);

        if (!$this->app->routesAreCached()) {
            require __DIR__ . '/Http/routes.php';
        }

        $this->loadViewsFrom(__DIR__ . '/resources/views', 'ncells');

        $this->publishes([
            __DIR__ . '/database/migrations/' => database_path('migrations')
        ], 'migrations');

        $packages->addPackageInfo('pages', 'Pages', function () {
            return '/admin/pages';
        });
    }

    public function register()
    {
        App::register(MemberServiceProvider::class);
        App::register(AdminServiceProvider::class);
    }
}
