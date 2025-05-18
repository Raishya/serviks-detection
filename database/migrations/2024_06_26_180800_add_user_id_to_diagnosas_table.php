<?php

// Migration file: add_user_id_to_diagnosas_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToDiagnosasTable extends Migration
{
    public function up()
    {
        Schema::table('diagnosas', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
        });
    }

    public function down()
    {
        Schema::table('diagnosas', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
}
