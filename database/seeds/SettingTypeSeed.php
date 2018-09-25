<?php

use App\SettingType;

class SettingTypeSeed
{
    /**
     * Run the seed.
     *
     * @return void
     */
    function run()
    {
        $settingType = new SettingType;
        $settingType->name = "general";
        $settingType->save();

        $settingType = new SettingType;
        $settingType->name = "notifications";
        $settingType->save();
    }
}
