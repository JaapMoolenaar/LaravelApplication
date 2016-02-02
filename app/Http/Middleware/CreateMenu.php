<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Settings;
use Menu;

class CreateMenu
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        
        Menu::make('mainMenu', function($menu){
            $menu->add('Home', '');
        });

        if (!Auth::guard($guard)->guest()) {
            Menu::make('userMenu', function($menu){
                $menu->add('Dashboard', 'dashboard');
            });

            Menu::make('adminMenu', function($menu){
                $adminTop = $menu->add('', '#');
                $adminTop->prepend('<i class="fa fa-btn fa-lock"></i> ');
                    $adminTop->add('Users', 'users')->prepend('<i class="fa fa-btn fa-users"></i> ');
            });
        }
        
        return $next($request);
    }
}
