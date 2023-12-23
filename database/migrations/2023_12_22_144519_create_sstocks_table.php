<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSstocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sstocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sparepart');
            $table->foreign('sparepart')->references('id')->on('spareparts');
            $table->double('quantity',10,2)->nullable();
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
        Schema::dropIfExists('sstocks');
    }
}
