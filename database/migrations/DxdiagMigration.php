<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class DxdiagMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function run()
    {
        Capsule::schema()->create('dxdiags', function($table) {
            $table->increments('id');
            $table->unsignedInteger('applicant_id');
            $table->string('os', 191)->nullable();
            $table->string('cpu', 191)->nullable();
            $table->string('gpu', 191)->nullable();
            $table->string('vram', 191)->nullable();
            $table->string('ram', 191)->nullable();
            $table->string('directx', 191)->nullable();
            $table->string('resolution', 191)->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('applicant_id');
        });
    }
}
