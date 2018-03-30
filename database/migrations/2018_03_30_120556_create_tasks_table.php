<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('project_id')->unsigned();
            $table->integer('category_id')->unsigned();
            $table->integer('description_id')->unsigned()->nullable();
            $table->integer('time_needed')->unsigned()->nullable();
            $table->string('priority', 32);
            $table->string('status', 32);
            $table->string('summary', 128);
            $table->dateTime('start_time')->nullable();
            $table->dateTime('due_time')->nullable();
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
}
