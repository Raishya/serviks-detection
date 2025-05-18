<?php

// Migration file: add_foreign_key_to_user_id_in_diagnosas_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToUserIdInDiagnosasTable extends Migration
{
    public function up()
    {
        Schema::table('diagnosas', function (Blueprint $table) {
            // Menambahkan foreign key constraint pada kolom user_id
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('diagnosas', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
    }
}
