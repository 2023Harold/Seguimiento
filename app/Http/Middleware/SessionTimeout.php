<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionTimeout
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
        if (Auth::check()) {
            $timeout    = config('session.lifetime') * 60; // en segundos
            $lastActivity = $request->session()->get('last_activity_time');

            if ($lastActivity && (time() - $lastActivity) > $timeout) {
                // Registrar logout por expiración
                if (config('session.logging_enabled')) {
                    $session = \App\Models\UserSessionLog::where('user_id', Auth::id())
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
                }

                Auth::logout();
                $request->session()->flush();

                if ($request->expectsJson()) {
                    return response()->json(['session_expired' => true], 401);
                }

                return redirect()->route('login')->with('message', 'Sesión expirada por inactividad.');
            }

            // Actualiza el último momento de actividad
            $request->session()->put('last_activity_time', time());
        }

        return $next($request);
    }
}
