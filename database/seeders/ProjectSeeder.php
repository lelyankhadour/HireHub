<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\User;

class ProjectSeeder extends Seeder
{
    public function run(): void
{
    $clients = User::where('role', 'client')->get();
    $tags = \App\Models\Tag::pluck('id');


    foreach ($clients as $client) {

        $openProjects = Project::factory()->count(30)->create([
            'client_id' => $client->id,
            'status' => 'open',
        ]);

        foreach ($openProjects as $project) {
            $project->tags()->attach($tags->random(3));
        }


        $closedProjects = Project::factory()->count(2)->create([
            'client_id' => $client->id,
            'status' => 'closed',
        ]);

        foreach ($closedProjects as $project) {
            $project->tags()->attach($tags->random(2));
        }
    }
}


}
