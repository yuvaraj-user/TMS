<?php

namespace App\Listeners;

use App\Events\taskupdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class taskupdatelistener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(taskupdate $event): void
    {
        // dd($event);
    }
}
