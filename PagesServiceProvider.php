<?php

namespace NineCells\Pages;

use App;
use Illuminate\Support\ServiceProvider;
use NineCells\Admin\PackageList;
use NineCells\Wiki\WikiServiceProvider;

class PagesServiceProvider extends ServiceProvider
{
    public function boot(PackageList $packages)
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'ncells');

        $packages->addPackageInfo('wiki', 'Wiki', function() {
            return 'WikiServiceProvider.php를 수정하세요';
        });
    }

    public function register()
    {
        App::register(WikiServiceProvider::class);
    }
}
