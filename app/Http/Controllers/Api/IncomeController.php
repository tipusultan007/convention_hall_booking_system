<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\IncomeResource;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Income::with('category');

        // ** ADD THIS FILTERING LOGIC **
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('income_date', [$request->start_date, $request->end_date]);
        }
        if ($request->has('category_id')) {
            $query->where('income_category_id', $request->category_id);
        }
        // ****************************

        $expenses = $query->latest()->paginate(20);
        return IncomeResource::collection($expenses);
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
