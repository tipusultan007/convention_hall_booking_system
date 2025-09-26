<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\IncomeResource;

class IncomeController extends Controller
{
    public function index()
    {
        $incomes = Income::with('category')->latest()->paginate(20);
        return IncomeResource::collection($incomes);
    }

    public function show(Income $income)
    {
        $income->load('category');
        return new IncomeResource($income);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            'income_category_id' => 'required|exists:income_categories,id',
            'amount' => 'required|numeric|min:0',
            'income_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $income = Income::create($validator->validated());
        $income->load('category');
        return new IncomeResource($income);
    }

    public function update(Request $request, Income $income)
    {
        $validator = Validator::make($request->json()->all(), [
            'income_category_id' => 'required|exists:income_categories,id',
            'amount' => 'required|numeric|min:0',
            'income_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $income->update($validator->validated());
        $income->load('category');
        return new IncomeResource($income);
    }

    public function destroy(Income $income)
    {
        $income->delete();
        return response()->noContent();
    }
}