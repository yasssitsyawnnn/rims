<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Inventory Management System</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('assets/logo.png') }}">
        <!-- Tailwind CSS -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
        <link rel="stylesheet" href="https://unpkg.com/flowbite@1.5.5/dist/flowbite.min.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/monolith.min.css"/>

        <!-- font-awesome icons -->
        <script src="https://kit.fontawesome.com/2d49de291b.js" crossorigin="anonymous"></script>
        
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">
        @stack('style')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100"> 
            <div class="md:flex md:bg-white">
                <div class="hidden md:block md:flex w-2/5 md:w-1/5 h-screen sticky text-white top-0 bg-indigo-800 border-r hidden">
                    <div class="mx-auto py-5">
                        <ul>                        
                            <a href="{{ route('dashboard') }}"><li class="{{ (request()->segment(1) == 'dashboard') ? 'bg-indigo-950 border-indigo-950': '' }} px-3 py-1 flex space-x-2 mt-10 rounded-md border-indigo-800 cursor-pointer hover:bg-indigo-950 hover:border-indigo-950">					
                                <img src="{{ asset('assets/home.svg') }}" alt="Dashboard Icon" class="w-5 h-5">
                                <span class="font-semibold">Dashboard</span>
                            </li></a>
                            <a href="{{ route('products.index') }}"><li class="{{ (request()->segment(1) == 'products') ? 'bg-indigo-950 border-indigo-950': '' }} px-3 py-1 flex space-x-2 mt-4 rounded-md border-indigo-800 cursor-pointer hover:bg-indigo-950 hover:border-indigo-950">					
                                <img src="{{ asset('assets/product.svg') }}" alt="Products Icon" class="w-5 h-5">
                                <span class="font-semibold">Products</span>
                            </li></a>
                            <a href="{{ route('branches.index') }}"><li class="{{ (request()->segment(1) == 'branches') ? 'bg-indigo-950 border-indigo-950': '' }} px-3 py-1 flex space-x-2 mt-4 rounded-md border-indigo-800 cursor-pointer hover:bg-indigo-950 hover:border-indigo-950">					
                                <img src="{{ asset('assets/branch.svg') }}" alt="Branches Icon" class="w-5 h-5">
                                <span class="font-semibold">Branches</span>
                            </li></a>
                            @if(auth()->user()->type != 1)
                            <a href="{{ route('update_stocks.index') }}"><li class="{{ (request()->segment(1) == 'update_stocks') ? 'bg-indigo-950 border-indigo-950': '' }} px-3 py-1 flex space-x-2 mt-4 rounded-md border-indigo-800 cursor-pointer hover:bg-indigo-950 hover:border-indigo-950">					
                                <img src="{{ asset('assets/stock.svg') }}" alt="Update Icon" class="w-5 h-5">
                                <span class="font-semibold">Update Inventory</span>
                            </li></a>
                            <a href="{{ route('inventory_requests.index') }}"><li class="{{ (request()->segment(1) == 'inventory_requests') ? 'bg-indigo-950 border-indigo-950': '' }} px-3 py-1 flex space-x-2 mt-4 rounded-md border-indigo-800 cursor-pointer hover:bg-indigo-950 hover:border-indigo-950">					
                                <img src="{{ asset('assets/request.svg') }}" alt="Request Icon" class="w-5 h-5">
                                <span class="font-semibold">Request Inventory</span>
                            </li></a>
                            <a href="{{ route('transfers.index') }}"><li class="{{ (request()->segment(1) == 'transfers') ? 'bg-indigo-950 border-indigo-950': '' }} px-3 py-1 flex space-x-2 mt-4 rounded-md border-indigo-800 cursor-pointer hover:bg-indigo-950 hover:border-indigo-950">					
                                <img src="{{ asset('assets/transfer.svg') }}" alt="Transfer Icon" class="w-5 h-5">
                                <span class="font-semibold">Transfer Inventory</span>
                            </li></a>
                            @endif
                            <a href="{{ route('reports.index') }}"><li class="{{ (request()->segment(1) == 'reports') ? 'bg-indigo-950 border-indigo-950': '' }} px-3 py-1 flex space-x-2 mt-4 rounded-md border-indigo-950 cursor-pointer hover:bg-indigo-950 hover:border-indigo-950">					
                                <img src="{{ asset('assets/report.svg') }}" alt="Reports Icon" class="w-5 h-5">
                                <span class="font-semibold">Reports</span>
                            </li></a>
                            <a href="{{ route('profile.index') }}"><li class="{{ (request()->segment(1) == 'profile') ? 'bg-indigo-950 border-indigo-950': '' }} px-3 py-1 flex space-x-2 mt-4 rounded-md border-indigo-950 cursor-pointer hover:bg-indigo-950 hover:border-indigo-950">					
                                <img src="{{ asset('assets/profile.svg') }}" alt="Profile Icon" class="w-5 h-5">
                                <span class="font-semibold">Profile</span>
                            </li></a>
                            @if(auth()->user()->type == 1)
                            <a href="{{ route('users.index') }}"><li class="{{ (request()->segment(1) == 'users') ? 'bg-indigo-950 border-indigo-950': '' }} px-3 py-1 flex space-x-2 mt-4 rounded-md border-indigo-800 cursor-pointer hover:bg-indigo-950 hover:border-indigo-950">					
                                <img src="{{ asset('assets/users.svg') }}" alt="Users Icon" class="w-5 h-5">    
                                <span class="font-semibold">Users</span>
                            </li></a>
                            @endif
                            <a href="{{ route('logout') }}"><li class="{{ (request()->segment(1) == 'logout') ? 'bg-indigo-950 border-indigo-950': '' }} px-3 py-1 flex space-x-2 mt-4 rounded-md border-indigo-800 cursor-pointer hover:bg-indigo-950 hover:border-indigo-950">					
                                <img src="{{ asset('assets/logout.svg') }}" alt="Log Out Icon" class="w-5 h-5">
                                <span class="font-semibold">Log Out</span>
                            </li></a>          
                        </ul>
                    </div>
                </div>

                <div class="block md:hidden">
                    <div class="bg-indigo-800 text-white">
                        <div class="sticky container mx-auto px-6 py-4 flex justify-end items-center text-gray-900">                    
                            <div class="block md:hidden">
                                <button id="menu-toggle" class="text-white focus:outline-none">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                                </button>
                            </div>
                        </div> 
                        <div id="mobile-menu" class="hidden md:hidden">
                            <nav class="flex flex-col items-center font-semibold text-white space-y-2 py-2">
                                <ul>                     
                                    <a href="{{ route('dashboard') }}"><li class="{{ (request()->segment(1) == 'dashboard') ? 'bg-indigo-950 border-indigo-950': '' }} px-3 py-1 flex space-x-2 mt-4 rounded-md border-indigo-800 cursor-pointer hover:bg-indigo-950 hover:border-indigo-950">					
                                        <img src="{{ asset('assets/home.svg') }}" alt="Dashboard Icon" class="w-5 h-5">
                                        <span class="font-semibold">Dashboard</span>
                                    </li></a>
                                    <a href="{{ route('products.index') }}"><li class="{{ (request()->segment(1) == 'products') ? 'bg-indigo-950 border-indigo-950': '' }} px-3 py-1 flex space-x-2 mt-4 rounded-md border-indigo-800 cursor-pointer hover:bg-indigo-950 hover:border-indigo-950">					
                                        <img src="{{ asset('assets/product.svg') }}" alt="Products Icon" class="w-5 h-5">
                                        <span class="font-semibold">Products</span>
                                    </li></a>
                                    <a href="{{ route('branches.index') }}"><li class="{{ (request()->segment(1) == 'branches') ? 'bg-indigo-950 border-indigo-950': '' }} px-3 py-1 flex space-x-2 mt-4 rounded-md border-indigo-800 cursor-pointer hover:bg-indigo-950 hover:border-indigo-950">					
                                        <img src="{{ asset('assets/branch.svg') }}" alt="Branches Icon" class="w-5 h-5">
                                        <span class="font-semibold">Branches</span>
                                    </li></a>
                                    @if(auth()->user()->type != 1)
                                    <a href="{{ route('update_stocks.index') }}"><li class="{{ (request()->segment(1) == 'update_stocks') ? 'bg-indigo-950 border-indigo-950': '' }} px-3 py-1 flex space-x-2 mt-4 rounded-md border-indigo-800 cursor-pointer hover:bg-indigo-950 hover:border-indigo-950">					
                                        <img src="{{ asset('assets/stock.svg') }}" alt="Update Icon" class="w-5 h-5">
                                        <span class="font-semibold">Update Inventory</span>
                                    </li></a>
                                    <a href="{{ route('inventory_requests.index') }}"><li class="{{ (request()->segment(1) == 'inventory_requests') ? 'bg-indigo-950 border-indigo-950': '' }} px-3 py-1 flex space-x-2 mt-4 rounded-md border-indigo-800 cursor-pointer hover:bg-indigo-950 hover:border-indigo-950">					
                                        <img src="{{ asset('assets/request.svg') }}" alt="Request Icon" class="w-5 h-5">
                                        <span class="font-semibold">Request Inventory</span>
                                    </li></a>
                                    <a href="{{ route('transfers.index') }}"><li class="{{ (request()->segment(1) == 'transfers') ? 'bg-indigo-950 border-indigo-950': '' }} px-3 py-1 flex space-x-2 mt-4 rounded-md border-indigo-800 cursor-pointer hover:bg-indigo-950 hover:border-indigo-950">					
                                        <img src="{{ asset('assets/transfer.svg') }}" alt="Transfer Icon" class="w-5 h-5">
                                        <span class="font-semibold">Transfer Inventory</span>
                                    </li></a>
                                    @endif
                                    <a href="{{ route('reports.index') }}"><li class="{{ (request()->segment(1) == 'reports') ? 'bg-indigo-950 border-indigo-950': '' }} px-3 py-1 flex space-x-2 mt-4 rounded-md border-indigo-950 cursor-pointer hover:bg-indigo-950 hover:border-indigo-950">					
                                        <img src="{{ asset('assets/report.svg') }}" alt="Reports Icon" class="w-5 h-5">
                                        <span class="font-semibold">Reports</span>
                                    </li></a>
                                    <a href="{{ route('profile.index') }}"><li class="{{ (request()->segment(1) == 'profile') ? 'bg-indigo-950 border-indigo-950': '' }} px-3 py-1 flex space-x-2 mt-4 rounded-md border-indigo-950 cursor-pointer hover:bg-indigo-950 hover:border-indigo-950">					
                                        <img src="{{ asset('assets/profile.svg') }}" alt="Profile Icon" class="w-5 h-5">
                                        <span class="font-semibold">Profile</span>
                                    </li></a>
                                    @if(auth()->user()->type == 1)
                                    <a href="{{ route('users.index') }}"><li class="{{ (request()->segment(1) == 'users') ? 'bg-indigo-950 border-indigo-950': '' }} px-3 py-1 flex space-x-2 mt-4 rounded-md border-indigo-800 cursor-pointer hover:bg-indigo-950 hover:border-indigo-950">					
                                        <img src="{{ asset('assets/users.svg') }}" alt="Users Icon" class="w-5 h-5">    
                                        <span class="font-semibold">Users</span>
                                    </li></a>
                                    @endif
                                    <a href="{{ route('logout') }}"><li class="{{ (request()->segment(1) == 'logout') ? 'bg-indigo-950 border-indigo-950': '' }} px-3 py-1 flex space-x-2 mt-4 rounded-md border-indigo-800 cursor-pointer hover:bg-indigo-950 hover:border-indigo-950">					
                                        <img src="{{ asset('assets/logout.svg') }}" alt="Log Out Icon" class="w-5 h-5">
                                        <span class="font-semibold">Log Out</span>
                                    </li></a>           
                                </ul>
                            </nav>                
                        </div>               
                    </div>
                </div>

                <main class="min-h-screen w-full bg-white border-l" style="overflow: auto;"> 
                    
                    @yield('bodycontent')		
                </main>
            </diV>               
        </div> 
               
        <script src="https://unpkg.com/flowbite@1.5.5/dist/flowbite.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
        @stack('js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('menu-toggle').addEventListener('click', function() {
                    var menu = document.getElementById('menu');
                    var mobileMenu = document.getElementById('mobile-menu');
                    if (mobileMenu.classList.contains('hidden')) {
                        mobileMenu.classList.remove('hidden');
                    } else {
                        mobileMenu.classList.add('hidden');
                    }
                });
            });
        </script>
    </body>
</html>