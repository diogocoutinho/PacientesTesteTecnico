<?php

namespace App\Listeners;

use App\Events\JobCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendJobCompletedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(JobCompleted $event)
    {
        // Enviar notificação para o frontend
//        \Pusher::trigger('job-completed', 'JobCompleted', ['message' => $event->message]);
    }
}
