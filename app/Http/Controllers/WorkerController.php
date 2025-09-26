<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use Illuminate\Http\Request;

class WorkerController extends Controller
{
    /**
     * Display a listing of the resource and the form to create a new one.
     */
    public function index()
    {
        $workers = Worker::latest()->get();
        return view('workers.index', compact('workers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|unique:workers,phone|max:20',
            'designation' => 'nullable|string|max:255',
            'monthly_salary' => 'required|numeric|min:0',
        ]);

        Worker::create($request->all());

        return redirect()->route('workers.index')->with('success', 'Worker added successfully.');
    }

      /**
     * Display the specified resource along with its salary history.
     */
    public function show(Worker $worker)
    {
        // Eager load the monthlySalaries relationship and order them by the most recent month first.
        $worker->load(['monthlySalaries' => function ($query) {
            $query->orderBy('salary_month', 'desc');
        }]);

        return view('workers.show', compact('worker'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Worker $worker)
    {
        return view('workers.edit', compact('worker'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Worker $worker)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|unique:workers,phone,' . $worker->id . '|max:20',
            'designation' => 'nullable|string|max:255',
            'monthly_salary' => 'required|numeric|min:0',
            'is_active' => 'required|boolean',
        ]);

        $worker->update($request->all());

        return redirect()->route('workers.index')->with('success', 'Worker details updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Worker $worker)
    {
        // Safety check: Prevent deleting a worker with existing salary records.
        if ($worker->monthlySalaries()->exists()) {
            return redirect()->route('workers.index')->with('error', 'Cannot delete worker. They have existing salary records.');
        }

        $worker->delete();

        return redirect()->route('workers.index')->with('success', 'Worker deleted successfully.');
    }
}