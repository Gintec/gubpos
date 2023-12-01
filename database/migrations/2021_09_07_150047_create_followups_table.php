<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFollowupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('followups', function (Blueprint $table) {
            $table->id();
            $table->string('title',70);
            $table->unsignedBigInteger('member')->index()->nullable();
            $table->foreign('member')->references('id')->on('users')->nullable();
            $table->date('date')->nullable();
            $table->string('type',30)->nullable();
            $table->text('discussion')->nullable();
            $table->string('nextaction',80)->nullable();
            $table->date('nextactiondate')->nullable();
            $table->string('status',30)->nullable();

            $table->unsignedBigInteger('assigned_to')->index()->nullable();
            $table->foreign('assigned_to')->references('id')->on('users')->nullable();

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
        Schema::dropIfExists('followups');
    }
}
