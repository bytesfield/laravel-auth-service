<?php

namespace App\Listeners\Auth;

use App\Events\Auth\UserCreatedEvent;
use App\Mail\Auth\UserEmailVerification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailVerification implements ShouldQueue
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
     * @param  UserCreatedEvent  $event
     * @return void
     */
    public function handle(UserCreatedEvent $event): void
    {
        Mail::to($event->user->email)->send(new UserEmailVerification($event->user));
    }
}
