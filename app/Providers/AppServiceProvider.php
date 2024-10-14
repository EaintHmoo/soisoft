<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Assets\Js;

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
        Gate::policy(\App\Models\Admin\Category::class, \App\Policies\Admin\CategoryPolicy::class);
        Gate::policy(\App\Models\Admin\PrePopulatedData::class, \App\Policies\Admin\PrePopulatedDataPolicy::class);

        Gate::policy(\App\Models\Buyer\Tender::class, \App\Policies\Buyer\TenderPolicy::class);
        Gate::policy(\App\Models\Buyer\Quotation::class, \App\Policies\Buyer\QuotationPolicy::class);
        Gate::policy(\App\Models\Buyer\Contact::class, \App\Policies\Buyer\ContactPolicy::class);
        Gate::policy(\App\Models\Buyer\Department::class, \App\Policies\Buyer\DepartmentPolicy::class);
        Gate::policy(\App\Models\Buyer\Project::class, \App\Policies\Buyer\ProjectPolicy::class);   


        FilamentAsset::register([
            Js::make('preline-accordion', __DIR__ . '/../../node_modules/@preline/accordion/index.js'),
        ]);
    }
}
