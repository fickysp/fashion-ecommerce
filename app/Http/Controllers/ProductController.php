<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $size = ProductSize::all();
        $product = Product::latest()->simplePaginate(10);
        return view('product.index', compact('product', 'size'));
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
        // Validasi input
        $request->validate([
            'image' => 'required|image',
            'product_name' => 'required|string',
            'desc' => 'required|string',
            'price' => 'required|numeric',
            'size' => 'required|in:S,M,L,XL', // Pastikan input size sesuai dengan enum
            'stock' => 'required|integer|min:0', // Menambahkan validasi agar stok tidak negatif
            'category' => 'required|in:new,sale', // Validasi kategori
        ]);

        $image = $request->file('image');
        $image->storeAs('public/product', $image->hashName());

        // Simpan produk
        $product = new Product();
        $product->image = $image->hashName();
        $product->product_name = $request->product_name;
        $product->desc = $request->desc;
        $product->price = $request->price;
        $product->category = $request->category;




        $product->save();

        // Simpan ukuran dan stok ke tabel product_sizes
        $productSize = new ProductSize();
        $productSize->product_id = $product->id;
        $productSize->size = $request->size; // Gunakan size dari input
        $productSize->stock = $request->stock; // Gunakan stok dari input
        $productSize->save();

        // Update stok total di tabel products
        $product->stock = $request->stock; // Simpan stok dari size yang diinput
        $product->save();

        return redirect()->route('product.index')->with('success', 'Produk berhasil disimpan');
    }



    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::with('sizes')->findOrFail($id);
        return view('product.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $product = Product::findOrFail($id);
        $pSize = ProductSize::where('product_id', $product->id)->first();
        return view('product.edit', compact('pSize','product'));
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //     $this->validate($request, [
    //         'image' => 'required|image|mimes:jpeg,jpg,png|max:2048',
    //         'product_name' => 'required',
    //         'desc' => 'required',
    //         'price' => 'required',
    //         'size' => 'required',
    //         'stock' => 'required',
    //         'category' => 'required',
    //     ]);

    //     $product = Product::findOrFail($id);

    //     if ($request->hashFile('image')) {
    //         $image = $request->file('image');
    //         $image->storeAs('public/product', $image->hashName());

    //         Storage::delete('public/product' . $product->image);

    //         $product->update([
    //             'image' => $image->hashName(),
    //             'product_name' => $request->product_name,
    //             'desc' => $request->desc,
    //             'price' => $request->price,
    //             'stock' => $request->stock,
    //             'category' => $request->category,
    //         ]);
    //     }

    //     return redirect()->route('product.index')->with(['success' => 'Data berhasil diubah']);
    // }

    public function update(Request $request, string $id)
    {
        // Validasi input
        $request->validate([
            'image' => 'nullable|image', // Ganti 'required' menjadi 'nullable' agar bisa diupdate tanpa mengubah gambar
            'product_name' => 'required|string',
            'desc' => 'required|string',
            'price' => 'required|numeric',
            'size' => 'required|in:S,M,L,XL', // Pastikan input size sesuai dengan enum
            'stock' => 'required|integer|min:0', // Menambahkan validasi agar stok tidak negatif
            'category' => 'required|in:new,sale', // Validasi kategori
        ]);

        // Temukan produk berdasarkan ID
        $product = Product::findOrFail($id);

        // Jika ada gambar baru yang diupload
        if ($request->hasFile('image')) {
            // Hapus gambar lama dari storage
            Storage::delete('public/product/' . $product->image);

            // Simpan gambar baru
            $image = $request->file('image');
            $imageName = $image->hashName();
            $image->storeAs('public/product', $imageName);

            // Update produk dengan data baru, termasuk gambar
            $product->image = $imageName;
        }

        // Update informasi produk lainnya
        $product->product_name = $request->product_name;
        $product->desc = $request->desc;
        $product->price = $request->price;
        $product->category = $request->category;

        // Simpan produk
        $product->save();

        // Temukan ukuran dan stok yang sesuai
        $productSize = ProductSize::where('product_id', $product->id)->first();

        // Update ukuran dan stok di tabel product_sizes
        if ($productSize) {
            $productSize->size = $request->size; // Gunakan size dari input
            $productSize->stock = $request->stock; // Gunakan stok dari input
            $productSize->save();
        } else {
            // Jika tidak ada record size, buat yang baru
            $productSize = new ProductSize();
            $productSize->product_id = $product->id;
            $productSize->size = $request->size; // Gunakan size dari input
            $productSize->stock = $request->stock; // Gunakan stok dari input
            $productSize->save();
        }

        // Update stok total di tabel products
        $product->stock = $request->stock; // Simpan stok dari size yang diinput
        $product->save();

        return redirect()->route('product.index')->with('success', 'Produk berhasil diubah');
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
