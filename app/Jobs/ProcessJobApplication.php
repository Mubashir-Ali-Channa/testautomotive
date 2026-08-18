<?php

namespace App\Jobs;

use App\Models\JobApplication;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessJobApplication implements ShouldQueue
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
        JobApplication::create([
            'career_id' => $this->data['career_id'],
            'name' => strip_tags($this->data['name']),
            'email' => strip_tags($this->data['email']),
            'phone' => strip_tags($this->data['phone']),
            'resume_path' => $this->data['resume_path'],
            'message' => strip_tags($this->data['message'] ?? ''),
        ]);
    }
}
