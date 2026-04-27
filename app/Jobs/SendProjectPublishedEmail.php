<?php

namespace App\Jobs;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendProjectPublishedEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Project $project;

    public $tries = 5;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }
    public function handle(): void
    {
      
        try {

            $client = $this->project->client;

            Mail::raw(
                "Your project '{$this->project->title}' has been published successfully!",
                function ($message) use ($client) {
                    $message->to($client->email)
                        ->subject('Project Published');
                }
            );

        } catch (\Throwable $e) {

            \Log::error('SendProjectPublishedEmail failed', [
                'project_id' => $this->project->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }
}
