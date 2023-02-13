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
            $table->boolean('isTeacher'); // teacher or student
            $table->string('name')->nullable();
            $table->string('email')->unique();//->nullable();
            //$table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('ip_addresses')->nullable();
            $table->string('verificationCode');
            $table->integer('verified')->default(0);
            $table->integer('screwUps')->default(0);
            $table->integer('presences')->default(0);
            //$table->string('faculty')->nullable();
            //$table->longText('description')->nullable();
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
