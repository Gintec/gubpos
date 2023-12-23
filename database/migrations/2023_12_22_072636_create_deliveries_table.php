<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer');
            $table->foreign('customer')->references('id')->on('users');
            $table->unsignedBigInteger('invoice_no');
            $table->foreign('invoice_no')->references('id')->on('transactions')->onDelete('cascade');
            $table->double('amount',10,2)->nullable();
            $table->string('details',30)->nullable();
            $table->string('delivery_address',100)->nullable();
            $table->date('delivery_date',30)->nullable();
            $table->string('deliveredBy',30)->nullable();
            $table->string('status',30)->nullable();
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
        Schema::dropIfExists('deliveries');
    }
}
