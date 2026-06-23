<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'uuid'             => $this->uuid,
            'reference_number' => $this->reference_number,
            'direction'        => $this->direction,
            'direction_label'  => $this->direction === 'africa_to_china' ? 'Africa → China' : 'China → Africa',

            // Amounts
            'send_amount'         => (float) $this->send_amount,
            'send_currency'       => $this->send_currency,
            'send_currency_symbol' => $this->send_currency_symbol,
            'fee_amount'          => (float) $this->fee_amount,
            'fee_currency'        => $this->fee_currency,
            'total_debit_amount'  => (float) $this->total_debit_amount,
            'receive_amount'      => (float) $this->receive_amount,
            'receive_currency'    => $this->receive_currency,
            'applied_rate'        => (float) $this->applied_rate,

            // Methods
            'payment_method' => $this->payment_method,
            'receive_method' => $this->receive_method,

            // Status
            'status'            => $this->status,
            'status_label'      => $this->statusLabel(),
            'compliance_status' => $this->compliance_status,
            'failure_reason'    => $this->failure_reason,

            // Relations
            'beneficiary' => $this->whenLoaded('beneficiary', fn () => [
                'id'           => $this->beneficiary->id,
                'nickname'     => $this->beneficiary->nickname,
                'full_name'    => $this->beneficiary->first_name . ' ' . $this->beneficiary->last_name,
                'receive_method' => $this->beneficiary->receive_method,
            ]),

            // Timestamps
            'initiated_at'         => $this->initiated_at?->toISOString(),
            'payment_confirmed_at' => $this->payment_confirmed_at?->toISOString(),
            'completed_at'         => $this->completed_at?->toISOString(),
            'created_at'           => $this->created_at?->toISOString(),
        ];
    }

    private function statusLabel(): string
    {
        return match ($this->status) {
            'initiated'             => 'Initiated',
            'payment_pending'       => 'Payment Pending',
            'payment_confirmed'     => 'Payment Confirmed',
            'processing'            => 'Processing',
            'sent_to_beneficiary'   => 'Sent',
            'completed'             => 'Completed',
            'failed'                => 'Failed',
            'cancelled'             => 'Cancelled',
            'refunded'              => 'Refunded',
            default                 => ucfirst($this->status),
        };
    }
}
