<?php

namespace App\Listeners;


use App\Models\UserSessionLog;
use Illuminate\Auth\Events\Login;

class LogUserLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        if (!config('session.logging_enabled')) return;

        UserSessionLog::create([
            'user_id'    => $event->user->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'login_at'   => now(),
        ]);
    }
}
