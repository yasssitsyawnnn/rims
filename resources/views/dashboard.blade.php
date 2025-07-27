@extends('layouts.app')
@section('bodycontent')
@if (session('status'))
    <div class="text-black m-2 p-4 bg-green-200">
        {{ session('status') }}
    </div>
@endif
@if (session('success'))
    <div class="text-black m-2 p-4 bg-yellow-200">
        {{ session('success') }}
    </div>
@endif
@if (session('delete'))
    <div class="text-black m-2 p-4 bg-red-200">
        {{ session('delete') }}
    </div>
@endif
<h1 class="text-center font-bold text-indigo-800 uppercase">
  Dashboard
  {{ auth()->user()->name }}
  (@if(auth()->user()->type == 1)
  Admin
  @elseif(auth()->user()->type == 2)
  Inventory Manager
  @else
  Branch Manager
  @endif)
</h1>
<div class="py-12 ml-4 md:ml-0">
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg sm:px-24 lg:px-26">
      <div class="md:flex md:space-x-40">
          <a href="{{ route('products.index') }}">
            <div class="justify-center inline-flex bg-gray-200 rounded-2xl overflow-hidden shadow-lg" style="width:320px; height:128px;">
              <div class="px-6 py-4 text-center">                      
                <p class="text-gray-500">
                  Products
                </p>
                <div class="mt-4 font-bold text-5xl text-green-500">{{ App\Models\Product::count() }}</div>
              </div>
            </div>
          </a>
          <a href="{{ route('branches.index') }}">
            <div class="justify-center inline-flex bg-gray-200 rounded-2xl overflow-hidden shadow-lg" style="width:320px; height:128px;">
              <div class="px-6 py-4 text-center">
                @if(auth()->user()->type != 3)                        
                <p class="text-gray-500">
                  Branches
                </p>
                <div class="mt-4 font-bold text-5xl text-blue-500">{{ App\Models\Branch::count() }}</div>
                @else
                    @php 
                      $branch = App\Models\Branch::where('manager_id', auth()->user()->id)->first();
                    @endphp
                    <p class="text-gray-500">
                      Branch
                    </p>
                    <div class="mt-4 font-bold text-3xl text-blue-500">{{ $branch->name }}</div>
                @endif
              </div>
            </div>
          </a>               
      </div><br>
      <div class="md:flex md:space-x-40">
        <a @if(auth()->user()->type == 1) href="{{ route('reports.index') }}" @elseif(auth()->user()->type == 2) href="{{ route('transfers.index') }}" @else href="{{ route('inventory_requests.index') }}" @endif>
          <div class="justify-center inline-flex bg-gray-200 rounded-2xl overflow-hidden shadow-lg" style="width:320px; height:128px;">
            <div class="px-6 py-4 text-center"> 
              @if(auth()->user()->type != 2)                      
              <p class="text-gray-500">
                Pending Requests
              </p>
              @else
              <p class="text-gray-500">
                Pending Approvals
              </p>
              @endif
              <div class="mt-4 font-bold text-5xl text-yellow-500">
                @if(auth()->user()->type == '1')
                {{ App\Models\InventoryRequest::where('status', 1)->count() }}
                @elseif(auth()->user()->type == '2')
                {{ App\Models\InventoryRequest::where('type', 2)->where('status', 4)->count() }}
                @else 
                  @php 
                    $branch = App\Models\Branch::where('manager_id', auth()->user()->id)->first();
                  @endphp
                  {{ App\Models\InventoryRequest::where('req_branch_id', $branch->id)->where('status', 1)->count() }}
                @endif
              </div>
            </div>
          </div>
        </a>
        <a @if(auth()->user()->type == 1) href="{{ route('reports.index') }}" @else href="{{ route('transfers.index') }}" @endif>
          <div class="justify-center inline-flex bg-gray-200 rounded-2xl overflow-hidden shadow-lg" style="width:320px; height:128px;">
            <div class="px-6 py-4 text-center">                        
              <p class="text-gray-500">
                Pending Transfers
              </p>
              <div class="mt-4 font-bold text-5xl text-red-500">
                @if(auth()->user()->type != '3')
                {{ App\Models\InventoryRequest::where('type', 1)->where('status', '!=', 3)->count() }}
                @else 
                  @php 
                    $branch = App\Models\Branch::where('manager_id', auth()->user()->id)->first();
                  @endphp
                  {{ App\Models\InventoryRequest::where('type', 2)->where('branch_id', $branch->id)->whereIn('status', [1, 2])->count() }}
                @endif
              </div>
            </div>
          </div>
        </a>               
      </div><br>
      @if(auth()->user()->type == 3)
        @php 
          $branch = auth()->user()->branch;
          $products = App\Models\Product::with(['branchProducts' => function ($q) use ($branch) {
            $q->where('branch_id', $branch->id);
          }])->orderBy('id', 'desc')->paginate(25);
        @endphp
      <div class="mt-4">
        <h5 class="font-bold text-center text-gray-700 text-md">Branch's Inventory</h5><br>  
        <table class="w-full text-sm text-left text-gray-700 dark:text-gray-400">
          <thead class="text-sm text-gray-800 uppercase bg-gray-300 dark:bg-gray-700 dark:text-gray-400">
            <tr>
              <th scope="col" class="py-3 px-4">
                #
              </th>
              <th scope="col" class="py-3 px-4">
                Product
              </th>
              <th scope="col" class="py-3 px-4">
                Total Quantity
              </th>
            </tr>
          </thead>
          <tbody>                            
            @foreach($products as $product)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">                                    
              <td class="py-3 px-3">
                {{ $product->id }}
              </td>
              <td class="py-3 px-3">
                {{ $product->name }}
              </td>
              <td class="py-3 px-4">
                {{ $product->branchProducts->sum('quantity') }}
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>               
      </div><br>
      @endif
    </div>
  </div>
</div>
@endsection


@push('js')
@endpush