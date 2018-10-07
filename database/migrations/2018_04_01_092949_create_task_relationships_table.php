<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskRelationshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_relationships', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('source_task_id')->unsigned();
            $table->integer('destination_task_id')->unsigned();
            $table->smallInteger('relationship_type');
        });

        Schema::table('task_relationships', function (Blueprint $table) {
            $table->foreign('source_task_id')->references('id')->on('tasks')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('destination_task_id')->references('id')->on('tasks')
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
        Schema::dropIfExists('task_relationships');
    }
}
