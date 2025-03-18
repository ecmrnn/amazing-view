<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class AppendDefaultUserFilters
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         // Set default values if not present
         $query = $request->query();
         $defaults = [
             'role' => UserRole::ALL->value,
             'status' => UserStatus::ACTIVE->value,
         ];

         if (Route::current()->getName() == 'app.users.index') {
            if (!isset($query['role']) || !isset($query['status'])) {
                return redirect()->route('app.users.index', array_merge($defaults, $query));
            }
         }

        return $next($request);
    }
}
