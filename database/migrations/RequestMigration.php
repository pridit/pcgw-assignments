<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class RequestMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function run()
    {
        Capsule::schema()->create('requests', function($table) {
            $table->increments('id');
            $table->string('title');
            $table->string('developer')->nullable();
            $table->string('publisher')->nullable();
            $table->date('release_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }
}
