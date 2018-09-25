<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class UserHashMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function run()
    {
        Capsule::schema()->create('user_hash', function($table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('type', 191);
            $table->string('hash', 191);
            $table->dateTime('expire_at');

            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
        });
    }
}
