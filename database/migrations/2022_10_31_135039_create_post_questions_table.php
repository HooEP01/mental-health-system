<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Post::class, 'post_id');
            $table->string('type', 100);
            $table->string('question', 2000);
            $table->text('description')->nullable();
            $table->text('data')->nullable();
            $table->string('image', 255)->nullable();
            $table->string('audio', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_questions');
    }
};
