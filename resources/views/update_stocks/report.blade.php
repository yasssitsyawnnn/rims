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
        <div class="bg-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="overflow-x-auto">
                    <form method="GET" action="{{ url('reports') }}">                    
                        <div class="mt-4 ml-4">
                            <div class="flex">
                                <div>
                                    <label for="start">Start Date</label><br>
                                    <input type="date" name="start" id="start" value="{{ $start }}" class="block w-48 appearance-none rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" required>
                                </div>
                                <div class="ml-2">
                                    <label for="end">End Date</label><br>
                                    <input type="date" name="end" id="end" value="{{ $end }}" class="block w-48 appearance-none rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" required>
                                </div>                                
                                @if(auth()->user()->type != 3)
                                    @php 
                                        $branches = App\Models\Branch::where('status', 1)->get();
                                    @endphp
                                <div class="ml-2">
                                    <label for="branch">Branch</label><br>
                                    <select name="branch_id" id="branch_id" class="block w-48 appearance-none rounded-md border border-gray-300 px-3 py-2">
                                        <option value="0" selected>All</option>
                                        @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" @if($branch_id == $branch->id) selected @endif>{{ $branch->name }} (Manager: @if(isset($branch->manager)){{ $branch->manager->name }}@endif)</option>
                                        @endforeach
                                    </select>
                                </div>      
                                @endif                          
                            </div>
                            <div class="mt-2">
                                @php
                                    use App\Models\Product;

                                    $products = Product::where('status', 1)->get();
                                @endphp
                                <label for="product_fillter">Product</label><br>
                                <select name="product_fillter" id="product_fillter" class="block w-48 appearance-none rounded-md border border-gray-300 px-3 py-2">
                                    <option value="0" selected>All</option>
                                </select>
                            </div> 
                            <button type="submit" class="mt-2 inline-flex items-center px-2 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-400 focus:bg-blue-400 active:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-25">
                                GET
                            </button>
                        </div>
                    </form><br>
                    @if($start != null && $end != null)
                    <h1 class="font-semibold mb-2 text-center text-lg">Stock Update</h1>
                    <table class="w-full text-sm text-left text-gray-700 dark:text-gray-400">
                        <thead class="text-sm text-gray-800 uppercase bg-gray-300 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="py-3 px-6">
                                   #
                                </th>
                                <th scope="col" class="py-3 px-6">
                                    Product
                                </th>
                                <th scope="col" class="py-3 px-6">
                                    Color
                                </th>
                                <th scope="col" class="py-3 px-6">
                                    Branch
                                </th>
                                <th scope="col" class="py-3 px-6">
                                    Date
                                </th>                                
                                <th scope="col" class="py-3 px-6">
                                    Quantity
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($update_stocks as $update_stock)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">                                    
                                <td class="py-3 px-6">
                                    {{ $update_stock->id }}
                                </td>
                                <td class="py-3 px-6">
                                    @if(isset($update_stock->product))
                                    {{ $update_stock->product->name }}
                                    @endif
                                </td>
                                <td class="py-3 px-6">
                                    @if(isset($update_stock->product))
                                    {{ $update_stock->product->color }}
                                    @endif
                                </td>
                                <td class="py-3 px-6">
                                    @if(isset($update_stock->branch))
                                    {{ $update_stock->branch->name }}
                                    @endif
                                </td>
                                <td class="py-3 px-6">
                                    {{ $update_stock->created_at->format('Y-m-d') }}
                                </td>
                                <td class="py-3 px-6">
                                    {{ $update_stock->quantity }}
                                </td>
                            </tr>
                            @endforeach
                            <tr class="bg-gray-200 border-b dark:bg-gray-900 dark:border-gray-700">                                    
                                <td class="py-3 px-6 text-end" colspan="6">
                                    <b>Total</b>
                                </td>
                                <td class="py-3 px-6">
                                    <b>{{ $sum }}</b>
                                </td>
                            </tr>
                        </tbody>
                    </table><br><br>

                    <h1 class="font-semibold mb-2 text-center text-lg">Stock Fulfillments</h1>
                    <table class="w-full text-sm text-left text-gray-700 dark:text-gray-400">
                        <thead class="text-sm text-gray-800 uppercase bg-gray-300 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="py-3 px-6">
                                   #
                                </th>
                                <th scope="col" class="py-3 px-6">
                                    Product
                                </th>
                                <th scope="col" class="py-3 px-6">
                                    Color
                                </th>
                                <th scope="col" class="py-3 px-6">
                                    Branch
                                </th>
                                <th scope="col" class="py-3 px-6">
                                    Date
                                </th>                                
                                <th scope="col" class="py-3 px-6">
                                    Quantity
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($fulfillments as $fulfillment)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">                                    
                                <td class="py-3 px-6">
                                    {{ $fulfillment->id }}
                                </td>
                                <td class="py-3 px-6">
                                    @if(isset($fulfillment->product))
                                    {{ $fulfillment->product->name }}
                                    @endif
                                </td>
                                <td class="py-3 px-6">
                                    @if(isset($fulfillment->product))
                                    {{ $fulfillment->product->color }}
                                    @endif
                                </td>
                                <td class="py-3 px-6">
                                    @if(isset($fulfillment->branch))
                                    {{ $fulfillment->branch->name }}
                                    @endif
                                </td>
                                <td class="py-3 px-6">
                                    {{ $fulfillment->created_at->format('Y-m-d') }}
                                </td>
                                <td class="py-3 px-6">
                                    {{ $fulfillment->quantity }}
                                </td>
                            </tr>
                            @endforeach
                            <tr class="bg-gray-200 border-b dark:bg-gray-900 dark:border-gray-700">                                    
                                <td class="py-3 px-6 text-end" colspan="6">
                                    <b>Total</b>
                                </td>
                                <td class="py-3 px-6">
                                    <b>{{ $fulfillment_sum }}</b>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    @else
                    <div class="flex justify-center">
                        <img src="{{ asset('assets/alert.svg') }}" alt="Alert Icon" class="w-4 h-4 mt-1">
                        <h1 class="text-md text-red-500 font-semibold dark:text-red-400">&nbsp;Select a date range first!</h1>
                    </div>
                    @endif
                </div>                                                                       
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    const startPeriod = document.getElementById('start');
    const endPeriod = document.getElementById('end');

    startPeriod.addEventListener('change', () => {
        endPeriod.min = startPeriod.value;
    });

    endPeriod.addEventListener('change', () => {
        startPeriod.max = endPeriod.value;
    });

    $(document).ready(function() {
        function formatOption(option) {
            if (!option.id) return option.text;

            // Skip image if value is 0
            if (option.id === "0") {
                return $('<span>' + option.text + '</span>');
            }

            var img = $(option.element).data('image');
            return $('<span><img src="' + img + '" class="w-6 h-6 inline-block mr-2" /> ' + option.text + '</span>');
        }

        $('#product_fillter').select2({
            templateResult: formatOption,
            templateSelection: formatOption
        });
    });
</script>
@endpush