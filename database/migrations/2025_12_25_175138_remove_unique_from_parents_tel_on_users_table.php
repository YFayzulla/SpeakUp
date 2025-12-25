<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUniqueFromParentsTelOnUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // drop the unique index by name (as seen in your error log)
            $table->dropUnique('users_parents_tel_unique');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unique('parents_tel');
        });
    }
}