<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class SettingTypeMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function run()
    {
        Capsule::schema()->create('setting_types', function($table) {
            $table->increments('id');
            $table->string('name', 191);

            $table->timestamps();
            $table->softDeletes();
        });
    }
}
