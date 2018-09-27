<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class AssignMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function run()
    {
        Capsule::schema()->create('assigns', function($table) {
            $table->increments('id');
            $table->unsignedInteger('assignment_id');
            $table->unsignedInteger('applicant_id');
            $table->unsignedInteger('assigned_by')->nullable();

            $table->timestamps();

            $table->index('assignment_id');
            $table->index('applicant_id');
            $table->index('assigned_by');
        });
    }
}
