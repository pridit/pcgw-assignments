<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class TokenMigration
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
            $table->unsignedInteger('used_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('used_by');
        });
    }
}
