<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = ['Web', 'Mobile', 'Backend', 'Frontend', 'API', 'Design', 'Marketing', 'SEO', 'Branding', 'AI'];

        foreach ($tags as $tag) {
            Tag::create(['name' => $tag]);
        }
    }
}
