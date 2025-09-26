<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\IncomeCategory;
use App\Http\Resources\IncomeCategoryResource;

class IncomeCategoryController extends Controller
{
    public function index()
    {
        $categories = IncomeCategory::orderBy('name')->get();
        return IncomeCategoryResource::collection($categories);
    }
}