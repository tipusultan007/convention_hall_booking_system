<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MonthlySalaryDetailResource extends JsonResource
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
            
            // ** THE FIX: Use the optional() helper to prevent errors if the worker is null **
            'worker_name' => optional($this->worker)->name ?? 'Unknown Worker',
            
            'salary_month' => $this->salary_month?->format('F, Y'),
            'status' => $this->status,
            'financials' => [
                'total_salary' => number_format($this->total_salary, 2),
                'paid_amount' => number_format($this->paid_amount, 2),
                'due_amount' => number_format($this->due_amount, 2),
            ],
            // The `whenLoaded` check already handles the case where payments might not be loaded.
            'payments' => $this->whenLoaded('payments', function () {
                return $this->payments->sortByDesc('payment_date')->map(function ($payment) {
                    return [
                        'id' => $payment->id,
                        'amount' => number_format($payment->payment_amount, 2),
                        'date' => $payment->payment_date->format('M d, Y'),
                        'notes' => $payment->notes,
                    ];
                })->values();
            }),
        ];
    }
}