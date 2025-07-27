<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\BranchProduct;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::orderBy('id', 'desc')->paginate(25);
        return view('branches.index', compact('branches'));
    }

    public function create()
    {
        return view('branches.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'manager_id' => 'required',
            'name' => 'required',
        ]);

        $branch = new Branch();
        $branch->manager_id = $request->manager_id;
        $branch->name = $request->name;
        $branch->save();    
        
        $products = Product::all();
        foreach($products as $product)
        {
            $branch_product = new BranchProduct();
            $branch_product->branch_id = $branch->id;
            $branch_product->product_id = $product->id;
            $branch_product->save();
        }

        if($branch){
            return redirect()->route('branches.index')->with('status', 'Branch created successfully.');         
        }
        return redirect()->route('branches.index')->with('delete', 'Branch create faild, try again.');
    }

    public function view(Branch $branch)
    {
        return view('branches.view', compact('branch'));
    }

    public function edit(Branch $branch)
    {
        return view('branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        $request->validate([
            'manager_id' => 'required',
            'name' => 'required',
        ]);

        $branch->manager_id = $request->manager_id;
        $branch->name = $request->name;
        $branch->status = $request->status;
        $branch->save();  

        return redirect()->route('branches.index')->with('success', 'Branch updated successfully.');
    }

    public function destroy(Request $request)
    {
        $branch = Branch::find($request->data_id);
        if($branch)
        {
            $branch_products = BranchProduct::where('branch_id', $branch->id)->delete();
            $branch->delete();

            return redirect()->route('branches.index')->with('delete', 'Branch deleted successfully.');
        }
        else
        {
            return redirect()->route('branches.index')->with('delete', 'No branch found!.');
        }    
    }
}