<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\BranchProduct;
use App\Models\InventoryRequest;
use App\Models\Product;
use App\Models\Fulfillment;

class TransferController extends Controller
{
    public function index()
    {
        if(auth()->user()->type != 3){
            $transfers = InventoryRequest::where('type', 1)->orderBy('id', 'desc')->paginate(25);
            $to_approves = InventoryRequest::where('type', 2)->where('status', 4)->orderBy('id', 'desc')->get();
            return view('transfers.index', compact('transfers', 'to_approves'));
        }else{
            $branch = Branch::where('manager_id', auth()->user()->id)->first();
            $transfers = InventoryRequest::where('type', 2)->where('branch_id', $branch->id)->whereIn('status', [1, 2, 3])->orderBy('id', 'desc')->paginate(25);
            return view('transfers.index', compact('transfers'));
        }        
        
    }

    public function view(InventoryRequest $transfer)
    {
        $fulfillments = Fulfillment::where('request_id', $transfer->id)->orderBy('id', 'desc')->get();
        return view('transfers.view', compact('transfer', 'fulfillments'));
    }

    public function fulfill(Request $request, InventoryRequest $transfer)
    {
        if($transfer)
        {
            $request->validate([
                'quantity' => 'required',
            ]);

            if($transfer->type == '2'){
                $my_stock = BranchProduct::where('branch_id', $transfer->branch_id)->where('product_id', $transfer->product_id)->first();
                if($my_stock->quantity < $request->quantity){
                    return redirect()->route('transfers.view', $transfer)->with('delete', 'Not enough stock of this product to transfer!');
                }else{
                    $my_stock->quantity = $my_stock->quantity - $request->quantity;
                    $my_stock->save();
                }
            }

            $fulfillment = new Fulfillment();
            $fulfillment->request_id = $transfer->id;
            $fulfillment->product_id = $transfer->product_id;
            $fulfillment->req_branch_id = $transfer->req_branch_id;
            $fulfillment->quantity = $request->quantity;
            $fulfillment->relased_by = auth()->user()->id;
            $fulfillment->save();

            $transfer->remaining = $transfer->remaining - $request->quantity;
            $transfer->fulfilled = $transfer->fulfilled + $request->quantity;
            if($transfer->remaining > 0){
                $transfer->status = 2;
            }            
            else{
                $transfer->status = 3;
            }
            $transfer->save(); 

            $branch_product = BranchProduct::where('branch_id', $transfer->req_branch_id)->where('product_id', $transfer->product_id)->first();
            $branch_product->quantity = $branch_product->quantity + $request->quantity;
            $branch_product->save();

            return redirect()->route('transfers.view', $transfer)->with('status', 'Transfer fulfilled successfully');
        }
        else
        {
            return redirect()->route('transfers.index')->with('delete', 'No inventory request found!.');
        }
    }

    public function destroy(Request $request)
    {
        $fulfillment = Fulfillment::find($request->data_id);
        if($fulfillment)
        {
            $inventory_request = InventoryRequest::find($fulfillment->request_id);
            $inventory_request->remaining = $inventory_request->remaining + $fulfillment->quantity;
            $inventory_request->fulfilled = $inventory_request->fulfilled - $fulfillment->quantity;
            if($inventory_request->remaining > 0){
                $inventory_request->status = 2;
            }            
            else{
                $inventory_request->status = 3;
            }
            $inventory_request->save(); 

            $branch_product = BranchProduct::where('branch_id', $inventory_request->req_branch_id)->where('product_id', $inventory_request->product_id)->first();
            $branch_product->quantity = $branch_product->quantity - $fulfillment->quantity;
            $branch_product->save();

            if($inventory_request->type == '2'){
                $my_stock = BranchProduct::where('branch_id', $inventory_request->branch_id)->where('product_id', $inventory_request->product_id)->first();
                $my_stock->quantity = $my_stock->quantity + $request->quantity;
                $my_stock->save();
            }

            $fulfillment->delete();    
            return redirect()->route('transfers.view', $inventory_request)->with('delete', 'Fulfillment deleted successfully');
        }
        else
        {
            return redirect()->route('transfers.index')->with('delete', 'No fulfillment found!.');
        }    
    }

    public function approve(InventoryRequest $to_approve)
    {
        $to_approve->status = 1;
        $to_approve->save(); 
        return redirect()->route('transfers.index')->with('status', 'Inventory request approved successfully.'); 
    }

    public function reject(InventoryRequest $to_approve)
    {
        $to_approve->status = 5;
        $to_approve->save(); 
        return redirect()->route('transfers.index')->with('delete', 'Inventory request rejected successfully.'); 
    }

}
