<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSetupCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setup_company', function (Blueprint $table) {
            $table->id();
            $table->string('vcode', 50);
            $table->string('vname', 200);
            $table->foreignId('tid')->references('id')->on('setup_tenant');
            $table->string('company_nature', 200);
            $table->string('url', 200)->nullable();
            $table->string('display_url', 200)->nullable();
            $table->string('logo', 200)->nullable();
            $table->string('signature_image', 200)->nullable();
            $table->string('timezone', 50)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('uan', 50)->nullable();
            $table->string('fax', 50)->nullable();
            $table->string('address', 100)->nullable();
            $table->string('city', 50)->nullable();
            $table->integer('countryid');
            $table->integer('currencyid');
            $table->integer('shipping_methodid')->default(0);
            $table->integer('havesaletax')->default(0);
            $table->decimal('saletaxper', 4, 2)->default(0);
            $table->integer('havevat')->default(0);
            $table->decimal('vatper', 4, 2)->default(0);
            $table->string('ntn_heading', 200)->nullable();
            $table->string('cnic_heading', 200)->nullable();
            $table->string('registrationno', 200)->nullable();
            $table->string('salestaxno', 200)->nullable();
            $table->string('sqldateformat', 50)->nullable()->default('');
            $table->string('compdateformat', 50)->nullable()->default('');
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
        Schema::dropIfExists('setup_company');
    }
}
