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
                    @if(auth()->user()->type != '3' && $to_approves != null && sizeof($to_approves) > 0)
                    <h5 class="font-bold text-center text-gray-700 text-md">Pending Approvals</h5><br> 
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
                                    Type
                                </th>
                                <th scope="col" class="py-3 px-4">
                                    Quantity
                                </th>                                
                                <th scope="col" class="py-3 px-4">
                                    Fulfilled
                                </th>
                                <th scope="col" class="py-3 px-4">
                                    Remaining
                                </th>
                                <th scope="col" class="py-3 px-4">
                                    Status
                                </th>
                                <th scope="col" class="py-3 px-4">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>                            
                            @foreach($to_approves as $to_approve)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">                                    
                                <td class="py-3 px-3">
                                    {{ $to_approve->id }}
                                </td>
                                <td class="py-3 px-3">
                                @if(isset($to_approve->product))
                                    {{ $to_approve->product->name }}
                                @endif
                                </td>
                                <td class="py-3 px-3">
                                @if($to_approve->type == 1)
                                New
                                @else
                                Transfer <br>
                                    @if(isset($to_approve->branch))
                                        <span class="text-xs">({{ $to_approve->branch->name }})</span>
                                    @endif
                                @endif
                                </td>
                                <td class="py-3 px-4">
                                    {{ $to_approve->quantity }}
                                </td>
                                <td class="py-3 px-4">
                                    {{ $to_approve->fulfilled }}
                                </td>
                                <td class="py-3 px-4">
                                    @if($to_approve->remaining < 1)
                                    0
                                    @else
                                    {{ $to_approve->remaining }}
                                    @endif
                                </td>                                
                                <td class="py-3 px-3">
                                    @if($to_approve->status == 4)
                                    <span class="bg-gradient-to-tl from-pink-600 to-pink-400 px-2 text-xs rounded py-1 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Pending Approval</span>
                                    @elseif($to_approve->status == 5)
                                    <span class="bg-gradient-to-tl from-red-700 to-red-500 px-2 text-xs rounded py-1 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Rejected</span>
                                    @else
                                    <span class="bg-gradient-to-tl from-green-600 to-lime-500 px-2 text-xs rounded py-1 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Approved</span>
                                    @endif
                                </td>                                
                                <td class="py-3 px-4">
                                    <a href="{{ route('transfers.approve', $to_approve) }}" title="approve request" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150"><img src="{{ asset('assets/check.svg') }}" alt="Check Icon" class="w-3 h-3"></a>
                                    <a href="{{ route('transfers.reject', $to_approve) }}" title="reject request" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"><img src="{{ asset('assets/xmark.svg') }}" alt="Xmark Icon" class="w-3 h-3"></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br><br>
                    @endif
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
                                    Type
                                </th>
                                <th scope="col" class="py-3 px-4">
                                    Quantity
                                </th>                                
                                <th scope="col" class="py-3 px-4">
                                    Fulfilled
                                </th>
                                <th scope="col" class="py-3 px-4">
                                    Remaining
                                </th>
                                <th scope="col" class="py-3 px-4">
                                    Status
                                </th>
                                <th scope="col" class="py-3 px-4">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>                            
                            @foreach($transfers as $transfer)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">                                    
                                <td class="py-3 px-3">
                                    {{ $transfer->id }}
                                </td>
                                <td class="py-3 px-3">
                                @if(isset($transfer->product))
                                    {{ $transfer->product->name }}
                                @endif
                                </td>
                                <td class="py-3 px-3">
                                @if($transfer->type == 1)
                                New
                                @else
                                Transfer <br>
                                    @if(isset($transfer->branch))
                                        <span class="text-xs">({{ $transfer->branch->name }})</span>
                                    @endif
                                @endif
                                </td>
                                <td class="py-3 px-4">
                                    {{ $transfer->quantity }}
                                </td>
                                <td class="py-3 px-4">
                                    {{ $transfer->fulfilled }}
                                </td>
                                <td class="py-3 px-4">
                                    @if($transfer->remaining < 1)
                                    0
                                    @else
                                    {{ $transfer->remaining }}
                                    @endif
                                </td>                                
                                <td class="py-3 px-3">
                                    @if($transfer->status == 3)
                                     <span class="bg-gradient-to-tl from-green-600 to-lime-500 px-2 text-xs rounded py-1 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Fulfilled</span>
                                    @elseif($transfer->status == 2)
                                    <span class="bg-gradient-to-tl from-yellow-600 to-lime-400 px-2 text-xs rounded py-1 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">P. Fulfilled</span>   
                                    @else
                                    <span class="bg-gradient-to-tl from-yellow-600 to-yellow-400 px-2 text-xs rounded py-1 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Pending</span>
                                    @endif
                                </td>                                
                                <td class="py-3 px-4">
                                    <a href="{{ route('transfers.view', $transfer) }}" title="fulfill request" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"><img src="{{ asset('assets/folder_open.svg') }}" alt="View Icon" class="w-3 h-3"></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $transfers->links() }}                    
                </div>                                                                       
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
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