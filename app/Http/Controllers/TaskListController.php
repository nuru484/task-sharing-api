<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskList;
use Illuminate\Support\Facades\Auth;

class TaskListController extends Controller
{
    /**
     * Display a listing of the user's task lists.
     */
    public function index()
    {
        // Get the authenticated user's task lists
        $taskLists = Auth::user()->taskLists()->with('tasks')->get();

        return response()->json([
            'status' => 'success',
            'data' => $taskLists,
        ]);
    }

    /**
     * Store a newly created task list for the authenticated user.
     */
    public function store(Request $request)
    {
        // Validate request data
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Create a new task list associated with the authenticated user
        $taskList = Auth::user()->taskLists()->create([
            'name' => $request->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Task list created successfully.',
            'data' => $taskList,
        ], 201);
    }

    /**
     * Display the specified task list if it belongs to the user.
     */
    public function show(string $id)
    {
        // Find the task list by ID, ensuring it belongs to the authenticated user
        $taskList = Auth::user()->taskLists()->with('tasks')->find($id);

        if (!$taskList) {
            return response()->json([
                'status' => 'error',
                'message' => 'Task list not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $taskList,
        ]);
    }

    /**
     * Update the specified task list if it belongs to the user.
     */
    public function update(Request $request, string $id)
    {
        // Validate request data
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Find the task list by ID, ensuring it belongs to the authenticated user
        $taskList = Auth::user()->taskLists()->find($id);

        if (!$taskList) {
            return response()->json([
                'status' => 'error',
                'message' => 'Task list not found.',
            ], 404);
        }

        // Update the task list
        $taskList->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Task list updated successfully.',
            'data' => $taskList,
        ]);
    }

    /**
     * Remove the specified task list if it belongs to the user.
     */
    public function destroy(string $id)
    {
        // Find the task list by ID, ensuring it belongs to the authenticated user
        $taskList = Auth::user()->taskLists()->find($id);

        if (!$taskList) {
            return response()->json([
                'status' => 'error',
                'message' => 'Task list not found.',
            ], 404);
        }

        // Delete the task list
        $taskList->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Task list deleted successfully.',
        ]);
    }
}
