<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'uuid'               => $this->uuid,
            'first_name'         => $this->first_name,
            'last_name'          => $this->last_name,
            'full_name'          => $this->full_name,
            'email'              => $this->email,
            'phone_number'       => $this->phone_number,
            'phone_country_code' => $this->phone_country_code,
            'country'            => $this->whenLoaded('country', fn () => [
                'id'       => $this->country->id,
                'name'     => $this->country->name,
                'iso_code' => $this->country->iso_code,
                'flag_url' => $this->country->flag_url,
            ]),
            'kyc_status'         => $this->kyc_status,
            'kyc_level'          => $this->kyc_level,
            'account_status'     => $this->account_status,
            'preferred_language' => $this->preferred_language,
            'profile_photo_url'  => $this->profile_photo_url,
            'email_verified'     => !is_null($this->email_verified_at),
            'phone_verified'     => !is_null($this->phone_verified_at),
            'created_at'         => $this->created_at?->toISOString(),
        ];
    }
}
