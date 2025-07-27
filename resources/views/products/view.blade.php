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

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <a href="{{ route('products.index') }}" title="back" class="inline-flex items-center px-4 py-2 bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-900 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150" ><i class="fa-solid fa-arrow-left-long"></i></a><br><br>
                <br>
                <h1 class="text-center text-xl">Product Details</h1>
                <hr style="height:2px; background-color:#333; border:none;"><br>
                <div class="flex justify-between mb-2">
                    <span><span class="font-semibold">Product Name:</span>{{ $product->name }}</span>
                    <span><span class="font-semibold">#</span>{{ $product->id }}</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span><span class="font-semibold">Product Category:</span>{{ $product->category }}</span>
                </div> 
                <div class="flex justify-between mb-2">
                    <span><span class="font-semibold">Product Colour:</span>{{ $product->color }}</span>
                </div> 
                <div class="flex justify-between mb-2">
                    <span><span class="font-semibold">Product Price:</span>{{ $product->price }}</span>
                </div> 
                <div class="flex justify-between mb-2">
                    <p><strong>SKU:</strong> {{ $product->sku }}</p>
                </div> 
                @if(auth()->user()->type == 3)
                <div class="flex justify-between mb-2">
                    <span><span class="font-semibold">Branch's Product Stock:</span>{{ $product->branchProducts->sum('quantity') }}</span>
                </div> 
                @endif
                <div class="flex justify-between mb-2">
                    <span><span class="font-semibold">Status:</span>
                        @if($product->status == 2)                                    
                        <span class="bg-gradient-to-tl from-red-600 to-pink-400 px-2 text-xs rounded py-1 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Deactivated</span>
                        @else
                        <span class="bg-gradient-to-tl from-green-600 to-lime-400 px-2 text-xs rounded py-1 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Active</span>
                        @endif
                    </span>
                </div>
                <br><br>
                @php 
                    $branch_products = App\Models\BranchProduct::where('product_id', $product->id)->get();
                @endphp
                <table class="w-full text-sm text-left text-gray-700 dark:text-gray-400">
                    <thead class="text-sm text-gray-800 uppercase bg-gray-300 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="py-3 px-4">
                                Branch
                            </th>
                            <th scope="col" class="py-3 px-4">
                                Manager
                            </th>
                            <th scope="col" class="py-3 px-4">
                                Stock
                            </th>
                        </tr>
                    </thead>
                    <tbody>                            
                        @foreach($branch_products as $branch_product)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">                                    
                            <td class="py-3 px-3">
                                @if(isset($branch_product->branch)){{ $branch_product->branch->name }}@endif
                            </td>
                            <td class="py-3 px-3">
                                @if(isset($branch_product->branch->manager)){{ $branch_product->branch->manager->name }}@endif
                            </td>
                            <td class="py-3 px-3">
                                {{ $branch_product->quantity }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection