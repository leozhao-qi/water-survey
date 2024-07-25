<?php

use App\User;
use App\MoodleUser;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

// Because this operation is across two connections (MoodleUser and the regular database)
// It is NOT easily reversible.
class RemoveMoodleIdFromUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('fullname')->nullable();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
        });

        DB::connection("mysql")->table('users')->eachById(function ($user) {
            $moodle_user = DB::connection("mysql2")->table('mdl_user')->where('id', $user->moodle_id)->first();

            $firstname = (isset($moodle_user->firstname)) ? $moodle_user->firstname : null;
            $lastname = (isset($moodle_user->lastname)) ? $moodle_user->lastname : null;
            // NULL is always converted to an empty string during concatenation. Therefore if one of two is unset behaviour is to take one of first or last names.
            $fullname = (!empty($firstname) && !empty($lastname)) ? $firstname . ' ' . $lastname : null;

            DB::table('users')->where('id', $user->id)->update([
                'fullname' => $fullname,
                'firstname' => $firstname,
                'lastname' => $lastname
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('moodle_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('moodle_id');
            $table->dropColumn(['fullname','firstname','lastname']);
        });
    }
}
