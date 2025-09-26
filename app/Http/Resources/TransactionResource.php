<?php

namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'type' => $this->type, // 'credit' or 'debit'
            'date' => $this->transaction_date->format('M d, Y'),
            'description' => $this->description,
        ];
    }
}