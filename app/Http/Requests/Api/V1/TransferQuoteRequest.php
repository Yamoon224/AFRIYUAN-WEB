<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class TransferQuoteRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'from_currency' => 'required|string|size:3|exists:currencies,code',
            'to_currency'   => 'required|string|size:3|exists:currencies,code|different:from_currency',
            'send_amount'   => 'required|numeric|min:1',
        ];
    }
}
