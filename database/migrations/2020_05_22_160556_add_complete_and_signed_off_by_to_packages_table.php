<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompleteAndSignedOffByToPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->smallInteger('complete')->unsigned()->default(0);
            $table->integer('signed_off_by')->unsigned()->nullable();
            $table->date('signed_off_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('complete');
            $table->dropColumn('signed_off_by');
            $table->dropColumn('signed_off_at');
        });
    }
}
