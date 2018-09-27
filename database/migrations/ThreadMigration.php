<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class ThreadMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function run()
    {
        Capsule::schema()->create('threads', function($table) {
            $table->increments('id');
            $table->unsignedInteger('post_id')->nullable();
            $table->string('title', 191);
            $table->string('slug', 191);
            $table->tinyInteger('is_pinned')->default(0);
            $table->tinyInteger('is_closed')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->index('post_id');
        });
    }
}
