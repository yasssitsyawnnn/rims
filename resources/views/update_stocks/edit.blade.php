@extends('layouts.app')
@section('bodycontent')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <a href="{{ route('update_stocks.index') }}" title="back" class="inline-flex items-center px-4 py-2 bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-900 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150" ><i class="fa-solid fa-arrow-left-long"></i></a><br><br>
                <h5 class="font-bold text-center text-gray-900 text-xl">Update Stock</h5><br>                
                <form action="{{ route('update_stocks.update', $update_stock) }}" method="POST" enctype="multipart/form-data">
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
                                <option value="{{ $product->id }}" data-image="{{ asset('storage/' . $product->image) }}" @if($update_stock->product_id == $product->id) selected @endif>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('product_id') <span class="text-red-500 error">{{ $message }}</span><br> @enderror
                    <br>
                    <div>
                        <label for="quantity">Quantity</label><br>
                        <input type="number" min="0" name="quantity" id="quantity" value="{{ $update_stock->quantity }}" class="block w-96 appearance-none rounded-md border border-gray-300 px-3 py-2" placeholder="quantity" required>
                    </div>
                    @error('quantity') <span class="text-red-500 error">{{ $message }}</span><br> @enderror
                    <br>                
                    <button type="submit" class="disabled:opacity-25 inline-flex items-center px-4 py-2 bg-blue-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-900 focus:bg-blue-900 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-25">Update</button>
                </form>
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
@endpush