<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccaccountCoaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accaccount_coa', function (Blueprint $table) {
            
            $table->id();
            $table->string('vcode',50);
            $table->string('vname',200);
            $table->foreignId('accountgroupid')->references('id')->on('accaccount_group');
            $table->foreignId('accounttypeid')->references('id')->on('accaccount_type');
            $table->integer('parentid');
            $table->integer('levelnbr');
            $table->foreignId('companyid')->references('id')->on('setup_company');
            $table->foreignId('financialyearid')->references('id')->on('accfinancial_year');
            $table->integer('iscontrol');
            $table->integer('isdisallowtranz');
            $table->integer('issubsidaryaccount');
            $table->integer('isallowtranzbank');
            $table->integer('isallowallcompany');
            $table->integer('isanalysis');
            $table->integer('employee');
            $table->integer('customer');
            $table->integer('supplier');
            $table->integer('costcenter');
            $table->integer('workorder');
            $table->integer('lccode');
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
        Schema::dropIfExists('accaccount_coa');
    }
}
