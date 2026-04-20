<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Project;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
       
        $project1 = Project::find(1);
        $project2 = Project::find(2);

        $client1 = User::where('email', 'client1@hirehub.com')->first();
        $client2 = User::where('email', 'client2@hirehub.com')->first();

        Review::create([
            'user_id'          => $client1->id,
            'reviewable_id'    => $project1->id,
            'reviewable_type'  => Project::class,
            'rating'           => 4.5,
            'comment'          => 'Great work! Delivered on time and with high quality.',
        ]);

        Review::create([
            'user_id'          => $client2->id,
            'reviewable_id'    => $project2->id,
            'reviewable_type'  => Project::class,
            'rating'           => 3.8,
            'comment'          => 'Good job overall, but communication could be better.',
        ]);

  
        $freelancer1 = User::where('email', 'freelancer1@hirehub.com')->first();
        $freelancer2 = User::where('email', 'freelancer2@hirehub.com')->first();

        Review::create([
            'user_id'          => $client1->id,
            'reviewable_id'    => $freelancer1->id,
            'reviewable_type'  => User::class,
            'rating'           => 5.0,
            'comment'          => 'Amazing freelancer! Highly recommended.',
        ]);

        Review::create([
            'user_id'          => $client2->id,
            'reviewable_id'    => $freelancer2->id,
            'reviewable_type'  => User::class,
            'rating'           => 4.2,
            'comment'          => 'Very professional and skilled.',
        ]);
    }
}
