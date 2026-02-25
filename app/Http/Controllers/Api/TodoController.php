<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Http\ResponseTrait;

class TodoController extends Controller
{
    // GET api/todos
    public function index(Request $request) {
        $todos = $request->user()
            ->todos()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($todos);
    }

    // POST api/todos
    public function store(Request $request) {
        $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'due_date'     => 'nullable|date',
            'priority'     => 'nullable|in:low,medium,high', 
        ]);

        $todo = $request->user()->todos()->create([
            'title'       => $request->title,
            'description' => $request->description,
            'due_date'    => $request->due_date,
            'priority'    => $request->priority ?? 'medium',
            'is_completed' => false,
        ]);

        return response()->json([
            'message'   => 'Tạo công việc thành công',
            'todo'      => $todo,
        ], 201);
    }

    // GET api/todos/{id}
    public function show(Request $request, Todo $todo) {
        
        $this->authorize('view', $todo);
        return response()->json($todo);
    }

    // PUT api/todos/{id}
    public function update(Request $request, Todo $todo) {

        $this->authorize('update', $todo);

        $request->validate([
            'title'          => 'sometimes|required|string|max:255',
            'description'    => 'nullable|string',
            'due_date'       => 'nullable|date',
            'priority'       => 'nullable|in:low,medium,high',
            'is_completed'   => 'sometimes|boolean',
        ]);

        $todo->update($request->only([
            'title', 'description', 'due_date', 'priority', 'is_completed'
        ]));

        return response()->json([
            'message'    => 'Cập nhật công việc thành công',
            'todo'       => $todo,
        ], 200);
    }

    // DELETE api/todos/{id}
    public function destroy(Request $request, Todo $todo) {

        $this->authorize('delete', $todo);

        $todo->delete();

        return response()->json([
            'message'   => 'Xóa công việc thành công',
        ]);
    } 

    // PATCH api/todos/{id}/toggle
    public function toggle(Request $request, Todo $todo) {

        $this->authorize('update', $todo);

        $todo->update(['is_completed' => ! $todo->is_completed]);

        return response()->json([
            'message'   => $todo->is_completed ? 'Đã đánh dấu hoàn thành' : 'Đã hủy đánh dấu hoàn thành',
            'todo'      => $todo,
        ], 200);
    }
}
