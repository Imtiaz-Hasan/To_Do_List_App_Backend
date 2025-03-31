<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $tasks = Auth::user()->tasks;
        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'created_date' => 'required|date',
                'completion_date' => 'nullable|date|after_or_equal:created_date'
            ]);
        
            $task = Auth::user()->tasks()->create([
                'name' => $request->name,
                'created_date' => $request->created_date,
                'completion_date' => $request->completion_date,
                'is_completed' => false
            ]);
        
            return response()->json([
                'status' => 'success',
                'message' => 'Task created successfully!',
                'task' => $task
            ], 201);
        } catch (ValidationException $e) {
            $errors = $e->errors();
            $firstErrorKey = array_key_first($errors);
            $firstErrorMessage = $errors[$firstErrorKey][0];
        
            return response()->json([
                'status' => 'error',
                'message' => $firstErrorMessage
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create task',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);
        return response()->json($task);
    }

    public function update(Request $request, Task $task)
    {
        try {
            $this->authorize('update', $task);
        
            $request->validate([
                'name' => 'required|string|max:255',
                'created_date' => 'required|date',
                'completion_date' => 'nullable|date|after_or_equal:created_date'
            ]);
        
            $task->update([
                'name' => $request->name,
                'created_date' => $request->created_date,
                'completion_date' => $request->completion_date
            ]);
        
            return response()->json([
                'status' => 'success',
                'message' => 'Task updated successfully!',
                'task' => $task
            ]);
        } catch (ValidationException $e) {
            $errors = $e->errors();
            $firstErrorKey = array_key_first($errors);
            $firstErrorMessage = $errors[$firstErrorKey][0];
        
            return response()->json([
                'status' => 'error',
                'message' => $firstErrorMessage
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update task',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Task $task)
    {
        try {
            $this->authorize('delete', $task);
            $task->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Task deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete task',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function complete(Task $task)
    {
        try {
            $this->authorize('update', $task);
            
            $task->update([
                'is_completed' => true,
                'completion_date' => now()
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Task marked as completed!',
                'task' => $task
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to complete task',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
}