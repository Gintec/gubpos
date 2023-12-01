<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('business_name',70)->nullable();
            $table->string('motto',70)->nullable();
            $table->string('logo',70)->nullable();
            $table->string('address',200)->nullable();
            $table->string('background',70)->nullable();
            $table->string('mode',30)->nullable();
            $table->string('color',30)->nullable();
            $table->unsignedBigInteger('businessgroup_id')->index()->nullable();
            $table->foreign('businessgroup_id')->references('id')->on('businessgroups')->nullable();
            $table->unsignedBigInteger('user_id')->index()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->nullable();

            $table->timestamps();

        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('setting_id')->references('id')->on('settings');
        });

        DB::table('settings')->insert(
            array(
                'business_name' => 'ProdSales',
                'motto' => 'Production and Sales Management System',
                'logo' => 'logo-dark.png',
                'background' => 'login-bg.jpg',
                'mode' => 'Active',
                'address' => 'Business Address',
                'color' => '',
                'user_id' => 1,
                'businessgroup_id' => 1

            )
        );

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
