<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::get();

        return response()->json([
            'status' => 'success',
            'count'  => count($products),
            'data'   => $products,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|max:255',
            'description' => 'nullable',
            'price'       => 'required|numeric',
            'offer_price' => 'nullable|numeric',
            'stock'       => 'required|integer',
            'is_featured' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 400);
        }

        $data['category_id'] = $request->category_id;
        $data['name']        = $request->name;
        $data['slug']        = Str::slug($request->name);
        $data['description'] = $request->description;
        $data['price']       = $request->price;
        $data['offer_price'] = $request->offer_price;
        $data['stock']       = $request->stock;
        $data['is_featured'] = $request->is_featured;

        Product::create($data);

        return response()->json([
            'status'  => 'success',
            'message' => 'Product created successfully',
        ], 201);
    }

    public function show(string $id)
    {
        $product = Product::with('category')->find($id);

        if ($product) {
            return response()->json([
                'status' => 'success',
                'data'   => $product,
            ], 200);
        }

        return response()->json([
            'status'  => 'failed',
            'message' => 'Product not found',
        ], 404);
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|max:255',
            'description' => 'nullable',
            'price'       => 'required|numeric',
            'offer_price' => 'nullable|numeric',
            'stock'       => 'required|integer',
            'is_featured' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'errors' => $validator->errors(),
            ], 400);
        }

        $product = Product::find($id);

        if ($product) {
            $product->category_id = $request->category_id;
            $product->name        = $request->name;
            $product->slug        = Str::slug($request->name);
            $product->description = $request->description;
            $product->price       = $request->price;
            $product->offer_price = $request->offer_price;
            $product->stock       = $request->stock;
            $product->is_featured = $request->is_featured;
            $product->save();

            return response()->json([
                'status'  => 'success',
                'message' => 'Product updated successfully',
            ], 201);
        }

        return response()->json([
            'status'  => 'failed',
            'message' => 'Product not found',
        ], 404);
    }

    public function destroy(string $id)
    {
        $product = Product::find($id);

        if ($product) {
            $product->delete();

            return response()->json([
                'status'  => 'success',
                'message' => 'Product deleted successfully',
            ], 200);
        }

        return response()->json([
            'status'  => 'failed',
            'message' => 'Product not found',
        ], 404);
    }
}