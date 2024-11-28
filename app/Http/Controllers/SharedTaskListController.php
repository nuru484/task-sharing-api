<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SharedTaskList;
use App\Models\TaskList;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SharedTaskListController extends Controller
{
    /**
     * Share a task list with another user.
     */
    public function share(Request $request)
    {
        $request->validate([
            'task_list_id' => 'required|exists:task_lists,id',
            'username' => 'required|exists:users,username',
            'permission' => 'required|in:view,edit',
        ]);

        $taskList = Auth::user()->taskLists()->find($request->task_list_id);

        if (!$taskList) {
            return response()->json([
                'status' => 'error',
                'message' => 'Task list not found or not owned by you.',
            ], 403);
        }

        $recipient = User::where('username', $request->username)->first();

        if ($recipient->id === Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'You cannot share a task list with yourself.',
            ], 400);
        }

        SharedTaskList::updateOrCreate(
            [
                'task_list_id' => $taskList->id,
                'user_id' => $recipient->id,
            ],
            [
                'permission' => $request->permission,
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Task list shared successfully.',
        ]);
    }
/**
 * View task lists shared with the authenticated user.
 */
public function index()
{
    // Fetch all shared task lists with their associated task lists and tasks
    $sharedTaskLists = SharedTaskList::with('taskList.tasks') // Eager load task list and its tasks
        ->where('user_id', Auth::id())
        ->get();

    // Return the response with the shared task lists along with their tasks
    return response()->json([
        'status' => 'success',
        'data' => $sharedTaskLists,
    ]);
}


    /**
     * View a specific shared task list and its tasks.
     */
    public function show($task_list_id)
    {
        $sharedTaskList = SharedTaskList::where('task_list_id', $task_list_id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$sharedTaskList) {
            return response()->json([
                'status' => 'error',
                'message' => 'Task list not shared with you.',
            ], 403);
        }

        $taskList = TaskList::with('tasks')->find($task_list_id);

        return response()->json([
            'status' => 'success',
            'data' => $taskList,
        ]);
    }

    /**
     * Edit a shared task list (if permitted).
     */
    public function update(Request $request, $task_list_id)
    {
        $sharedTaskList = SharedTaskList::where('task_list_id', $task_list_id)
            ->where('user_id', Auth::id())
            ->where('permission', 'edit')
            ->first();

        if (!$sharedTaskList) {
            return response()->json([
                'status' => 'error',
                'message' => 'You do not have permission to edit this task list.',
            ], 403);
        }

        $taskList = TaskList::find($task_list_id);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

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
 * View all users a task list is shared with, including the task list details.
 */
public function sharedUsers($task_list_id)
{
    // Ensure the authenticated user owns the task list
    $taskList = Auth::user()->taskLists()->find($task_list_id);

    if (!$taskList) {
        return response()->json([
            'status' => 'error',
            'message' => 'Task list not found or not owned by you.',
        ], 403);
    }

    // Fetch all users the task list is shared with, including task list details
    $sharedUsers = SharedTaskList::with(['user', 'taskList'])
        ->where('task_list_id', $task_list_id)
        ->get();

    return response()->json([
        'status' => 'success',
        'task_list' => [
            'id' => $taskList->id,
            'name' => $taskList->name,
            'created_at' => $taskList->created_at,
            'updated_at' => $taskList->updated_at,
        ],
        'shared_with' => $sharedUsers->map(function ($sharedUser) {
            return [
                'id' => $sharedUser->id,
                'permission' => $sharedUser->permission,
                'user' => [
                    'id' => $sharedUser->user->id,
                    'name' => $sharedUser->user->name,
                    'username' => $sharedUser->user->username,
                ],
            ];
        }),
    ]);
}



/**
 * Revoke a user's access to a shared task list.
 */
public function revokeAccess(Request $request, $task_list_id)
{
    $request->validate([
        'username' => 'required|exists:users,username',
    ]);

    // Ensure the authenticated user owns the task list
    $taskList = Auth::user()->taskLists()->find($task_list_id);

    if (!$taskList) {
        return response()->json([
            'status' => 'error',
            'message' => 'Task list not found or not owned by you.',
        ], 403);
    }

    // Find the recipient user by username
    $user = User::where('username', $request->username)->first();

    if (!$user) {
        return response()->json([
            'status' => 'error',
            'message' => 'User not found.',
        ], 404);
    }

    // Ensure the task list is actually shared with the user
    $sharedTaskList = SharedTaskList::where('task_list_id', $task_list_id)
        ->where('user_id', $user->id)
        ->first();

    if (!$sharedTaskList) {
        return response()->json([
            'status' => 'error',
            'message' => 'This task list is not shared with the specified user.',
        ], 404);
    }

    // Revoke access
    $sharedTaskList->delete();

    return response()->json([
        'status' => 'success',
        'message' => 'User\'s access to the task list has been revoked.',
    ]);
}


}
