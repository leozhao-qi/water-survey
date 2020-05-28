<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCommentAndEvaluationDetailsLastUpdatedByColumnsOnPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->integer('commented_by')->unsigned()->nullable();
            $table->date('commented_at')->nullable();
            $table->integer('evaluated_by')->unsigned()->nullable();
            $table->date('evaluated_at')->nullable();
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
            $table->dropColumn('commented_by');
            $table->dropColumn('commented_at');
            $table->dropColumn('evaluated_by');
            $table->dropColumn('evaluated_at');
        });
    }
}
