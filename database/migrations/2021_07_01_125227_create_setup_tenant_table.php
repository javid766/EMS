<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSetupTenantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setup_tenant', function (Blueprint $table) {
            $table->id();
            $table->string('vcode', 50)->nullable();
            $table->string('vname', 200)->nullable();
            $table->string('company_nature', 200)->nullable();
            $table->string('url', 200)->nullable();
            $table->string('tlogin', 50);
            $table->string('tpassword', 50);
            $table->datetime('logindate')->nullable();
            $table->integer('tisactive');
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
        Schema::dropIfExists('setup_tenant');
    }
}
