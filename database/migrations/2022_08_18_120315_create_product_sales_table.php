<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_sales', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('group_id',30)->index()->nullable();
            $table->foreign('group_id')->references('reference_no')->on('transactions')->onDelete('cascade');


            $table->unsignedBigInteger('product_id')->index()->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');


            $table->double('quantity',10,2)->nullable();

            $table->unsignedBigInteger('sales_person')->index()->nullable();
            $table->foreign('sales_person')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('confirmed_by')->index()->nullable();
            $table->foreign('confirmed_by')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('buyer')->index()->nullable();
            $table->foreign('buyer')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('setting_id')->index()->nullable();
            $table->foreign('setting_id')->references('id')->on('settings');

            $table->double('price',10,2)->nullable();
            $table->double('amount_paid',10,2)->default(0.00)->nullable();
            $table->string('pay_status',10)->default(0.00)->nullable();
            $table->string('details',50)->nullable();
            $table->dateTime('dated_sold')->nullable();

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
        Schema::dropIfExists('product_sales');
    }
}
