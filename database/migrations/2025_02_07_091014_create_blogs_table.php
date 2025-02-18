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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->unsignedBigInteger('blog_category_id');
            $table->string('image');
            $table->string('image_alt')->nullable();
            $table->string('index_image')->nullable();
            $table->string('index_image_alt')->nullable();
            $table->boolean('showed');
            $table->boolean('show_at_home');

            $table->string('title');
            $table->longText('introduction');
            $table->longText('content_table');
            $table->longText('first_paragraph');
            $table->longText('description');
            $table->string('author_name');
            $table->string('author_title');
            $table->string('author_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
