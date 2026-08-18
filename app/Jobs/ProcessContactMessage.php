<?php

namespace App\Jobs;

use App\Models\ContactMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessContactMessage implements ShouldQueue
{
    use Queueable;

    protected array $data;

    /**
     * Create a new job instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        ContactMessage::create([
            'name' => strip_tags($this->data['name']),
            'email' => strip_tags($this->data['email']),
            'subject' => strip_tags($this->data['subject']),
            'message' => strip_tags($this->data['message']),
        ]);
    }
}
