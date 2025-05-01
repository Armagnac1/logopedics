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
        Schema::create('pupils', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutor_id')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('city_id')->nullable();
            $table->string('full_name');
            $table->string('parent_name')->nullable();
            $table->string('age')->nullable();
            $table->string('time_zone')->nullable();  //integer?
            $table->string('phone')->nullable();
            $table->text('tutor_comments')->nullable();
            $table->unsignedInteger('lesson_duration')->default(30);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pupils');
    }
};
