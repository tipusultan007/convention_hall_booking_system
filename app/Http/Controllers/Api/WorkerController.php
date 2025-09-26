<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Worker;
use App\Http\Resources\WorkerResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WorkerController extends Controller
{
    public function index()
    {
        return WorkerResource::collection(Worker::latest()->get());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|unique:workers,phone|max:20',
            'designation' => 'nullable|string|max:255',
            'monthly_salary' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $worker = Worker::create($validator->validated());
        return new WorkerResource($worker);
    }

    public function show(Worker $worker)
    {
        return new WorkerResource($worker);
    }

    public function update(Request $request, Worker $worker)
    {
        $validator = Validator::make($request->json()->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|unique:workers,phone,' . $worker->id . '|max:20',
            'designation' => 'nullable|string|max:255',
            'monthly_salary' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $worker->update($validator->validated());
        return new WorkerResource($worker);
    }

    public function destroy(Worker $worker)
    {
        $worker->delete();
        return response()->noContent();
    }
}
