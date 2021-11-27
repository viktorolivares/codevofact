<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(
            'tenant.layouts.partials.header',
            'App\Http\ViewComposers\Tenant\CompanyViewComposer'
        );

        view()->composer(
            'tenant.layouts.partials.sidebar',
            'App\Http\ViewComposers\Tenant\CompanyViewComposer'
        );

        view()->composer(
            'tenant.layouts.partials.header',
            'App\Http\ViewComposers\Tenant\UserViewComposer'
        );

        view()->composer(
            'tenant.layouts.partials.header',
            'Modules\Document\Http\ViewComposers\DocumentViewComposer'
        );

        view()->composer(
            'tenant.layouts.partials.header',
            'App\Http\ViewComposers\Tenant\ModuleViewComposer'
        );

        view()->composer(
            'tenant.layouts.partials.sidebar',
            'App\Http\ViewComposers\Tenant\CompanyViewComposer'
        );

        view()->composer(
            'tenant.layouts.partials.sidebar',
            'App\Http\ViewComposers\Tenant\ModuleViewComposer'
        );

        view()->composer(
            'tenant.layouts.app',
            'App\Http\ViewComposers\Tenant\CompactSidebarViewComposer'
        );
        view()->composer(
            'tenant.layouts.app_pos',
            'App\Http\ViewComposers\Tenant\CompactSidebarViewComposer'
        );

        view()->composer(
            'tenant.layouts.partials.sidebar',
            'Modules\LevelAccess\Http\ViewComposers\ModuleLevelViewComposer'
        );

        view()->composer(
            'tenant.layouts.partials.sidebar_styles',
            'App\Http\ViewComposers\Tenant\ConfigurationVisualViewComposer'
        );

        view()->composer(
            'tenant.layouts.app',
            'App\Http\ViewComposers\Tenant\ConfigurationVisualViewComposer'
        );

        view()->composer(
            'tenant.layouts.app',
            'App\Http\ViewComposers\Tenant\CompanyViewComposer'
        );
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
