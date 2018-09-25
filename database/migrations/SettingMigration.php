<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class SettingMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function run()
    {
        Capsule::schema()->create('settings', function($table) {
            $table->increments('id');
            $table->unsignedInteger('type_id');
            $table->string('key');
            $table->string('name');

            $table->timestamps();
            $table->softDeletes();

            $table->index('type_id');
        });
    }
}
