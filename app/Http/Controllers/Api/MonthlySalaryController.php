<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MonthlySalaryDetailResource;
use Illuminate\Http\Request;
use App\Models\MonthlySalary; /* ... other models ... */
use App\Http\Resources\MonthlySalaryResource;
use App\Models\Worker;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class MonthlySalaryController extends Controller
{
    public function index() {
        $salaries = MonthlySalary::with('worker')->latest()->paginate(20);
        return MonthlySalaryResource::collection($salaries);
    }
    public function show(\App\Models\MonthlySalary $salary) 
{
    // ***** ADD THIS CHECK *****
    // This will force a 404 error if the model was not found,
    // which is the correct behavior.
    if (!$salary->exists) {
        return response()->json(['message' => 'Salary record not found.'], 404);
    }
    // **************************

    // Eager load all necessary relationships
    $salary->load('worker', 'payments');
    if (is_null($salary->worker)) {
        return response()->json([
            'message' => "Data integrity error: The worker associated with this salary record (ID: {$salary->id}) has been deleted."
        ], 404); // Return 404 Not Found as the complete resource is not available.
    }

    return new \App\Http\Resources\MonthlySalaryDetailResource($salary);
}
        public function generate(Request $request)
    {
        // ** THE FIX: Manually validate the JSON payload **
        $validator = Validator::make($request->json()->all(), [
            'month' => 'required|date_format:Y-m', // Expects format like "2025-10"
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Get the validated data from the JSON body
        $validatedData = $validator->validated();
        $month = Carbon::parse($validatedData['month'])->startOfMonth();
        
        $activeWorkers = Worker::where('is_active', true)->get();

        if ($activeWorkers->isEmpty()) {
            return response()->json(['message' => 'No active workers found.'], 404);
        }

        $recordsCreated = 0;

        // The rest of your logic is already correct and can remain the same
        DB::beginTransaction();
        try {
            foreach ($activeWorkers as $worker) {
                $salaryRecord = MonthlySalary::firstOrCreate(
                    [
                        'worker_id' => $worker->id,
                        'salary_month' => $month->toDateString(),
                    ],
                    [
                        'total_salary' => $worker->monthly_salary,
                        'paid_amount' => 0.00,
                        'due_amount' => $worker->monthly_salary,
                        'status' => 'Unpaid',
                    ]
                );

                if ($salaryRecord->wasRecentlyCreated) {
                    $recordsCreated++;
                }
            }
            
            DB::commit();

            if ($recordsCreated > 0) {
                return response()->json(['message' => "Successfully generated {$recordsCreated} new salary records for " . $month->format('F, Y') . "."], 201);
            } else {
                return response()->json(['message' => "Salary records for " . $month->format('F, Y') . " already exist."], 200);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Salary generation failed: ' . $e->getMessage());
            
            return response()->json(['message' => 'An unexpected error occurred.'], 500);
        }
    }
}
