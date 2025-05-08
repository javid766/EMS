<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccaccountOpeningTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accaccount_opening', function (Blueprint $table) {

            $table->id();
            $table->foreignId('accountid')->references('id')->on('accaccount_coa');
            $table->decimal('dr',18,4);
            $table->decimal('cr',18,4);
            $table->integer('currencyid');
            $table->integer('exchangeid');
            $table->decimal('exchangerate',18,4);
            $table->foreignId('financialyearid')->references('id')->on('accfinancial_year');
            $table->foreignId('companyid')->references('id')->on('setup_company');
            $table->foreignId('locationid')->references('id')->on('setup_location');
            $table->integer('projectid');
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
        Schema::dropIfExists('accaccount_opening');
    }
}
