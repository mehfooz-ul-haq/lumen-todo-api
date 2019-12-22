<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

class CategoriesController extends Controller
{
     /** @var Category */
    protected $category;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Category $category) {
        $this->category = $category;
    }

    /**
     * Return all the categories
     */
    public function index(Request $request) {
       
        try {
            return response()->json(['status' => true, 'message' => 'Request was successfull', 'data' => $request->authUser->categories], 200);
        }
        catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'data' => []], 400);
        }
    }

    /**
     * Return a specific category
     */
    public function show($id, Request $request) {
        
        try {
            $this->category = Category::find($id);
            if( !$this->category ) {
                return response()->json(['status' => false, 'message' => 'Category not found', 'data' => []], 404);
            }
        
            if( $request->authUser->id !== $this->category->user_id ) {
                return response()->json(['status' => false, 'message' => 'Bad Request', 'data' => []], 422);
            }

            return response()->json(['status' => true, 'message' => 'Request was successfull', 'data' => $this->category], 200);
        }
        catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'data' => []], 400);
        }
       
    }


    /**
     * Create a new category
     */
    public function store(Request $request) {
        
        try {
            $this->validate($request, ['name' => 'required|string']);
            
            $category = $this->category->create([
                'user_id' => $request->authUser->id,
                'name' => $request->input('name')
            ]);

            return response()->json(['status' => true, 'message' => 'Request was successfull', 'data' => $category], 200);
        }
        catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'data' => []], 400);
        }
    }

    /**
     * Update info for a specific category
     */
    public function update(Request $request, $id) {
        try {
            $this->validate($request, ['name' => 'required|string']);

            $category = $this->category->find($id);

            if( !$category ) {
                return response()->json(['status' => false, 'message' => 'Category not found', 'data' => []], 404);
            }

            if( $request->authUser->id !== $category->user_id ) {
                return response()->json(['status' => false, 'message' => 'Bad Request', 'data' => []], 422);
            }

            $category->update(['name' => $request->input('name'), 'user_id' => $request->authUser->id,]);

            return response()->json(['status' => true, 'message' => 'Request was successfull', 'data' => $category], 200);
        }
        catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'data' => []], 400);
        }
    }


    /**
     * Delete a specific category
     */
    public function destroy($id) {
       
        try {
            $category = $this->category->find($id);
            $category->delete();
            return response()->json(['status' => true, 'message' => 'Category deleted successfully', 'data' => []], 200);
        }
        catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'data' => []], 400);
        }
    }
}
