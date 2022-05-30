<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Validator;

class ProductController extends Controller {

    public function create(Request $request) {
        $rules = [
            'name' => 'required',
            'price' => 'required|integer|gt:0',
            'description' => 'required|min:10'
        ];

        $data = $request->all();
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(),
                'code' => 400
            ], 400);
        }

        Product::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'code' => 200
        ]);
    }

    public function index() {
        $products = Product::select([
            'id', 'name', 'price', 'description'
        ])->get();

        return response()->json([
            'success' => true,
            'data' => $products,
            'code' => 200
        ]);
    }

    public function update(Request $request) {
        $rules = [
            'id' => 'required|integer|exists:products,id',
            'name' => 'required',
            'price' => 'required|integer|gt:0',
            'description' => 'required|min:10'
        ];

        $data = $request->all();
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(),
                'code' => 400
            ], 400);
        }

        Product::where('id', $request->id)
            ->update($request->except(['id']));

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'code' => 200
        ]);
    }

    public function destroy(Request $request) {
        $rules = [
            'id' => 'required|integer|exists:products,id'
        ];

        $data = $request->all();
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(),
                'code' => 400
            ], 400);
        }

        Product::destroy($request->id);

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully',
            'code' => 200
        ]);
    }
}
