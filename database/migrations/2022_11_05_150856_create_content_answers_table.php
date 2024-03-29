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
        Schema::create('content_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Appointment::class, 'appointment_id')->nullable();
            $table->foreignIdFor(\App\Models\Task::class, 'task_id')->nullable();
            $table->foreignIdFor(\App\Models\User::class, 'user_id')->nullable();
            $table->foreignIdFor(\App\Models\Content::class, 'content_id')->nullable();
            $table->string('status', 200);
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
        Schema::dropIfExists('content_answers');
    }
};
