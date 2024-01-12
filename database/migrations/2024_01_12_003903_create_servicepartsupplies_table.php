<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicepartsuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicepartsupplies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('part_id');
            $table->foreign('part_id')->references('id')->on('spareparts')->onDelete('cascade');
            $table->double('quantity',10,2)->nullable();
            $table->unsignedBigInteger('supplier')->nullable;;
            $table->foreign('supplier')->references('id')->on('suppliers')->onDelete('cascade');
            $table->date('date_supplied')->nullable();
            $table->string('batchno',50)->nullable();
            $table->string('recorded_by',50)->nullable();
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
        Schema::dropIfExists('servicepartsupplies');
    }
}
