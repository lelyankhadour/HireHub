<?php

namespace App\Jobs;

use App\Models\Bid;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendBidAcceptedEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Bid $bid;

    public function __construct(Bid $bid)
    {
        $this->bid = $bid;
    }

    public function handle(): void
    {try{
        $freelancer = $this->bid->freelancer;
        $project    = $this->bid->project;

        Mail::raw(
            "Your bid on project '{$project->title}' has been accepted!",
            function ($message) use ($freelancer) {
                $message->to($freelancer->email)
                        ->subject('Your bid was accepted');
            }
        );
    }catch (\Throwable $e) {

        \Log::error('SendBidAcceptedEmailJob failed', [
            'bid_id'     => $this->bid->id,
            'error'      => $e->getMessage(),
            'trace'      => $e->getTraceAsString(),
        ]);

      
        throw $e;
    }
}}
