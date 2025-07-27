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
                <a href="{{ route('transfers.index') }}" title="back" class="inline-flex items-center px-4 py-2 bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-900 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150" ><i class="fa-solid fa-arrow-left-long"></i></a><br><br>
                <br>
                <h1 class="text-center text-xl">Transfer Inventory</h1>
                <hr style="height:2px; background-color:#333; border:none;"><br>
                <div class="flex justify-between mb-2">
                    <span>
                        <span class="font-semibold">Product Name:</span>
                        @if(isset($transfer->product))
                            {{ $transfer->product->name }}
                        @endif
                    </span>
                    <span><span class="font-semibold">#</span>{{ $transfer->id }}</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span>
                        <span class="font-semibold">Type:</span>
                        @if($transfer->type == 1)
                            New
                        @else
                            Transfer <br>
                            @if(isset($transfer->branch))
                                <span>(Request From Branch: {{ $transfer->branch->name }})</span>
                            @endif
                        @endif
                    </span>
                </div>                
                <div class="flex justify-between mb-2">
                    <span><span class="font-semibold">Branch That Need Inventory:</span>
                    @if(isset($transfer->req_branch))
                        {{ $transfer->req_branch->name }}
                    @endif
                    </span>
                </div>
                <div class="flex justify-between mb-2">
                    <span><span class="font-semibold">Request Quantity:</span>
                        {{ $transfer->quantity }}
                    </span>
                </div>
                <div class="flex justify-between mb-2">
                    <span><span class="font-semibold">Fulfilled Quantity:</span>
                        {{ $transfer->fulfilled }}
                    </span>
                </div>
                <div class="flex justify-between mb-2">
                    <span><span class="font-semibold">Remaining Quantity:</span>
                        @if($transfer->remaining < 1)
                        0
                        @else
                        {{ $transfer->remaining }}
                        @endif
                    </span>
                </div>
                <div class="flex justify-between mb-2">
                    <span><span class="font-semibold">Status:</span>
                        @if($transfer->status == 3)
                        <span class="bg-gradient-to-tl from-green-600 to-lime-400 px-2 text-xs rounded py-1 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Fulfilled</span>
                        @elseif($transfer->status == 2)
                        <span class="bg-gradient-to-tl from-yellow-600 to-yellow-400 px-2 text-xs rounded py-1 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">P. Fulfilled</span>   
                        @else
                        <span class="bg-gradient-to-tl from-red-600 to-pink-400 px-2 text-xs rounded py-1 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Pending</span>
                        @endif
                    </span>
                </div>
                @if(isset($transfer->branch) && isset($transfer->product) && $transfer->type == 2)
                <div class="flex justify-between mb-2 text-red-800">
                    <span><span class="font-semibold">This Branch's Inventory:</span>
                    @php 
                        $branch_product = App\Models\BranchProduct::where('branch_id', $transfer->branch->id)->where('product_id', $transfer->product->id)->first();
                    @endphp
                    
                    @if($branch_product != null)
                        {{ $branch_product->quantity }}
                    @endif
                    </span>
                </div>
                @endif 
                <br><br>
                <h5 class="text-base font-bold text-gray-700">Fulfill Inventory</h5>
                <form action="{{ route('transfers.fulfill', $transfer) }}" method="POST" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div>
                        <label for="quantity">Enter Fulfill Quantity</label><br>
                        <input type="number" min="0" name="quantity" id="quantity" class="block w-96 appearance-none rounded-md border border-gray-300 px-3 py-2" placeholder="quantity" required>
                    </div>
                    @error('quantity') <span class="text-red-500 error">{{ $message }}</span><br> @enderror
                    <br> 
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 focus:bg-green-500 active:bg-green-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-25">Fulfill</button>                        
                </form><br><br>
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
                            <th scope="col" class="py-3 px-4">
                                Actions
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
                            <td class="py-3 px-4">
                                <button type="button" value="{{ $fulfillment->id }}" data-modal-toggle="deletePost" class="deleteBtn inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"><img src="{{ asset('assets/trash.svg') }}" alt="Delete Icon" class="w-3 h-3"></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>               
            </div>
        </div>
    </div>
</div>

<!-- Delete modal -->
<div id="deletePost" tabindex="-1" class="bg-opacity-50 fixed top-0 left-0 right-0 z-50 hidden p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
    <div class="relative w-full h-full max-w-md md:h-auto">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-toggle="deletePost">
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="p-6 text-center">
                <form method="POST" action="{{ route('fulfillment.destroy', 'data_id') }}">
                    @csrf
                    @method('DELETE')
                    <svg aria-hidden="true" class="mx-auto mb-4 text-gray-400 w-14 h-14 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to delete this data?</h3>
                    <input type="hidden" name="data_id"  id="data_id"> 
                    <button type="submit" class="text-white bg-red-600 hover:bg-red-500 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                        Yes, Delete
                    </button>
                    <button data-modal-toggle="deletePost" type="button" class="text-white bg-gray-400 hover:bg-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">No, Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    document.getElementById('quantity').addEventListener('input', function() {
        if (this.value < 0) {
            this.value = 0;
        }
    });
</script>

<script>
    $(document).ready(function () {
        $('.deleteBtn').click(function (e){
            e.preventDefault();
            
            var data_id = $(this).val();
            $('#data_id').val(data_id);
        });
    });
</script>
@endpush