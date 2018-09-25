<?php

namespace App\Http\Controllers\User;

use App\Setting;
use App\SettingType;
use App\UserSetting;

use Slim\Http\Request;
use Slim\Http\Response;

use App\Http\Controllers\BaseController;

class SettingsController extends BaseController
{
    public function index(Request $request, Response $response, $args)
    {
        $options = Setting::with(['userSetting' => function ($query) {
            return $query->where('user_id', $this->session->get('user')->id);
        }])->get();

        $this->flash->addMessage('settings', [
            'options' => $options,
            'types' => SettingType::all()
        ]);

        return $response->withHeader('Location', $request->getHeader('HTTP_REFERER'));
    }

    public function update(Request $request, Response $response, $args)
    {
        foreach ($request->getParams() as $key => $param) {
            $user = $this->session->get('user');
            $setting = Setting::where('key', $key)->first();

            if ($setting) {
                UserSetting::updateOrCreate(['user_id' => $user->id, 'setting_id' => $setting->id], [
                    'user_id' => $user->id,
                    'setting_id' => $setting->id,
                    'enabled' => $param
                ]);
            }
        }

        $this->flash->addMessage('alert', ['success', 'Success', 'Your settings have been updated.']);

        return $response->withHeader('Location', $request->getHeader('HTTP_REFERER'));
    }
}
