<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccvoucherDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accvoucher_detail', function (Blueprint $table) {

            $table->id();
            $table->decimal('mid',18,4);
            $table->foreignId('accountid')->references('id')->on('accaccount_coa');
            $table->decimal('dr',18,4);
            $table->decimal('cr',18,4);
            $table->integer('currencyid');
            $table->integer('exchangeid');
            $table->decimal('exchangerate',18,4);
            $table->string('chequeno',250);
            $table->datetime('chequedate' )->nullable();
            $table->string('refno',200);
            $table->string('vname',500);
            $table->integer('priority');
            $table->integer('isposted');
            $table->datetime('posteddate' )->nullable();
            $table->integer('postedby');
            $table->foreignId('companyid')->references('id')->on('setup_company');
            $table->foreignId('locationid')->references('id')->on('setup_location');
            $table->integer('projectid');
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
        Schema::dropIfExists('accvoucher_detail');
    }
}
