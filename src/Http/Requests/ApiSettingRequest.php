<?php

namespace Uitccommerce\Api\Http\Requests;

use Uitccommerce\Support\Http\Requests\Request;

class ApiSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'api_enabled' => 'nullable|in:0,1',
        ];
    }
}
