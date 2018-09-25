<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class PostMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function run()
    {
        Capsule::schema()->create('posts', function($table) {
            $table->increments('id');
            $table->unsignedInteger('thread_id');
            $table->unsignedInteger('user_id')->nullable();
            $table->string('author', 191)->nullable();
            $table->text('body');

            $table->timestamps();
            $table->softDeletes();

            $table->index('thread_id');
            $table->index('user_id');
        });
    }
}
