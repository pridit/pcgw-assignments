<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class AssignmentMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function run()
    {
        Capsule::schema()->create('assignments', function($table) {
            $table->increments('id');
            $table->string('title', 191);
            $table->string('slug', 191);
            $table->integer('app_id')->nullable();
            $table->string('url', 191)->nullable();
            $table->string('path_report', 191)->nullable();
            $table->tinyInteger('report')->default(0);
            $table->tinyInteger('standard')->default(0);
            $table->tinyInteger('open')->default(1);
            $table->date('release_at')->nullable();
            $table->date('expire_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }
}
