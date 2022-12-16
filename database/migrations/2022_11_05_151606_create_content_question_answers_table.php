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
        Schema::create('content_question_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\ContentQuestion::class, 'content_question_id');
            $table->foreignIdFor(\App\Models\ContentAnswer::class, 'content_answer_id');
            $table->text('answer');
            $table->text('comment')->nullable();
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
        Schema::dropIfExists('content_question_answers');
    }
};
