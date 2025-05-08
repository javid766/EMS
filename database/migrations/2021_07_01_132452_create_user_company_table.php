<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_company', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userid')->references('id')->on('users');
            $table->foreignId('companyid')->references('id')->on('setup_company');
            $table->foreignId('tid')->references('id')->on('setup_tenant');
            $table->string('soudu_name', 200)->nullable();
            $table->string('soudu_email', 200)->nullable();
            $table->string('soudu_phoneno', 200)->nullable();
            $table->integer('isdefault')->nullable();
            $table->integer('isactive')->default(0);
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
        Schema::dropIfExists('user_company');
    }
}
