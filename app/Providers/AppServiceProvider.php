<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Policies\UserPolicy;

use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
    ];
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
        Gate::define('perfil_admin', function (User $user) {
            return $user->perfil == 'admin';
        });
        Gate::define('perfil_medico', function (User $user) {
            return $user->perfil == 'medico';
        });
        Gate::define('perfil_recepcao', function (User $user) {
            return $user->perfil == 'recepcao';
        });

        $this->register();
        
    }
}
