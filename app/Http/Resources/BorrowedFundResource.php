<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BorrowedFundResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'lender_name' => $this->lender->name,
            'purpose' => $this->purpose,
            'date_borrowed' => $this->date_borrowed->format('M d, Y'),
            'due_amount' => number_format($this->due_amount, 2),
            'status' => $this->status,
        ];
    }
}
