<?php

namespace Uitccommerce\Api\Http\Controllers;

use Uitccommerce\Api\Http\Requests\ApiSettingRequest;
use Uitccommerce\Base\Facades\Assets;
use Uitccommerce\Base\Facades\PageTitle;
use Uitccommerce\Base\Http\Responses\BaseHttpResponse;
use Illuminate\Routing\Controller;

class ApiController extends Controller
{
    public function settings()
    {
        PageTitle::setTitle(trans('packages/api::api.settings'));

        Assets::addScriptsDirectly('vendor/core/core/setting/js/setting.js');
        Assets::addStylesDirectly('vendor/core/core/setting/css/setting.css');

        return view('packages/api::settings');
    }

    public function storeSettings(ApiSettingRequest $request, BaseHttpResponse $response)
    {
        $this->saveSettings($request->except([
            '_token',
        ]));

        return $response
            ->setPreviousUrl(route('api.settings'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    protected function saveSettings(array $data)
    {
        foreach ($data as $settingKey => $settingValue) {
            if (is_array($settingValue)) {
                $settingValue = json_encode(array_filter($settingValue));
            }

            setting()->set($settingKey, (string)$settingValue);
        }

        setting()->save();
    }
}
