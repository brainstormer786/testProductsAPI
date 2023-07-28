<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        return $response()->json($products);
    }

    /**
     * Store a newly created product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = new Product();
        $product->name = $request->get('name');
        $product->description = $request->get('description') ?? null;
        $product->quantity = $request->get('quantity');
        $product->save();

        return $response()->json(["message" => "Product added successfully!"]);
    }

    /**
     * Return the specified product.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        if($product)
            return $response()->json($product);
        else
            return $response()->json(["error" => "Product did not found."], 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        $product->name = $request->get('name');
        $product->description = $request->get('description') ?? null;
        $product->quantity = $request->get('quantity');
        $product->save();

        return $response()->json(["message" => "Product updated successfully!"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        if($product) {
            $product->delete();
            return $response()->json(["message" => "Product deleted successfully!"]);
        } else {
            return $response()->json(["error" => "Product not found"], 404);
        }
    }
}
