<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');

        if (empty($tableNames)) {
            throw new \Exception('Error: config/permission.php not found and defaults could not be merged. Please publish the package configuration before proceeding.');
        }

        Schema::create($tableNames['permissions'], function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 200);
            // $table->string('srlno', 50);
            // $table->string('menuname', 200);
            // $table->integer('menulevel');
            // $table->integer('parentid');
            // $table->string('routing_pageurl', 500);
            // $table->string('icon', 100);
            // $table->string('menupage', 500);
            $table->string('guard_name');
            $table->integer('isactive')->default(0);
            $table->integer('insertedby')->nullable();
            $table->datetime('inserteddate')->nullable();
            $table->string('insertedip', 200)->nullable();
            $table->integer('updatedby')->nullable();
            $table->datetime('updateddate')->nullable();
            $table->string('updatedip', 200)->nullable();
            $table->timestamps();

            // $table->primary(['id'],
            //         'permissions_permission_primary');
        });

        Schema::create($tableNames['roles'], function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name');
            $table->integer('tid');
            $table->string('guard_name');
            $table->integer('isactive')->default(0);
            $table->integer('insertedby')->nullable();
            $table->datetime('inserteddate')->nullable();
            $table->string('insertedip', 200)->nullable();
            $table->integer('updatedby')->nullable();
            $table->datetime('updateddate')->nullable();
            $table->string('updatedip', 200)->nullable();
            $table->timestamps();

            // $table->primary(['id'],
            //         'roles_role_primary');
        });

        Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->id();
            $table->integer('permission_id');
            $table->string('model_type');

            // $columnNames['model_morph_key'] -> model_id = user_id
            $table->integer($columnNames['model_morph_key']);

            // $table->integer('isview');
            // $table->integer('isinsert');
            // $table->integer('isupdate');
            // $table->integer('isdelete');
            // $table->integer('isbackdate');
            // $table->integer('isprint');
            // $table->integer('includeexclude');
            $table->integer('isactive')->default(0);
            $table->integer('insertedby')->nullable();
            $table->datetime('inserteddate')->nullable();
            $table->string('insertedip', 200)->nullable();
            $table->integer('updatedby')->nullable();
            $table->datetime('updateddate')->nullable();
            $table->string('updatedip', 200)->nullable();

            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_permissions_model_id_model_type_index');

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            // $table->primary(['permission_id', $columnNames['model_morph_key'], 'model_type'],
            //         'model_has_permissions_permission_model_type_primary');
        });

        Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->id();
            $table->integer('role_id');
            $table->string('model_type');
            
            // $columnNames['model_morph_key'] -> model_id = user_id
            $table->integer($columnNames['model_morph_key']); 
            $table->integer('isactive')->default(0);
            $table->integer('insertedby')->nullable();
            $table->datetime('inserteddate')->nullable();
            $table->string('insertedip', 200)->nullable();
            $table->integer('updatedby')->nullable();
            $table->datetime('updateddate')->nullable();
            $table->string('updatedip', 200)->nullable();

            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_roles_model_id_model_type_index');

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            // $table->primary(['role_id', $columnNames['model_morph_key'], 'model_type'],
            //         'model_has_roles_role_model_type_primary');
        });

        Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames) {
            $table->id();
            $table->integer('permission_id');
            $table->integer('role_id');
            // $table->integer('isview');
            // $table->integer('isinsert');
            // $table->integer('isupdate');
            // $table->integer('isdelete');
            // $table->integer('isbackdate');
            // $table->integer('isprint');
            $table->integer('isactive')->default(0);
            $table->integer('insertedby')->nullable();
            $table->datetime('inserteddate')->nullable();
            $table->string('insertedip', 200)->nullable();
            $table->integer('updatedby')->nullable();
            $table->datetime('updateddate')->nullable();
            $table->string('updatedip', 200)->nullable();

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            // $table->primary(['permission_id', 'role_id'], 'role_has_permissions_permission_id_role_id_primary');
        });

        app('cache')
            ->store(config('permission.cache.store') != 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tableNames = config('permission.table_names');

        if (empty($tableNames)) {
            throw new \Exception('Error: config/permission.php not found and defaults could not be merged. Please publish the package configuration before proceeding, or drop the tables manually.');
        }

        Schema::drop($tableNames['role_has_permissions']);
        Schema::drop($tableNames['model_has_roles']);
        Schema::drop($tableNames['model_has_permissions']);
        Schema::drop($tableNames['roles']);
        Schema::drop($tableNames['permissions']);
    }
}
