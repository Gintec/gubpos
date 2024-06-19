<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name',50);
            $table->string('gender',7)->nullable();
            $table->string('dob',20)->nullable();
            $table->string('category',15)->nullable();
            $table->string('phone_number',30)->nullable();
            $table->string('email',50)->nullable();
            $table->text('about',120)->nullable();
            $table->string('address',130)->nullable();
            $table->string('location',40)->nullable();
            $table->string('salary',50)->nullable();
            $table->string('status',30)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('role',30)->nullable();
            $table->unsignedBigInteger('setting_id')->index()->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        DB::table('users')->insert(
            array(
                'name' => 'ProdSales',
                'role' => 'Super',
                'email'=> 'admin@prodsales.com',
                'password'=>'$2y$10$dxyWNlqDIBLi2DWM8fFzbO76rNcHAQOz1GLQLTShUJikUF8dXccQq',
                // 'setting_id'=>1
            ));

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
