<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $product = Product::latest()->simplePaginate(10);
        return view('product.index', compact('product'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('product.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'product_name' => 'required',
            'desc' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'category' => 'required',
        ]);

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/product', $image->hashName());

        Product::create([
            'image' => $image->hashName(),
            'product_name' => $request->product_name,
            'desc' => $request->desc,
            'price' => $request->price,
            'stock' => $request->stock,
            'category' => $request->category,
        ]);

        return redirect()->route('product.index')->with(['success' => 'Data berhasil ditambahkan']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $product = Product::findOrFail($id);
        return view('product.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'product_name' => 'required',
            'desc' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'category' => 'required',
        ]);

        $product = Product::findOrFail($id);

        if($request->hashFile('image')){
            $image = $request->file('image');
            $image->storeAs('public/product', $image->hashName());

            Storage::delete('public/product' . $product->image);

            $product->update([
                'image' => $image->hashName(),
            'product_name' => $request->product_name,
            'desc' => $request->desc,
            'price' => $request->price,
            'stock' => $request->stock,
            'category' => $request->category,
            ]);
        }

        return redirect()->route('product.index')->with(['success' => 'Data berhasil diubah']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        Storage::delete('public/product' . $product->image);
        $product->delete();

        return redirect()->back()->with(['success' => 'Data berhasil dihapus']);

    }
}
