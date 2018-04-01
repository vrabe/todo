<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('task_id')->unsigned();
            $table->string('field_name', 64);
            $table->string('old_value', 255);
            $table->string('new_value', 255);
            $table->smallInteger('type');
        });

        Schema::table('task_histories', function(Blueprint $table) {
      			$table->foreign('task_id')->references('id')->on('tasks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task_histories');
    }
}
