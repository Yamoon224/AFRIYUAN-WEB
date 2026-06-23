<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class BeneficiaryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nickname'             => 'required|string|max:100',
            'first_name'           => 'required|string|max:100',
            'last_name'            => 'required|string|max:100',
            'phone_number'         => 'nullable|string|max:20',
            'email'                => 'nullable|email',
            'country_id'           => 'required|exists:countries,id',
            'currency_id'          => 'required|exists:currencies,id',
            'receive_method'       => 'required|in:bank_transfer,alipay,wechat_pay,cash_pickup,mobile_money',
            'beneficiary_type'     => 'required|in:china,africa',
            // Bank fields
            'bank_name'            => 'required_if:receive_method,bank_transfer|nullable|string|max:150',
            'bank_account_number'  => 'required_if:receive_method,bank_transfer|nullable|string|max:100',
            'bank_swift_code'      => 'nullable|string|max:20',
            'bank_branch'          => 'nullable|string|max:150',
            // Digital wallet
            'digital_wallet_id'    => 'required_if:receive_method,alipay,wechat_pay|nullable|string|max:100',
            'digital_wallet_type'  => 'required_if:receive_method,alipay,wechat_pay|nullable|in:alipay,wechat_pay',
        ];
    }
}
