<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class ApplicationMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function run()
    {
        Capsule::schema()->create('applications', function($table) {
            $table->increments('id');
            $table->unsignedInteger('assignment_id');
            $table->unsignedInteger('applicant_id');
            $table->text('answer_aspects');
            $table->text('answer_standard')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('assignment_id');
            $table->index('applicant_id');
        });
    }
}
