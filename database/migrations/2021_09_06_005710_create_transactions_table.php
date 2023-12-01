<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->double('amount',10,2)->default(0)->nullable();
            $table->unsignedBigInteger('account_head')->index()->nullable();
            $table->foreign('account_head')->references('id')->on('accountheads')->onDelete('cascade');

            $table->dateTime('dated')->nullable();
            $table->string('reference_no',20)->nullable();
            $table->string('upload',50)->nullable();
            $table->string('detail',100)->nullable();
            $table->string('from',40)->nullable();
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('cascade');
            $table->string('to',40)->nullable();
            $table->unsignedBigInteger('approved_by')->index()->nullable();
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('recorded_by')->index()->nullable();
            $table->foreign('recorded_by')->references('id')->on('users')->onDelete('cascade');

            $table->string('payment_status',100)->nullable();
            $table->string('transaction_id',40)->nullable();
            $table->double('balance',10,2)->nullable();
            $table->double('vat',10,2)->default(0)->nullable();
            $table->double('discount',10,2)->default(0)->nullable();
            $table->string('payment_type',100)->nullable();
            $table->string('payment_particulars',40)->nullable();
            $table->string('beneficiary',40)->nullable();

            $table->unsignedBigInteger('setting_id')->index()->nullable();
            $table->foreign('setting_id')->references('id')->on('settings');

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
        Schema::dropIfExists('transactions');
    }
}
