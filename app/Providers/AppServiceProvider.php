<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Bootstrap pagination
        Paginator::useBootstrapFive();

        // Theme global pour toutes les vues
        View::composer('*', function ($view) {
            try {
                $siteTheme = SiteSetting::get('site_theme', 'default');
            } catch (\Throwable $e) {
                $siteTheme = 'default';
            }
            $view->with('siteTheme', $siteTheme);
        });
    }
}