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
        Schema::create('videos', function(Blueprint $table){
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->integer('duration'); //seconds
            //$table->longText('description')->nullable();
            $table->integer('views');
            $table->string('path');
            //$table->string('videoImagePath');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            //$table->set('genre', ['music', 'entertainment', 'education'])->nullable();
            //$table->string('genre');
            $table->integer('duration')->nullable();
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
        //
    }
};
