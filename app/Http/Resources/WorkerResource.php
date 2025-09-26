<?php

namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'designation' => $this->designation,
            'monthly_salary' => number_format($this->monthly_salary, 2),
            'is_active' => $this->is_active,
        ];
    }
}