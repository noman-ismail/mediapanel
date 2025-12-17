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
        if (!Schema::hasTable('media_folders')) {
            Schema::create('media_folders', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->timestamps();
            });
            
            // Add foreign key constraint after table creation
            Schema::table('media_folders', function (Blueprint $table) {
                $table->foreign('parent_id')
                      ->references('id')
                      ->on('media_folders')
                      ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_folders');
    }
};
