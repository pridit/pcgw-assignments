<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class ApplicantMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function run()
    {
        Capsule::schema()->create('applicants', function($table) {
            $table->increments('id');
            $table->string('name', 191);
            $table->tinyInteger('blacklisted')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }
}
