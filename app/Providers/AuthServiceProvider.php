<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Product::class => \App\Policies\ProductPolicy::class,
        \App\Models\Order::class => \App\Policies\OrderPolicy::class,
        \App\Models\Review::class => \App\Policies\ReviewPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define additional gates for complex authorization logic
        Gate::define('manage-products', function (User $user) {
            return $user->can('products.create') || 
                   $user->can('products.update') || 
                   $user->can('products.delete');
        });

        Gate::define('manage-orders', function (User $user) {
            return $user->can('orders.update') || 
                   $user->can('orders.cancel') || 
                   $user->can('orders.delete');
        });

        Gate::define('access-admin', function (User $user) {
            return $user->hasRole(['super_admin', 'admin', 'manager']) ||
                   in_array($user->role, ['super_admin', 'admin', 'manager']);
        });
    }
}

