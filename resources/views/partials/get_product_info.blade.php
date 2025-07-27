<table class="w-full text-sm text-left text-gray-700 dark:text-gray-400">
    <thead class="text-sm text-gray-800 uppercase bg-gray-300 dark:bg-gray-700 dark:text-gray-400">
        <tr>
            <th scope="col" class="py-3 px-4">
                Image
            </th>
            <th scope="col" class="py-3 px-4">
                Name
            </th>
            <th scope="col" class="py-3 px-4">
                Total Quantity
            </th>
        </tr>
    </thead>
    <tbody>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <td class="py-3 px-3">
                <img src="{{ asset('storage') }}/{{ $product->image }}" alt="image" width="75px" height="75px"> 
            </td>
            <td class="py-3 px-3">
                {{ $product->name }}
            </td>
            <td class="py-3 px-4">
                {{ $product->branchProducts->sum('quantity') }}
            </td>
        </tr>
    </tbody>
</table>