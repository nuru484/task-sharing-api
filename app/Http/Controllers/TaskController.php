<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TaskList;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the tasks in a specific task list.
     */
    public function index($task_list_id)
    {
        // Find the task list for the authenticated user
        $taskList = Auth::user()->taskLists()->find($task_list_id);
    
        if (!$taskList) {
            return response()->json([
                'status' => 'error',
                'message' => 'Task list not found.',
            ], 404);
        }
    
        $tasks = $taskList->tasks;
    
        return response()->json([
            'status' => 'success',
            'data' => $tasks,
        ]);
    }
    

    /**
     * Store a newly created task in a specific task list.
     */
    public function store(Request $request, $task_list_id)
    {
        $taskList = Auth::user()->taskLists()->find($task_list_id);
    
        if (!$taskList) {
            return response()->json([
                'status' => 'error',
                'message' => 'Task list not found.',
            ], 404);
        }
    
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_complete' => 'nullable|boolean',
        ]);
    
        $task = $taskList->tasks()->create([
            'title' => $request->title,
            'description' => $request->description,
            'is_complete' => $request->is_complete ?? false,
        ]);
    
        return response()->json([
            'status' => 'success',
            'message' => 'Task created successfully.',
            'data' => $task,
        ], 201);
    }
    

    /**
     * Display a specific task by ID if it belongs to the user's task list.
     */
    public function show($taskId)
    {
        $task = Auth::user()->taskLists()->with('tasks')->whereHas('tasks', function ($query) use ($taskId) {
            $query->where('id', $taskId);
        })->first();
    
        if (!$task) {
            return response()->json([
                'status' => 'error',
                'message' => 'Task not found.',
            ], 404);
        }
    
        return response()->json([
            'status' => 'success',
            'data' => $task->tasks->where('id', $taskId)->first(),
        ]);
    }
    

    /**
     * Update the specified task if it belongs to the user's task list.
     */
    public function update(Request $request, Task $task)
    {
        // Ensure the task belongs to a task list owned by the authenticated user
        if (!$task->taskList || $task->taskList->user_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Task not found.',
            ], 404);
        }

        // Validate the incoming request
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_complete' => 'nullable|boolean',
        ]);

        // Update the task with provided data
        $task->update($request->only(['title', 'description', 'is_complete']));

        return response()->json([
            'status' => 'success',
            'message' => 'Task updated successfully.',
            'data' => $task,
        ]);
    }

    /**
     * Remove the specified task if it belongs to the user's task list.
     */
    public function destroy(Task $task)
    {
        // Ensure the task belongs to a task list owned by the authenticated user
        if (!$task->taskList || $task->taskList->user_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Task not found.',
            ], 404);
        }

        // Delete the task
        $task->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Task deleted successfully.',
        ]);
    }
}
