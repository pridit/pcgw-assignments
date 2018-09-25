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
            $table->string('first_name', 191)->nullable();
            $table->string('last_name', 191)->nullable();
            $table->string('username', 191)->nullable();
            $table->string('password', 191);
            $table->string('email', 191)->unique();
            $table->tinyInteger('disabled')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }
}
