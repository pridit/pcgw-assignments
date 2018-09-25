<?php

use App\Setting;

class SettingSeed
{
    /**
     * Run the seed.
     *
     * @return void
     */
    function run()
    {
        $setting = new Setting;
        $setting->type_id = 2;
        $setting->key = "new-application";
        $setting->name = "New applications";
        $setting->save();

        $setting = new Setting;
        $setting->type_id = 2;
        $setting->key = "new-request";
        $setting->name = "New requests";
        $setting->save();

        $setting = new Setting;
        $setting->type_id = 2;
        $setting->key = 'new-thread';
        $setting->name = "New threads";
        $setting->save();

        $setting = new Setting;
        $setting->type_id = 2;
        $setting->key = 'new-post';
        $setting->name = "New posts to threads";
        $setting->save();

        $setting = new Setting;
        $setting->type_id = 1;
        $setting->key = 'send-discord';
        $setting->name = "Send to Discord after assignment creation";
        $setting->save();
    }
}
