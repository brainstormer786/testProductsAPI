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
        $products = Product::paginate();
        return response()->json($products);
    }

    /**
     * Store a newly created product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'quantity' => 'required|integer'
        ]);

        $product = new Product();
        $product->name = $request->get('name');
        $product->description = $request->get('description') ?? null;
        $product->quantity = $request->get('quantity');
        $product->save();

        return response()->json(["message" => "Product added successfully!"]);
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
            return response()->json($product);
        else
            return response()->json(["error" => "Product did not found."], 404);
    }

    /**
     * Update the specified product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'quantity' => 'required|integer'
        ]);

        $product = Product::find($id);
        if($product) {
            if($request->has('quantity'))
                $product->quantity = $request->get('quantity');
            
            if($product->update($request->all()))
                return response()->json(["message" => "Product updated successfully!"]);
            else
                return response()->json(["error" => "Failed to update product."]);
        } else {
                return response()->json(["error" => "Product not found."], 404);
        }
    }

    /**
     * Add/update product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addImage(Request $request, $id)
    {
        $validated = $request->validate([
            'image' => 'required|image'
        ]);
        
        $product = Product::find($id);
        if($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = time().'.'.$file->extension();
            $imagePath = public_path(). '/files';

            $file->move($imagePath, $imageName);
            $product->image = '/files/'.$imageName;
            $product->save();

            return response()->json([
                "success" => true,
                "message" => "Image has been uploaded successfully."
            ]);    
        }

        return response()->json(["error" => "Product not found to attach image."], 404);
    }

    /**
     * Remove the specified product from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        if($product) {
            $product->delete();
            return response()->json(["message" => "Product deleted successfully!"]);
        } else {
            return response()->json(["error" => "Product not found"], 404);
        }
    }
}
