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
                <a href="{{ route('inventory_requests.index') }}" title="back" class="inline-flex items-center px-4 py-2 bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-900 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150" ><i class="fa-solid fa-arrow-left-long"></i></a><br><br>
                <br>
                <h1 class="text-center text-xl">Inventory Request</h1>
                <hr style="height:2px; background-color:#333; border:none;"><br>
                <div class="flex justify-between mb-2">
                    <span>
                        <span class="font-semibold">Product Name:</span>
                        @if(isset($inventory_request->product))
                            {{ $inventory_request->product->name }}
                        @endif
                    </span>
                    <span><span class="font-semibold">#</span>{{ $inventory_request->id }}</span>
                </div>
                @if(isset($inventory_request->product))
                <span class="font-semibold">Image</span><br>                
                <img class="h-36 w-36" src="{{ asset('storage') }}/{{ $inventory_request->product->image }}">
                <br><br>
                @endif
                <div class="flex justify-between mb-2">
                    <span>
                        <span class="font-semibold">Type:</span>
                        @if($inventory_request->type == 1)
                            New
                        @else
                            Transfer <br>
                            @if(isset($inventory_request->branch))
                                <span>(Request From Branch: {{ $inventory_request->branch->name }})</span>
                            @endif
                        @endif
                    </span>
                </div>
                <div class="flex justify-between mb-2">
                    <span><span class="font-semibold">Branch That Need Inventory:</span>
                    @if(isset($inventory_request->req_branch))
                        {{ $inventory_request->req_branch->name }}
                    @endif
                    </span>
                </div>
                <div class="flex justify-between mb-2">
                    <span><span class="font-semibold">Request Quantity:</span>
                        {{ $inventory_request->quantity }}
                    </span>
                </div>
                <div class="flex justify-between mb-2">
                    <span><span class="font-semibold">Fulfilled Quantity:</span>
                        {{ $inventory_request->fulfilled }}
                    </span>
                </div>
                <div class="flex justify-between mb-2">
                    <span><span class="font-semibold">Remaining Quantity:</span>
                        @if($inventory_request->remaining < 1)
                        0
                        @else
                        {{ $inventory_request->remaining }}
                        @endif
                    </span>
                </div>
                <div class="flex justify-between mb-2">
                    <span><span class="font-semibold">Status:</span>
                        @if($inventory_request->status == 3)
                        <span class="bg-gradient-to-tl from-green-600 to-lime-400 px-2 text-xs rounded py-1 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Fulfilled</span>
                        @elseif($inventory_request->status == 2)
                        <span class="bg-gradient-to-tl from-yellow-600 to-yellow-400 px-2 text-xs rounded py-1 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">P. Fulfilled</span>   
                        @else
                        <span class="bg-gradient-to-tl from-red-600 to-pink-400 px-2 text-xs rounded py-1 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Pending</span>
                        @endif
                    </span>
                </div>   
                <h5 class="text-base font-bold text-gray-700 text-center">Fulfill Inventory</h5>
                <table class="w-full text-sm text-left text-gray-700 dark:text-gray-400">
                    <thead class="text-sm text-gray-800 uppercase bg-gray-300 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="py-3 px-4">
                                #
                            </th>
                            <th scope="col" class="py-3 px-4">
                                Date
                            </th>
                            <th scope="col" class="py-3 px-4">
                                Quantity
                            </th>
                            <th scope="col" class="py-3 px-4">
                                Released By
                            </th>
                        </tr>
                    </thead>
                    <tbody>                            
                        @foreach($fulfillments as $fulfillment)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">                                    
                            <td class="py-3 px-3">
                                {{ $fulfillment->id }}
                            </td>
                            <td class="py-3 px-3">
                                {{ $fulfillment->created_at->format('Y-m-d') }}
                            </td>
                            <td class="py-3 px-4">
                                {{ $fulfillment->quantity }}
                            </td>
                            <td class="py-3 px-3">
                            @if(isset($fulfillment->relased))
                                {{ $fulfillment->relased->name }}
                            @endif
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