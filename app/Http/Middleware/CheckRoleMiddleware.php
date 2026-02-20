<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        switch ($role) {
            case 'dispatcher':
                if (!$user->isDispatcher()) {
                    abort(403, 'Доступ только для диспетчеров.');
                }
                break;

            case 'master':
                if (!$user->isMaster()) {
                    abort(403, 'Доступ только для мастеров.');
                }
                break;

            default:
                abort(403, 'Неизвестная роль.');
        }

        return $next($request);
    }
}
