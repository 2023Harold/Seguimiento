<?php

namespace App\Http\Middleware;

use App\Models\UserSessionLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackSessionExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!config('session.logging_enabled')) {
            return $next($request);
        }
        // Si había usuario autenticado pero ya no hay sesión válida
        if (!Auth::check()) {
            $userId = $request->session()->get('last_user_id');

            if ($userId) {
                $session = UserSessionLog::where('user_id', $userId)
                    ->whereNull('logout_at')
                    ->latest('login_at')
                    ->first();

                if ($session) {
                    $logoutAt = now();
                    $session->update([
                        'logout_at'        => $logoutAt,
                        'duration_seconds' => $logoutAt->diffInSeconds($session->login_at),
                        'logout_type'      => 'expired',
                    ]);
                }

                $request->session()->forget('last_user_id');
            }
        } else {
            // Guarda el ID del usuario en sesión para detectar expiración
            $request->session()->put('last_user_id', Auth::id());
        }

        return $next($request);

    }
}
