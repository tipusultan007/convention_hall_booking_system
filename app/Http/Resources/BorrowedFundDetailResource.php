<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BorrowedFundDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'lender_name' => $this->lender->name,
            'purpose' => $this->purpose,
            'date_borrowed' => $this->date_borrowed->format('F d, Y'),
            'status' => $this->status,
            'financials' => [
                'total_borrowed' => number_format($this->amount_borrowed, 2),
                'total_repaid' => number_format($this->amount_repaid, 2),
                'due_amount' => number_format($this->due_amount, 2),
            ],
            'repayments' => $this->whenLoaded('repayments', function () {
                return $this->repayments->sortByDesc('repayment_date')->map(function ($repayment) {
                    return [
                        'id' => $repayment->id,
                        'amount' => number_format($repayment->repayment_amount, 2),
                        'date' => $repayment->repayment_date->format('M d, Y'),
                        'notes' => $repayment->notes,
                    ];
                })->values();
            }),
        ];
    }
}
