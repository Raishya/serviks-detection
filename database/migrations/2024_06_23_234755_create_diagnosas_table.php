<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiagnosasTable extends Migration
{
    public function up()
    {
        Schema::create('diagnosas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->integer('usia');
            $table->date('tanggal_pemeriksaan');
            $table->string('jenis_pemeriksaan');
            $table->string('image_path');
            $table->string('prediction');
            $table->decimal('confidence', 5, 2);
            $table->text('diagnosa');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('diagnosas');
    }
}
