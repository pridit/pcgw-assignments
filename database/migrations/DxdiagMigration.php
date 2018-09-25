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
            $table->string('os')->nullable();
            $table->string('cpu')->nullable();
            $table->string('gpu')->nullable();
            $table->string('vram')->nullable();
            $table->string('ram')->nullable();
            $table->string('directx')->nullable();
            $table->string('resolution')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('applicant_id');
        });
    }
}
