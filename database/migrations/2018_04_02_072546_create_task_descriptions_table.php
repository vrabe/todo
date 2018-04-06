<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskDescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_descriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->text('description');
            $table->integer('task_id')->unsigned()->nullable()->unique();
        });

        Schema::table('task_descriptions', function(Blueprint $table) {
  			$table->foreign('task_id')->references('id')->on('tasks')
  						->onDelete('cascade')
  						->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task_descriptions');
    }
}
