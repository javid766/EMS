<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccbackdateRangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accbackdate_ranges', function (Blueprint $table) {
            $table->id();
            $table->string('moduleid',200);
            $table->string('monthname',200);
            $table->datetime('datefrom');
            $table->datetime('dateto');
            $table->integer('isclosed');
            $table->foreignId('financialyearid')->references('id')->on('accfinancial_year');
            $table->foreignId('companyid')->references('id')->on('setup_company');
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
        Schema::dropIfExists('accbackdate_ranges');
    }
}
