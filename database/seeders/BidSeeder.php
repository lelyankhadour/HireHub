<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bid;
use App\Models\User;
use App\Models\Project;

class BidSeeder extends Seeder
{
    public function run(): void
    {
        $freelancers = User::where('role', 'freelancer')->get();
        $projects = Project::where('status', 'open')->get();

        foreach ($projects as $project) {
            foreach ($freelancers as $freelancer) {
                Bid::factory()->create([
                    'project_id' => $project->id,
                    'freelancer_id' => $freelancer->id,
                ]);
            }
        }
    }
}
