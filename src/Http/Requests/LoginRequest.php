<?php

namespace Uitccommerce\Api\Http\Requests;

use Uitccommerce\Support\Http\Requests\Request;

class LoginRequest extends Request
{
    public function rules(): array
    {
        return [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ];
    }
}
