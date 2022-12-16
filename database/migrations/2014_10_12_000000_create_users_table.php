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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('first_name', 255)->nullable();
            $table->string('last_name', 255)->nullable();;
            $table->date('birthday')->nullable();
            $table->string('gender', 100)->nullable();
            $table->string('relationship_status', 100)->nullable();
            $table->string('contact_number', 100)->nullable();
            $table->string('image', 1000)->nullable();
            $table->string('file', 1000)->nullable();
            $table->string('professional_title', 1000)->nullable();
            $table->text('professional_description')->nullable();
            $table->string('professional_status', 100)->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
