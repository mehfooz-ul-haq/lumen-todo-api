<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Todo;

class TodosController extends Controller
{
     /** @var Todo */
    protected $todo;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Todo $todo)
    {
        $this->todo = $todo;
    }   

    /**
     * Return all the todos
     */
    public function index(Request $request) {
       
        try {
            return response()->json(['status' => true, 'message' => 'Request was successfull', 'data' => $request->authUser->todos->all()], 200);
        }
        catch (\Exception $e) {
            return response()->json(['status' => true, 'message' => $e->getMessage(), 'data' => []], 400);
        }
    }

    /**
     * Return a specific todo
     */
    public function show($id) {
        
        try {
            return response()->json(['status' => true, 'message' => 'Request was successfull', 'data' => $this->todo->find($id)], 200);
        }
        catch (\Exception $e) {
            return response()->json(['status' => true, 'message' => $e->getMessage(), 'data' => []], 400);
        }
    }


    /**
     * Create a new todo
     */
    public function store(Request $request) {
        
        try {
            $this->validate($request, [
                'category_id' => 'required|integer',
                'status_id' => 'required|integer',
                'name' => 'required|string|unique:todos',
                'description' => 'required|string',
                'date_time' => 'required|date_format:Y-m-d H:i:s',
            ]);

            $todo = $this->todo->create([
                'user_id' => $request->authUser->id,
                'category_id' => $request->input('category_id'),
                'status_id' => $request->input('status_id'),
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'date_time' => $request->input('date_time'),
            ]);

            return response()->json(['status' => true, 'message' => 'Todo deleted successfully', 'data' => $todo], 200);
        }
        catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'data' => []], 400);
        }
    }

    /**
     * Update info for a specific todo
     */
    public function update(Request $request, $id) {
        
        try {
            $this->validate($request, ['name' => 'required|string']);

            $todo = $this->todo->find($id);
            $todo->update(['name' => $request->input('name')]);
    
            return response()->json(['status' => true, 'message' => 'Todo deleted successfully', 'data' => $todo], 200);
        }
        catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'data' => []], 400);
        }
        
    }


    /**
     * Delete a specific todo
     */
    public function destroy($id) {

        try {
            $todo = $this->todo->find($id);
            $todo->delete();
            return response()->json(['status' => true, 'message' => 'Todo deleted successfully', 'data' => []], 200);
        }
        catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'data' => []], 400);
        }
    }


    /**
     * Filter todos
     */
    public function filter(Request $request) {
        
        try {
            // get todo of logged in user
            $todos = $this->todo->whereUserId($request->authUser->id);

            // filter by status
            $by = (strtolower($request->input('by')) == 'c') ? 'category_id' : 'status_id';

            // sorty by sort
            $sort = (strtolower($request->input('sort')) == 'desc') ? 'desc' : 'asc';
            
            // run final query
            $todos = $todos->orderBy($by, $sort)->get();
            
            return response()->json(['status' => true, 'message' => 'Todo deleted successfully', 'data' => $todos], 200);
        }
        catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'data' => []], 400);
        }

    }

}
