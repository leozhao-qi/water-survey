<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogbookPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logbook_packages', function (Blueprint $table) {
            $table->id();
            $table->integer('logbook_id')->unsigned();
            $table->integer('package_id')->unsigned();
            $table->timestamps();

            $table->foreign('logbook_id')
                ->references('id')
                ->on('logbooks')
                ->onDelete('cascade');

            $table->foreign('package_id')
                ->references('id')
                ->on('packages')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logbook_packages');
    }
}
