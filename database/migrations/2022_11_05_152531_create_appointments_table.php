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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Event::class, 'event_id');
            $table->foreignIdFor(\App\Models\User::class, 'user_id');
            $table->text('reason');
            $table->string('status',100);
            $table->date('start_date', $precision = 0)->nullable();
            $table->date('end_date', $precision = 0)->nullable();
            $table->time('start_time', $precision = 0)->nullable();
            $table->time('end_time', $precision = 0)->nullable();
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
        Schema::dropIfExists('appointments');
    }
};
