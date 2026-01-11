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
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->foreignId('coach_id')->constrained('users')->onDelete('cascade');
            $table->enum('difficulty', ['beginner', 'intermediate', 'advanced']);
            $table->integer('duration_weeks');
            $table->enum('goal', ['weight_loss', 'muscle_gain', 'endurance', 'flexibility', 'general_fitness']);
            $table->string('image')->nullable();
            $table->boolean('is_public')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
