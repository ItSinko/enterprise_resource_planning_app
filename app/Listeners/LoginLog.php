<?php

namespace App\Listeners;

use Carbon\Carbon;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LoginLog
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
     * @param  \Illuminate\Auth\Events\Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $event->subject = 'login';
        $event->description = 'Login Successfully';

        activity($event->subject)
            ->by($event->user)
            ->withProperties(['status' => 'online', 'last_login' => Carbon::now(), 'nama' => $event->user->nama])
            ->log($event->description);
    }
}
