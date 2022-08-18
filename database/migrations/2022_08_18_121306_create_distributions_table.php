<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistributionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distributions', function (Blueprint $table) {
            $table->id();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->double('quantity',10,2)->default(0.00)->nullable();
            $table->foreign('distributor_id')->references('id')->on('distributors');
            $table->foreign('comfirmed_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('setting_id')->references('id')->on('settings');
            $table->double('price',10,2)->nullable();
            $table->double('amount_returned',10,2)->default(0.00)->nullable();
            $table->string('quantity_sold',10)->default(0.00)->nullable();
            $table->string('details',50)->nullable();
            $table->dateTime('dated_sold')->nullable();
            $table->string('distribution_batch',30)->nullable();
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
        Schema::dropIfExists('distributions');
    }
}
