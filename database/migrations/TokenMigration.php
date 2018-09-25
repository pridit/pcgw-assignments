<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class CreateTokenMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function run()
    {
        Capsule::schema()->create('tokens', function($table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('hash');
            $table->unsignedInteger('used_by');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'used_by']);
        });
    }
}
