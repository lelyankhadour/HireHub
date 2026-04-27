<?php
namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class RecalculateFreelancerRatingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public User $freelancer;

    public function __construct(User $freelancer)
    {
        $this->freelancer = $freelancer;
    }

    public function handle(): void
    {
        try {
            // استخدام Transaction لضمان عدم تداخل البيانات
            DB::transaction(function () {
                $avg = $this->freelancer->reviews()->avg('rating') ?? 0;

                $this->freelancer->update([
                    'rating' => round($avg, 2)
                ]);
            });
        } catch (\Throwable $e) {

            \Log::error('RecalculateFreelancerRatingJob  failed', [
                'freelancer_id' => $this->freelancer->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);


            throw $e;
        }
    }
}