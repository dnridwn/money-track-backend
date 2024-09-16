<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'account' => !empty($this->account) ? $this->account : NULL,
            'category' => !empty($this->category) ? $this->category : NULL,
            'transfer_to_account' => !empty($this->transferToAccount) ? $this->transferToAccount : NULL,
            'date' => $this->date,
            'date_formatted' => $this->date_formatted,
            'amount' => $this->amount,
            'total_amount' => $this->total_amount,
            'total_amount_formatted' => $this->total_amount_formatted,
            'note' => $this->note,
            'fees' => !empty($this->fees) ? collect($this->fees)->map(fn($fee) => new TransactionFeeResource($fee)) : NULL
        ];
    }
}
