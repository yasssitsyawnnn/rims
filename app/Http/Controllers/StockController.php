<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\BranchProduct;
use App\Models\Fulfillment;
use App\Models\UpdateStock;
use App\Models\Product;

class StockController extends Controller
{
    public function index()
    {
        if(auth()->user()->type != 3){
            $update_stocks = UpdateStock::orderBy('id', 'desc')->paginate(25);
        }else{
            $branch = Branch::where('manager_id', auth()->user()->id)->first();
            $update_stocks = UpdateStock::where('branch_id', $branch->id)->orderBy('id', 'desc')->paginate(25);
        }        
        return view('update_stocks.index', compact('update_stocks'));
    }

    public function create()
    {
        return view('update_stocks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required',
        ]);

        $update_stock = new UpdateStock();
        $update_stock->product_id = $request->product_id;
        $update_stock->branch_id = $request->branch_id;
        $update_stock->quantity = $request->quantity;
        $update_stock->save(); 
        
        $branch_product = BranchProduct::where('branch_id', $update_stock->branch_id)->where('product_id', $update_stock->product_id)->first();
        $branch_product->quantity = $branch_product->quantity + $request->quantity;
        $branch_product->save();
        
        if($update_stock){
            return redirect()->route('update_stocks.index')->with('status', 'Inventory update saved successfully.');         
        }
        return redirect()->route('update_stocks.index')->with('delete', 'Inventory update faild, try again.');
    }

    public function view(UpdateStock $update_stock)
    {
        return view('update_stocks.view', compact('update_stock'));
    }

    public function edit(UpdateStock $update_stock)
    {
        return view('update_stocks.edit', compact('update_stock'));
    }

    public function update(Request $request, UpdateStock $update_stock)
    {
        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required',
        ]);

        $diff = $update_stock->quantity - $request->quantity;

        $branch_product = BranchProduct::where('branch_id', $update_stock->branch_id)->where('product_id', $update_stock->product_id)->first();
        $branch_product->quantity = $branch_product->quantity - $diff;
        $branch_product->save();

        $update_stock->product_id = $request->product_id;
        $update_stock->quantity = $request->quantity;
        $update_stock->save();

        return redirect()->route('update_stocks.index')->with('success', 'Inventory updated successfully.');
    }

    public function destroy(Request $request)
    {
        $update_stock = UpdateStock::find($request->data_id);
        if($update_stock)
        {
            $branch_product = BranchProduct::where('product_id', $update_stock->product_id)->where('branch_id', $update_stock->branch_id)->first();
            $branch_product->quantity = $branch_product->quantity - $update_stock->quantity;
            $branch_product->save();

            $update_stock->delete();            
            return redirect()->route('update_stocks.index')->with('delete', 'Inventory deleted successfully.');
        }
        else
        {
            return redirect()->route('update_stocks.index')->with('delete', 'No inventory found!.');
        }    
    }

    public function getProductInfo($product_id)
    {
        $branch = auth()->user()->branch;

        $product = Product::with(['branchProducts' => function ($q) use ($branch) {
                $q->where('branch_id', $branch->id);
            }])
            ->where('id', $product_id)
            ->first();

        $html = view('partials.get_product_info', compact('product'))->render();

        return response()->json(['html' => $html]);
    }

     public function reports(Request $request)
    {
        $start = null;
        $end = null;
        $branch_id = null;
        $product_fillter = null;
        $update_stocks = null;
        $sum = 0;

        $fulfillments = null;
        $fulfillment_sum = 0;

        $start = $request->start;
        $end = $request->end;
        $product_fillter = $request->product_fillter;        

        if($start != null && $end != null){
            if(auth()->user()->type != 3){
                $branch_id = $request->branch_id;
                if($branch_id == 0 && $product_fillter == 0){
                    $update_stocks = UpdateStock::whereBetween('created_at', [$start, $end])
                    ->orderBy('id', 'asc')
                    ->get();

                    $sum = UpdateStock::whereBetween('created_at', [$start, $end])
                    ->orderBy('id', 'asc')
                    ->sum('quantity');

                    $fulfillments = Fulfillment::whereBetween('created_at', [$start, $end])
                    ->orderBy('id', 'asc')
                    ->get();

                    $fulfillment_sum = Fulfillment::whereBetween('created_at', [$start, $end])
                    ->orderBy('id', 'asc')
                    ->sum('quantity');
                }elseif($branch_id != 0 && $product_fillter == 0){

                    $update_stocks = UpdateStock::where('branch_id', $branch_id)->whereBetween('created_at', [$start, $end])
                    ->orderBy('id', 'asc')
                    ->get();

                    $sum = UpdateStock::where('branch_id', $branch_id)->whereBetween('created_at', [$start, $end])
                    ->orderBy('id', 'asc')
                    ->sum('quantity');

                    $fulfillments = Fulfillment::where('req_branch_id', $branch_id)->whereBetween('created_at', [$start, $end])
                    ->orderBy('id', 'asc')
                    ->get();

                    $fulfillment_sum = Fulfillment::where('req_branch_id', $branch_id)->whereBetween('created_at', [$start, $end])
                    ->orderBy('id', 'asc')
                    ->sum('quantity');
                }elseif($branch_id == 0 && $product_fillter != 0){
                    $update_stocks = UpdateStock::where('product_id', $product_fillter)->whereBetween('created_at', [$start, $end])
                    ->orderBy('id', 'asc')
                    ->get();

                    $sum = UpdateStock::where('product_id', $product_fillter)->whereBetween('created_at', [$start, $end])
                    ->orderBy('id', 'asc')
                    ->sum('quantity');

                    $fulfillments = Fulfillment::where('product_id', $product_fillter)->whereBetween('created_at', [$start, $end])
                    ->orderBy('id', 'asc')
                    ->get();

                    $fulfillment_sum = Fulfillment::where('product_id', $product_fillter)->whereBetween('created_at', [$start, $end])
                    ->orderBy('id', 'asc')
                    ->sum('quantity');
                }else{
                    $update_stocks = UpdateStock::where('branch_id', $branch_id)->where('product_id', $product_fillter)->whereBetween('created_at', [$start, $end])
                    ->orderBy('id', 'asc')
                    ->get();

                    $sum = UpdateStock::where('branch_id', $branch_id)->where('product_id', $product_fillter)->whereBetween('created_at', [$start, $end])
                    ->orderBy('id', 'asc')
                    ->sum('quantity');

                    $fulfillments = Fulfillment::where('req_branch_id', $branch_id)->where('product_id', $product_fillter)->whereBetween('created_at', [$start, $end])
                    ->orderBy('id', 'asc')
                    ->get();

                    $fulfillment_sum = Fulfillment::where('req_branch_id', $branch_id)->where('product_id', $product_fillter)->whereBetween('created_at', [$start, $end])
                    ->orderBy('id', 'asc')
                    ->sum('quantity');
                }                

            }else{
                $branch = Branch::where('manager_id', auth()->user()->id)->first();

                if($product_fillter == 0){

                    $update_stocks = UpdateStock::where('branch_id', $branch->id)->whereBetween('created_at', [$start, $end])
                    ->orderBy('id', 'asc')
                    ->get();

                    $sum = UpdateStock::where('branch_id', $branch->id)->whereBetween('created_at', [$start, $end])
                    ->orderBy('id', 'asc')
                    ->sum('quantity');

                    $fulfillments = Fulfillment::where('req_branch_id', $branch->id)->whereBetween('created_at', [$start, $end])
                    ->orderBy('id', 'asc')
                    ->get();

                    $fulfillment_sum = Fulfillment::where('req_branch_id', $branch->id)->whereBetween('created_at', [$start, $end])
                    ->orderBy('id', 'asc')
                    ->sum('quantity');
                }else{
                    $update_stocks = UpdateStock::where('product_id', $product_fillter)->where('branch_id', $branch->id)->whereBetween('created_at', [$start, $end])
                    ->orderBy('id', 'asc')
                    ->get();

                    $sum = UpdateStock::where('product_id', $product_fillter)->where('branch_id', $branch->id)->whereBetween('created_at', [$start, $end])
                    ->orderBy('id', 'asc')
                    ->sum('quantity');

                    $fulfillments = Fulfillment::where('product_id', $product_fillter)->where('req_branch_id', $branch->id)->whereBetween('created_at', [$start, $end])
                    ->orderBy('id', 'asc')
                    ->get();

                    $fulfillment_sum = Fulfillment::where('product_id', $product_fillter)->where('req_branch_id', $branch->id)->whereBetween('created_at', [$start, $end])
                    ->orderBy('id', 'asc')
                    ->sum('quantity');
                }
            }
        }

        return view('update_stocks.report', compact('update_stocks', 'start', 'end', 'sum', 'fulfillments', 'fulfillment_sum', 'branch_id', 'product_fillter'));
    }
}
