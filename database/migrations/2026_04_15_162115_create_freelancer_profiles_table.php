<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    public function up(): void
    {
        Schema::create('freelancer_profiles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->unique()
                  ->constrained()
                  ->cascadeOnDelete();

            $table->text('bio')->nullable();

            $table->decimal('hourly_rate', 10, 2)->nullable();

            $table->enum('availability_status', ['available', 'busy', 'unavailable'])
                  ->default('available');

            $table->string('avatar')->nullable();

            $table->json('portfolio_links')->nullable();
            $table->json('skills_summary')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('freelancer_profiles');
    }
};


