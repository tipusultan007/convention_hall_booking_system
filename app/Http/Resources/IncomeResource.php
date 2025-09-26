<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IncomeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'amount' => number_format($this->amount, 2),
            'date' => $this->income_date->format('M d, Y'),
            'description' => $this->description,
            'category_name' => $this->category->name,
        ];
    }
}