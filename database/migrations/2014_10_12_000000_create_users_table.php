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
            $table->string('password');
            $table->string('phone')->nullable()->unique();
            $table->string('passport')->nullable()->unique();
            $table->date('date_born')->nullable();
            $table->string('location')->nullable();
            $table->string('description')->nullable();
            $table->string('parents_name')->nullable();
            $table->string('parents_tel')->unique()->nullable();
            $table->string('group_id')->nullable();
            $table->string('photo')->nullable();
            $table->string('should_pay')->nullable();
            $table->string('status')->nullable();
            $table->string('percent')->nullable();
            $table->foreignId('room_id')->nullable();
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
