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
        if (!Schema::hasTable('media')) {
            Schema::create('media', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('path')->default('media');
                $table->string('mime');
                $table->unsignedBigInteger('size'); // File size in bytes
                $table->unsignedInteger('width');
                $table->unsignedInteger('height');
                $table->unsignedBigInteger('folder_id')->nullable();
                $table->string('title')->nullable();
                $table->string('alt')->nullable();
                $table->text('caption')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
            
            // Add foreign key constraint after table creation
            if (Schema::hasTable('media_folders')) {
                Schema::table('media', function (Blueprint $table) {
                    $table->foreign('folder_id')
                          ->references('id')
                          ->on('media_folders')
                          ->onDelete('set null');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
