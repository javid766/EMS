<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccvoucherMainTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accvoucher_main', function (Blueprint $table) {
            
            $table->id();
            $table->string('vno',50);
            $table->string('vtype',50);
            $table->datetime('vdate');
            $table->string('chequeno',200);
            $table->datetime('chequedate');
            $table->string('refno',200);
            $table->string('vname',500);
            $table->integer('currencyid');
            $table->integer('exchangeid');
            $table->decimal('exchangerate',18,4);
            $table->integer('orderid');
            $table->integer('isautoreversal');
            $table->datetime('reversaldate');
            $table->integer('reversalby');
            $table->integer('isreoccurring');
            $table->datetime('reoccurringdate');
            $table->integer('reoccurringby');
            $table->integer('isposted');
            $table->datetime('posteddate')->nullable();
            $table->integer('postedby');
            $table->integer('iscanceled');
            $table->datetime('canceleddate');
            $table->integer('canceledby');
            $table->integer('isapproved');
            $table->datetime('approveddate');
            $table->integer('approvedby');
            $table->integer('isdeleted');
            $table->datetime('deleteddate');
            $table->integer('deletedby');
            $table->foreignId('financialyearid')->references('id')->on('accfinancial_year');
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
        Schema::dropIfExists('accvoucher_main');
    }
}
