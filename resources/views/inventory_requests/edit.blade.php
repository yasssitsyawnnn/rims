@extends('layouts.app')
@section('bodycontent')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <a href="{{ route('inventory_requests.index') }}" title="back" class="inline-flex items-center px-4 py-2 bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-900 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150" ><i class="fa-solid fa-arrow-left-long"></i></a><br><br>
                <h5 class="font-bold text-center text-gray-900 text-xl">Edit Inventory Request</h5><br>                
                <form action="{{ route('inventory_requests.update', $inventory_request) }}" method="POST" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div>
                        @php
                            use App\Models\Product;

                            $products = Product::where('status', 1)->get();
                        @endphp
                        <label for="product_id">Product</label><br>
                        <select name="product_id" id="product_id" class="select2 block w-96" required>
                            <option value="" disabled selected>Select a product from here</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-image="{{ asset('storage/' . $product->image) }}" @if($inventory_request->product_id == $product->id) selected @endif>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('product_id') <span class="text-red-500 error">{{ $message }}</span><br> @enderror
                    <br> 
                    <div>
                        <label for="type">Request Type</label><br>
                        <select name="type" id="type" class="block w-96 appearance-none rounded-md border border-gray-300 px-3 py-2" onchange="toggleType()" required>
                            <option value="" disabled selected>Select a type from here</option>
                            <option value="1" @if($inventory_request->type === 1) selected @endif>New request</option>
                            <option value="2" @if($inventory_request->type === 2) selected @endif>Transfer request from another branch</option>
                        </select> 
                    </div>
                    @error('type') <span class="text-red-500 error">{{ $message }}</span><br> @enderror
                    <br>                     
                    <div id="selectbranch" style="display:none;">
                        @php
                            use App\Models\Branch;
                            if(auth()->user()->type != 3){
                                $branches = Branch::where('status', 1)->get();
                            }                               
                            else{
                                $branches = Branch::where('manager_id', '!=', auth()->user()->id)->where('status', 1)->get();
                            }
                        @endphp
                        <label for="branch_id">Request From Branch</label><br>
                        <select name="branch_id" id="branch_id" class="block w-96 appearance-none rounded-md border border-gray-300 px-3 py-2">
                            <option value="" disabled selected>Select a branch from here</option>
                            @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" @if($inventory_request->branch_id == $branch->id) selected @endif>{{ $branch->name }} (Manager: @if(isset($branch->manager)){{ $branch->manager->name }}@endif)</option>
                            @endforeach
                        </select> 
                        @error('branch_id') <span class="text-red-500 error">{{ $message }}</span><br> @enderror
                        <br> 
                    </div>                    
                    @if(auth()->user()->type != 3)
                    <div>
                        @php
                            $branches = Branch::where('status', 1)->get();
                        @endphp
                        <label for="req_branch_id">Branch That Need Inventory</label><br>
                        <select name="req_branch_id" id="req_branch_id" class="block w-96 appearance-none rounded-md border border-gray-300 px-3 py-2" required>
                            <option value="" disabled selected>Select a branch from here</option>
                            @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" @if($inventory_request->req_branch_id == $branch->id) selected @endif>{{ $branch->name }} (Manager: @if(isset($branch->manager)){{ $branch->manager->name }}@endif)</option>
                            @endforeach
                        </select> 
                    </div>
                    @error('req_branch_id') <span class="text-red-500 error">{{ $message }}</span><br> @enderror
                    <br>
                    @else
                        @php
                            $req_branch = Branch::where('manager_id', auth()->user()->id)->first();
                        @endphp
                        <input type="hidden" name="req_branch_id" value="{{ $req_branch->id }}">
                    @endif   
                     <div>
                        <label for="quantity">Quantity</label><br>
                        <input type="number" min="0" name="quantity" id="quantity" value="{{ $inventory_request->quantity }}" class="block w-96 appearance-none rounded-md border border-gray-300 px-3 py-2" placeholder="quantity" required>
                    </div>
                    @error('quantity') <span class="text-red-500 error">{{ $message }}</span><br> @enderror
                    <br>                
                    <button type="submit" class="passwordvalid disabled:opacity-25 inline-flex items-center px-4 py-2 bg-blue-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-900 focus:bg-blue-900 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-25">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        function formatOption(option) {
            if (!option.id) return option.text;
            var img = $(option.element).data('image');
            return $('<span><img src="' + img + '" class="w-6 h-6 inline-block mr-2" /> ' + option.text + '</span>');
        }

        $('#product_id').select2({
            templateResult: formatOption,
            templateSelection: formatOption
        });
    });

    document.getElementById('quantity').addEventListener('input', function() {
        if (this.value < 0) {
            this.value = 0;
        }
    });
</script>

<script>
    function toggleType() {
        var type = document.getElementById('type').value;
        var branchId = document.getElementById('branch_id');
        
        if (type == "2") {
            branchId.required = true;
            $('#selectbranch').show();
        } else {
            branchId.required = false;
            $('#selectbranch').hide();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        toggleType();
    });
</script>
@endpush