<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\BranchProduct;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        if(auth()->user()->type != 3){
            $products = Product::with('branchProducts')->orderBy('id', 'desc')->paginate(25);
            return view('products.index', compact('products'));
        }else{
            $branch = auth()->user()->branch;
            $products = Product::with(['branchProducts' => function ($q) use ($branch) {
                $q->where('branch_id', $branch->id);
            }])->orderBy('id', 'desc')->paginate(25);

            return view('products.index', compact('products', 'branch'));
        }       
        
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

            // ✅ Generate SKU
        $categoryCode = strtoupper(substr($request->category, 0, 3)); // e.g., BAJ
        $nameCode = strtoupper(substr(preg_replace('/\s+/', '', $request->name), 0, 5)); // e.g., RAZIQ
        $colorCode = strtoupper(substr($request->color, 0, 3)); // e.g., BLK
        $lastId = Product::max('id') + 1;
        $sku = "{$categoryCode}-{$nameCode}-{$colorCode}-" . str_pad($lastId, 3, '0', STR_PAD_LEFT);

        $product = new Product();
        $product->name = $request->name;
        $product->category = $request->category;
        $product->color = $request->color;
        $product->price = $request->price;
        $product->sku = $request->sku;
        $product->image = null;
        $product->save();

        $branches = Branch::all();
        foreach($branches as $branch)
        {
            $branch_product = new BranchProduct();
            $branch_product->branch_id = $branch->id;
            $branch_product->product_id = $product->id;
            $branch_product->save();
        }

        if($product){
            return redirect()->route('products.index')->with('status', 'Product stored successfully.');
        }
        return redirect()->route('products.index')->with('delete', 'Product store faild, try again.');
    }

     public function view(Product $product)
    {
        return view('products.view', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $product->name = $request->name;
        $product->category = $request->category;
        $product->color = $request->color;
        $product->price = $request->price;
        $product->sku = $request->sku;
        $product->status = $request->status;
        $product->save();

    

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Request $request)
    {
        $product = Product::find($request->data_id);
        if($product)
        {
            $branch_products = BranchProduct::where('product_id', $product->id)->delete();
            
            $product->delete();

            return redirect()->route('products.index')->with('delete', 'Product deleted successfully.');
        }
        else
        {
            return redirect()->route('products.index')->with('delete', 'No product found!');
        }    
    }
}
