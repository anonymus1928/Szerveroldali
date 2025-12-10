<?php

namespace App\Providers;

use App\Models\Ticket;
use App\Policies\TicketPolicy;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
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
        Paginator::useBootstrapFive();

        // Policy-k alapján "Gate"-ek definiálása
        // Ezeket a Gate-eket lehet majd használni a controller-ekben
        Gate::define('view-any', [TicketPolicy::class, 'viewAny']);
        Gate::define('view', [TicketPolicy::class, 'view']);
        Gate::define('create', [TicketPolicy::class, 'create']);
        Gate::define('update', [TicketPolicy::class, 'update']);
        Gate::define('delete', [TicketPolicy::class, 'delete']);
    }
}
