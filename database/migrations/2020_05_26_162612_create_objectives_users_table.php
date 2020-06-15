<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObjectivesUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('objective_user', function (Blueprint $table) {
            $table->id();
            $table->integer('objective_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->timestamps();

            // $table->foreign('objective_id')
            //     ->references('id')
            //     ->on('objectives')
            //     ->onDelete('cascade');

            // $table->foreign('user_id')
            //     ->references('id')
            //     ->on('users')
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
        Schema::dropIfExists('objectives_users');
    }
}
