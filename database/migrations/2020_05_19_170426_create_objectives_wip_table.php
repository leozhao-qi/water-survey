<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObjectivesWipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('objectives_wip', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('lesson_id');
            $table->string('number');
            $table->text('name');
            $table->string('type')->nullable();
            $table->timestamps();

            // $table->foreign('lesson_id')
            //     ->references('id')
            //     ->on('lessons')
            //     ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('objectives_wip');
    }
}
