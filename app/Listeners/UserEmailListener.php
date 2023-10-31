<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserEmailListener
{
    // NOTE Synchronize: implements ShouldQueue
    // NOTE Synchronize: use InteractsWithQueue;

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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if (!$event) {
            Log::error('Rating was empty' . ' [' . __CLASS__ . '\\' . __FUNCTION__ . ':' . __LINE__ . ']');
            return false;
        }

    }
}
