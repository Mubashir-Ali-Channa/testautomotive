<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mechanics', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('bio');
            $table->string('role'); // e.g. Master Mechanic, Custom Builder
            $table->string('avatar_path')->nullable();
            $table->text('specialties')->nullable(); // json or comma-separated text
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mechanics');
    }
};
