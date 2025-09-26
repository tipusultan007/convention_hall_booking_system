<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MonthlySalaryDetailResource;
use App\Models\MonthlySalary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SalaryPaymentController extends Controller
{
     public function store(Request $request, MonthlySalary $monthlySalary)
    {
        // ** THE FIX: Manually validate the JSON payload **
        $validator = Validator::make($request->json()->all(), [
            'payment_amount' => 'required|numeric|min:0.01|max:' . $monthlySalary->due_amount,
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Get the validated data from the JSON body
        $validatedData = $validator->validated();

        DB::transaction(function () use ($validatedData, $monthlySalary) {
            // Use the validated data to create the payment
            $monthlySalary->payments()->create($validatedData);

            // The rest of the logic remains the same
            $totalPaid = $monthlySalary->payments()->sum('payment_amount');
            $dueAmount = $monthlySalary->total_salary - $totalPaid;
            $status = ($dueAmount <= 0) ? 'Paid' : 'Partially Repaid';

            $monthlySalary->update([
                'paid_amount' => $totalPaid,
                'due_amount' => $dueAmount,
                'status' => $status,
            ]);
        });
        
        // Return the full, updated salary details so the Flutter app can refresh its state
        $monthlySalary->load('worker', 'payments');
        return new MonthlySalaryDetailResource($monthlySalary);
    }
}
