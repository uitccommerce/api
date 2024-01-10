<?php

namespace Uitccommerce\Api\Http\Requests;

use Uitccommerce\Support\Http\Requests\Request;

class ForgotPasswordRequest extends Request
{
    public function rules(): array
    {
        return [
            'email' => 'required|email|string',
        ];
    }
}
