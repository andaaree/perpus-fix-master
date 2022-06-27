<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempVidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_vids', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('judul',100)->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('nama_pembuat')->nullable();
            $table->string('thumbnail',8)->nullable();
            $table->string('jenjang',100)->nullable();
            $table->string('kelas',100)->nullable();
            $table->string('jurusan',100)->nullable();
            $table->string('mapel',100)->nullable();
            $table->string('filename')->nullable();
            $table->char('filetype',3)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temp_vids');
    }
}
