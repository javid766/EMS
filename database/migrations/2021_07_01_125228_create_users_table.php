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
            $table->decimal('empid', 18, 0);
            $table->foreignId('tid')->references('id')->on('setup_tenant');
            $table->string('name', 250);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->integer('allowaudit');
            $table->integer('allowactual');
            $table->integer('isadmin');
            $table->integer('usertype');
            $table->integer('isindividual')->nullable();
            $table->integer('default_companyid');
            $table->integer('default_financialyearid');
            $table->string('usersec_flage', 50);
            $table->string('macaddress', 500);
            $table->string('computer_name', 50);
            $table->string('window_loginname', 50);
            $table->integer('isactive');
            $table->integer('insertedby')->nullable();
            $table->datetime('inserteddate')->nullable();
            $table->string('insertedip', 200)->nullable();
            $table->integer('updatedby')->nullable();
            $table->datetime('updateddate')->nullable();
            $table->string('updatedip', 200)->nullable();
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
        Schema::dropIfExists('users');
    }
}
