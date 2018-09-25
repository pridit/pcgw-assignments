<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class UserMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function run()
    {
        Capsule::schema()->create('users', function($table) {
            $table->increments('id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('username')->nullable();
            $table->string('password');
            $table->string('email')->unique();
            $table->tinyInteger('disabled')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }
}
