<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('request_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();
            $table->integer('status_code')->default(200);
            $table->integer('query_count')->default(0);
          
            $table->string('method');        
          $table->text('endpoint');

            $table->integer('duration_ms');  

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_logs');
    }
};
