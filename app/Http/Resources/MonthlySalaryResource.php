<?php

namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MonthlySalaryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'worker_name' => $this->worker->name,
            'salary_month' => $this->salary_month->format('F, Y'),
            'total_salary' => number_format($this->total_salary, 2),
            'paid_amount' => number_format($this->paid_amount, 2),
            'due_amount' => number_format($this->due_amount, 2),
            'status' => $this->status,
        ];
    }
}
