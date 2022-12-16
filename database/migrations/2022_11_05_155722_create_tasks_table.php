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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Event::class, 'event_id')->nullable();
            $table->foreignIdFor(\App\Models\Appointment::class, 'appointment_id')->nullable();
            $table->foreignIdFor(\App\Models\Content::class, 'content_id')->nullable();
            $table->string('title', 1000);
            $table->string('catergory', 1000);
            $table->text('description')->nullable();
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
        Schema::dropIfExists('tasks');
    }
};
