<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index() {
      $products = Product::orderBy('id', 'desc')->get();
      $total = Product::count();
        return view('admin.product.home', compact('products', 'total'));
    }

    public function create() {
        return view('admin.product.create');
    }

    public function save(Request $request)
    {
        $validation = $request->validate([
            'title' => 'required',
            'category' => 'required',
            'price' => 'required',
        ]);
        $data = Product::create($validation);
        if ($data) {
            session()->flash('success', 'Product Add Successfully');
            return redirect(route('admin/products'));
        } else {
            session()->flash('error', 'Some problem occure');
            return redirect(route('admin.products/create'));
        }
    }
    public function edit($id) {
      $products = Product::findOrFail($id);
      return view('admin.product.update', compact('products'));
    }

    public function update(Request $request, $id) {
      // Validasi data
      // $validatedData = $request->validate([
      //     'title' => 'required|string|max:255',
      //     'description' => 'required|string',
      //     'price' => 'required|numeric',
      // ]);

      // Temukan produk berdasarkan ID, jika tidak ditemukan akan menampilkan 404
      // $product = Product::findOrFail($id);

      // Update produk dengan data yang divalidasi
      // $product->update($validatedData);

      // Setel pesan sukses di sesi
      // session()->flash('success', 'Product updated successfully');

      // Redirect ke halaman yang sesuai, misalnya ke daftar produk
      // return redirect()->route('admin.products');

      // youtube version 
      $validatedData = $request->validate([
         'title' => 'required|string|max:255',
         'category' => 'required|string|max:255',
         'price' => 'required|numeric',
     ]);
     
      try {
         $product = Product::findOrFail($id);
         $product->title = $validatedData['title'];
         $product->category = $validatedData['category'];
         $product->price = $validatedData['price'];
 
         $product->save();
 
         session()->flash('success', 'Product updated successfully');
         return redirect()->route('admin/products');
     } catch (\Exception $e) {
         return back()->withErrors(['msg' => 'Error: ' . $e->getMessage()]);
     }
  }

  public function delete($id) {
   $products = Product::findOrFail($id)->delete();

   if($products) {
      session()->flash('success', 'Product Deleted Successfully');
      return redirect(route('admin/products'));
   } else {
      session()->flash('error', 'Some problem occure');
      return redirect(route('admin/products'));
   }
  }
}

