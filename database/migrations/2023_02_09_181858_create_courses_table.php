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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');//->unique();
            $table->string('teacher');
            $table->string('faculty');
            //$table->longText('description')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('presenceChecks')->default(0);
            $table->string('allowedEmails')->nullable();     // endings of emails that students must have to see the course
            $table->boolean('isCurrentlyChecking')->default(0);
            $table->string('ipForChecking')->nullable();
            //$table->bigInteger('last_time_ip_check')->default(0);
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
        Schema::dropIfExists('courses');
    }
};
