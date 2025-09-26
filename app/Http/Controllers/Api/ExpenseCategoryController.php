<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExpenseCategory;
use App\Http\Resources\ExpenseCategoryResource;

class ExpenseCategoryController extends Controller
{
    public function index()
    {
        // Return all categories, not paginated, for the dropdown
        $categories = ExpenseCategory::orderBy('name')->get();
        return ExpenseCategoryResource::collection($categories);
    }
}