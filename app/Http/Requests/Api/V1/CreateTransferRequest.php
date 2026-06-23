<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class CreateTransferRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'from_currency'    => 'required|string|size:3|exists:currencies,code',
            'to_currency'      => 'required|string|size:3|exists:currencies,code|different:from_currency',
            'send_amount'      => 'required|numeric|min:1',
            'beneficiary_id'   => 'required|exists:beneficiaries,id',
            'payment_method'   => 'required|in:card,mobile_money,bank_transfer',
            'receive_method'   => 'required|in:bank_transfer,alipay,wechat_pay,cash_pickup,mobile_money',
            'payment_method_id' => 'required_if:payment_method,card|string|nullable',
        ];
    }
}
