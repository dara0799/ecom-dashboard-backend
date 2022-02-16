<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Validator;

class ProductController extends Controller
{
    // https://www.itsolutionstuff.com/post/crud-with-image-upload-in-laravel-8-exampleexample.html
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $input = $request->all();

        if ($image = $request->file('path')) {
            $destinationPath = 'image/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['path'] = "$profileImage";
        }

        return Product::create($input);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product, $id)
    {
        if ($product == [] || !$product) {
            return 'No product found';
        } else {
            return Product::where('id', $id)->get();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product, $id)
    {
        return Product::where('id', $id)->get($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product, $id)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required'
        ]);
        
        $input = $request->except('_method');

        if ($input == [] || !$input) {
            return 'No product found';
        } else {
            if ($image = $request->file('path')) {
                $destinationPath = 'image/';
                $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
                $image->move($destinationPath, $profileImage);
                $input['path'] = "$profileImage";
            }else{
                unset($input['image']);
            } 
            Product::where('id', $id)->update($input);
            return $product;
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product, $id)
    {
        if ($product == [] || !$product) {
            return 'No product found';
        } else {
            Product::where('id', $id)->delete($product);
            return 'This product has been destroyed successfully!';
        }
    }

    
    /**
     * Search the specified resource from storage.
     *
     * @param  str  $product
     * @return \Illuminate\Http\Response
     */
    public function search($product)
    {
        return Product::where('name', 'like', '%' . $product . '%')->get();
    }
}
