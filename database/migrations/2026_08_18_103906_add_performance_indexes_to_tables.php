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
        Schema::table('products', function (Blueprint $table) {
            $table->index('is_active');
            $table->index('is_featured');
            $table->index('category');
            $table->index('price');
            $table->index('slug');
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->index('is_active');
            $table->index('slug');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('status');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->index('is_approved');
            $table->index('product_id');
        });

        Schema::table('careers', function (Blueprint $table) {
            $table->index('is_active');
        });

        Schema::table('mechanics', function (Blueprint $table) {
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
            $table->dropIndex(['is_featured']);
            $table->dropIndex(['category']);
            $table->dropIndex(['price']);
            $table->dropIndex(['slug']);
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
            $table->dropIndex(['slug']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['status']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex(['is_approved']);
            $table->dropIndex(['product_id']);
        });

        Schema::table('careers', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
        });

        Schema::table('mechanics', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
        });
    }
};
