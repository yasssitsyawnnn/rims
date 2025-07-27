<table class="w-full text-sm text-left text-gray-700 dark:text-gray-400">
    <thead class="text-sm text-gray-800 uppercase bg-gray-300 dark:bg-gray-700 dark:text-gray-400">
        <tr>
            <th scope="col" class="py-3 px-4">Branch</th>
            <th scope="col" class="py-3 px-4">Manager</th>
            <th scope="col" class="py-3 px-4">Stock</th>
        </tr>
    </thead>
    <tbody>                            
        @foreach($branch_products as $branch_product)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">                                    
                <td class="py-3 px-3">
                    {{ $branch_product->branch->name ?? 'N/A' }}
                </td>
                <td class="py-3 px-3">
                    {{ $branch_product->branch->manager->name ?? 'N/A' }}
                </td>
                <td class="py-3 px-3">
                    {{ $branch_product->quantity }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>