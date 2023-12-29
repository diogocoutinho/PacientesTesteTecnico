<?php

namespace App\Listeners;

use App\Events\JobCompleted;

class ProcessJobCompleted
{
    public function handle(JobCompleted $event)
    {
        $message = $event->message;

        // Manipular a mensagem como desejado
        // Por exemplo, pode-se armazenar no Redis
//        \Redis::set('job_completed_message', $message);
        \Log::info('Job completed Listener: ' . $message);
    }
}
