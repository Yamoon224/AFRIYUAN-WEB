<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'first_name'        => 'required|string|max:100',
            'last_name'         => 'required|string|max:100',
            'email'             => 'required|email|unique:users,email',
            'phone_number'      => 'required|string|max:20|unique:users,phone_number',
            'phone_country_code' => 'required|string|max:10',
            'country_id'        => 'required|exists:countries,id',
            'date_of_birth'     => 'required|date|before:-18 years',
            'nationality'       => 'required|string|max:100',
            'password'          => 'required|string|min:8|confirmed',
        ];
    }
}
