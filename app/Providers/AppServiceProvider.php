<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register app layout component
        Blade::component('app-layout', \App\View\Components\Layouts\App::class);
        
        // Register anonymous components from resources/views/components
        Blade::anonymousComponentPath(resource_path('views/components'));
        
        // Register components with class implementations
        Blade::componentNamespace('App\\View\\Components', 'app');
        
        // Register navigation components
        Blade::component('nav-link', \App\View\Components\NavLink::class);
        Blade::component('responsive-nav-link', \App\View\Components\ResponsiveNavLink::class);
    }
}