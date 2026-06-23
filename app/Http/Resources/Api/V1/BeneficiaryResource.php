<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BeneficiaryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'nickname'         => $this->nickname,
            'full_name'        => $this->first_name . ' ' . $this->last_name,
            'first_name'       => $this->first_name,
            'last_name'        => $this->last_name,
            'phone_number'     => $this->phone_number,
            'email'            => $this->email,
            'beneficiary_type' => $this->beneficiary_type,
            'receive_method'   => $this->receive_method,
            'country'          => $this->whenLoaded('country', fn () => [
                'id'       => $this->country->id,
                'name'     => $this->country->name,
                'iso_code' => $this->country->iso_code,
                'flag_url' => $this->country->flag_url,
            ]),
            'currency'         => $this->whenLoaded('currency', fn () => [
                'code'   => $this->currency->code,
                'symbol' => $this->currency->symbol,
                'name'   => $this->currency->name,
            ]),
            // Show bank/wallet info based on method
            'bank_name'           => $this->when($this->receive_method === 'bank_transfer', $this->bank_name),
            'bank_account_number' => $this->when($this->receive_method === 'bank_transfer', $this->bank_account_number),
            'bank_swift_code'     => $this->when($this->receive_method === 'bank_transfer', $this->bank_swift_code),
            'digital_wallet_id'   => $this->when(
                in_array($this->receive_method, ['alipay', 'wechat_pay']),
                $this->digital_wallet_id
            ),
            'digital_wallet_type' => $this->when(
                in_array($this->receive_method, ['alipay', 'wechat_pay']),
                $this->digital_wallet_type
            ),
            'is_verified'      => $this->is_verified,
            'is_active'        => $this->is_active,
            'created_at'       => $this->created_at?->toISOString(),
        ];
    }
}
