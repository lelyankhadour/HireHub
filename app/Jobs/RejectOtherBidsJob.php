<?php

namespace App\Jobs;

use App\Models\Bid;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RejectOtherBidsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Bid $acceptedBid;

    public function __construct(Bid $acceptedBid)
    {
        $this->acceptedBid = $acceptedBid;
    }

    public function handle(): void
    {
        try {
            $project = $this->acceptedBid->project;


            $project->bids()
                ->where('id', '!=', $this->acceptedBid->id)
                ->update(['status' => 'rejected']);
        } catch (\Throwable $e) {

            \Log::error('RejectOtherBidsJob failed', [
           ' acceptedBid' =>$this->acceptedBid->project ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);


            throw $e;
        }
    }
}