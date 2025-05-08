<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccaccountCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accaccount_category', function (Blueprint $table) {
            
            $table->id();
            $table->string('vcode', 50);
            $table->string('vname', 200);
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
        Schema::dropIfExists('accaccount_category');
    }
}
