<?php
// app/Listeners/LogUserLogout.php
namespace App\Listeners;

use App\Models\UserSessionLog;
use Illuminate\Auth\Events\Logout;

class LogUserLogout
{
    public function handle(Logout $event)
    {
        if (!config('session.logging_enabled')) return;

        $session = UserSessionLog::where('user_id', $event->user->id)
            ->whereNull('logout_at')
            ->latest('login_at')
            ->first();

        if ($session) {
            $logoutAt = now();
            $session->update([
                'logout_at'        => $logoutAt,
                'duration_seconds' => $logoutAt->diffInSeconds($session->login_at),
                'logout_type'      => 'manual',
            ]);
        }
    }
}