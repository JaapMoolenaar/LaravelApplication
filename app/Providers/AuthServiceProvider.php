<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);
        
        // superusers can do all, except un-make themselves superuser
        // or deleting themselves
        $gate->before(function ($user, $ability) {
            if($ability == 'delete-user') return;
            if($ability == 'createsuperuser') return;
            
            if (!!$user->superuser) {
                return true;
            }
        });

        $gate->define('createsuperuser', function ($user, $item) {
            // only update superusers if not updating self
            if($user->superuser && $user->getKey() !== $item->getKey()) {
                return true;
            }
            
            return false;
        });
        
        $gate->define('delete-user', function ($user, $item) {
            // only delete superusers if not deleting self
            if($item->superuser && $user->superuser && $user->getKey() !== $item->getKey()) {
                return true;
            }
            
            return !$item->superuser && $user->getKey() !== $item->getKey();
        });
        
        $gate->define('update-user', function ($user, $item) {
            return $user->hasPermission('update-user') && !$item->superuser;
        });
                
        foreach(config('acl.roles') as $role => $permissions) {
            foreach($permissions as $permission) {
                // These are special cases, treated via the rules found above
                // This has to do with the way super users are managed
                if($permission == 'update-user') continue;
                if($permission == 'delete-user') continue;
                if($permission == 'createsuperuser') continue;

                $gate->define($permission, function ($user) use($role, $permission) {
                    return $user->hasPermission($permission);
                });
            }
        }
    }
}
