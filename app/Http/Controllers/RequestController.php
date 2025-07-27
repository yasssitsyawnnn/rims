<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\BranchProduct;
use App\Models\InventoryRequest;
use App\Models\Product;
use App\Models\Fulfillment;

class RequestController extends Controller
{
    public function index()
    {
        if(auth()->user()->type != 3){
            $inventory_requests = InventoryRequest::orderBy('id', 'desc')->paginate(25);
        }else{
            $branch = Branch::where('manager_id', auth()->user()->id)->first();
            $inventory_requests = InventoryRequest::where('req_branch_id', $branch->id)->orderBy('id', 'desc')->paginate(25);
        }        
        return view('inventory_requests.index', compact('inventory_requests'));
    }

    public function create()
    {
        return view('inventory_requests.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'type' => 'required',
            'quantity' => 'required',
        ]);

        $inventory_request = new InventoryRequest();
        $inventory_request->product_id = $request->product_id;
        $inventory_request->type = $request->type;
        if($request->type == '2'){
            $inventory_request->branch_id = $request->branch_id;
            if(auth()->user()->type == 3){
                $inventory_request->status = 4;
            }
        }        
        $inventory_request->req_branch_id = $request->req_branch_id;
        $inventory_request->quantity = $request->quantity;
        $inventory_request->remaining = $request->quantity;
        $inventory_request->save();    
        
        if($inventory_request){
            return redirect()->route('inventory_requests.index')->with('status', 'Inventory request created successfully.');         
        }
        return redirect()->route('inventory_requests.index')->with('delete', 'Inventory request create faild, try again.');
    }

    public function view(InventoryRequest $inventory_request)
    {
        $fulfillments = Fulfillment::where('request_id', $inventory_request->id)->orderBy('id', 'desc')->get();
        return view('inventory_requests.view', compact('inventory_request', 'fulfillments'));
    }

    public function edit(InventoryRequest $inventory_request)
    {
        return view('inventory_requests.edit', compact('inventory_request'));
    }

    public function update(Request $request, InventoryRequest $inventory_request)
    {
        $request->validate([
            'product_id' => 'required',
            'type' => 'required',
            'quantity' => 'required',
        ]);

        $inventory_request->product_id = $request->product_id;
        $inventory_request->type = $request->type;
        if($request->type == '2'){
            $inventory_request->branch_id = $request->branch_id;
        }
        $inventory_request->req_branch_id = $request->req_branch_id;
        $inventory_request->quantity = $request->quantity;
        $inventory_request->remaining = $request->quantity;
        $inventory_request->save();

        return redirect()->route('inventory_requests.index')->with('success', 'Inventory request updated successfully.');
    }

    public function destroy(Request $request)
    {
        $inventory_request = InventoryRequest::find($request->data_id);
        if($inventory_request)
        {
            if($inventory_request->status == 1){
                $inventory_request->delete();
            }else{
                if($inventory_request->type == '1'){
                    $branch_product = BranchProduct::where('product_id', $inventory_request->product_id)->where('branch_id', $inventory_request->req_branch_id)->first();
                    $branch_product->quantity = $branch_product->quantity - $inventory_request->fulfilled_qty;
                    $branch_product->save();
                }else{
                    $branch_product = BranchProduct::where('product_id', $inventory_request->product_id)->where('branch_id', $inventory_request->req_branch_id)->first();
                    $branch_product->quantity = $branch_product->quantity - $inventory_request->fulfilled_qty;
                    $branch_product->save();

                    $old_branch = BranchProduct::where('product_id', $inventory_request->product_id)->where('branch_id', $inventory_request->branch_id)->first();
                    $old_branch->quantity = $old_branch->quantity + $inventory_request->fulfilled_qty;
                    $old_branch->save();
                }

                $inventory_request->delete();
            }            
            return redirect()->route('inventory_requests.index')->with('delete', 'Inventory request deleted successfully.');
        }
        else
        {
            return redirect()->route('inventory_requests.index')->with('delete', 'No inventory request found!.');
        }    
    }

    public function getBranchProducts($product_id)
    {
        $branch_products = BranchProduct::with(['branch.manager'])
            ->where('product_id', $product_id)
            ->get();

        $html = view('partials.branch_products_table', compact('branch_products'))->render();

        return response()->json(['html' => $html]);
    }
}
