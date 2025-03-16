<?php

namespace App\Http\Middleware;

use App\Enums\PageStatus;
use App\Models\Page;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPageStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $uri = $request->getRequestUri();
        $page = Page::whereUrl($uri)->first();
        $user = Auth::user();
        
        if ($user) {
            if ($user->hasRole('admin')) {
                return $next($request);
            }
        }
        
        switch ($page->status ?? 0) {
            case PageStatus::DISABLED->value:
                return response()->view('error.404', status: 404);
                break;
            case PageStatus::MAINTENANCE->value:
                return response()->view('error.503', status: 503);
                break;
            default:
                return $next($request);
                break;
        }
    }
}
