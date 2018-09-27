<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class UserSettingMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function run()
    {
        Capsule::schema()->create('user_settings', function($table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('setting_id');
            $table->tinyInteger('enabled')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('setting_id');
        });
    }
}
