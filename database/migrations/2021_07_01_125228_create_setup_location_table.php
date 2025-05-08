<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSetupLocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setup_location', function (Blueprint $table) {
            $table->id();
            $table->string('vcode', 50)->nullable();
            $table->string('vname', 200)->nullable();
            $table->foreignId('companyid')->references('id')->on('setup_company');
            $table->string('address', 200);
            $table->string('state', 200);
            $table->string('zip', 50);
            $table->integer('countryid');
            $table->string('telephone', 200);
            $table->string('tollfree', 50);
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
        Schema::dropIfExists('setup_location');
    }
}
