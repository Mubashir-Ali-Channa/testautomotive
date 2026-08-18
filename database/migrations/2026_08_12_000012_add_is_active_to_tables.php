<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hero_slides', function (Blueprint $table) {
            $table->boolean('is_active')->default(true);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_active')->default(true);
        });

        Schema::table('mechanics', function (Blueprint $table) {
            $table->boolean('is_active')->default(true);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_active')->default(true);
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->boolean('is_active')->default(true);
        });

        Schema::table('careers', function (Blueprint $table) {
            $table->boolean('is_active')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('hero_slides', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });

        Schema::table('mechanics', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });

        Schema::table('careers', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
