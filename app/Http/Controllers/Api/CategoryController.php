<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::get();

        return response()->json([
            'status' => 'success',
            'count'  => count($categories),
            'data'   => $categories,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|max:255',
            'description' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 400);
        }

        $data['name']        = $request->name;
        $data['slug']        = Str::slug($request->name);
        $data['description'] = $request->description;

        Category::create($data);

        return response()->json([
            'status'  => 'success',
            'message' => 'Category created successfully',
        ], 201);
    }

    public function show(string $id)
    {
        $category = Category::find($id);

        if ($category) {
            return response()->json([
                'status' => 'success',
                'data'   => $category,
            ], 200);
        }

        return response()->json([
            'status'  => 'failed',
            'message' => 'Category not found',
        ], 404);
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|max:255',
            'description' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'errors' => $validator->errors(),
            ], 400);
        }

        $category = Category::find($id);

        if ($category) {
            $category->name        = $request->name;
            $category->slug        = Str::slug($request->name);
            $category->description = $request->description;
            $category->save();

            return response()->json([
                'status'  => 'success',
                'message' => 'Category updated successfully',
            ], 201);
        }

        return response()->json([
            'status'  => 'failed',
            'message' => 'Category not found',
        ], 404);
    }

    public function destroy(string $id)
    {
        $category = Category::find($id);

        if ($category) {
            $category->delete();

            return response()->json([
                'status'  => 'success',
                'message' => 'Category deleted successfully',
            ], 200);
        }

        return response()->json([
            'status'  => 'failed',
            'message' => 'Category not found',
        ], 404);
    }
}