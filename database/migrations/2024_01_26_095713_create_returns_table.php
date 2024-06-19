<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->references('id')->on('product_sales')->onDelete('cascade');

            $table->string('returnedby',40)->nullable();
            $table->double('quantity',10,2);
            $table->string('typeofreturn',20)->nullable();
            $table->date('date_returned',40)->nullable();
            $table->string('reason',100)->nullable();
            $table->string('returnedto',20)->nullable();
            $table->string('amount',40)->nullable();
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
        Schema::dropIfExists('returns');
    }
}
