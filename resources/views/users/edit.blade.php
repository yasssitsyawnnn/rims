@extends('layouts.app')
@section('bodycontent')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <a href="{{ route('users.index') }}" title="back" class="inline-flex items-center px-4 py-2 bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-900 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150" ><i class="fa-solid fa-arrow-left-long"></i></a><br><br>
                <h5 class="font-bold text-center text-gray-900 text-xl">Edit User</h5><br>                  
                <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div>
                        <label for="name">Full Name</label><br>
                        <input type="text" name="name" value="{{ $user->name }}" class="block w-96 appearance-none rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" placeholder="full name" required>
                    </div>
                    @error('name') <span class="text-red-500 error">{{ $message }}</span><br> @enderror
                    <br>
                    <div>
                        <label for="email">Email</label><br>
                        <input type="text" name="email" id="email" value="{{ $user->email }}" class="block w-96 appearance-none rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" placeholder="email" required>
                    </div>
                    <p id="danger_alert1" class="text-sm text-red-500 mb-2" style="display:none;"></p>
                    @error('email') <span class="text-red-500 error">{{ $message }}</span><br> @enderror
                    <br> 
                    <div>
                        <label for="contact">Contact Number</label><br>
                        <input type="number" name="contact" id="contact" value="{{ $user->contact }}" class="block w-96 appearance-none rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" placeholder="contact number" required>
                    </div>
                    <p id="danger_alert2" class="text-sm text-red-500 mb-2" style="display:none;"></p>
                    @error('contact') <span class="text-red-500 error">{{ $message }}</span><br> @enderror
                    <br>
                    <div>
                        <label for="type">Select User Type</label><br>
                        <select name="type" id="type" class="block w-96 appearance-none rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" required onchange="toggleUser()">
                            <option value="" disabled selected>Select type from here</option>
                            <option value="1" @if($user->type === 1) selected @endif>Admin</option>
                            <option value="2" @if($user->type === 2) selected @endif>Inventory Manager</option>
                            <option value="3" @if($user->type === 3) selected @endif>Branch Manager</option>
                        </select> 
                    </div>
                    @error('type') <span class="text-red-500 error">{{ $message }}</span><br> @enderror
                    <br>                     
                    <div>
                        <label for="status">Account Status</label><br>
                        <select name="status" class="block w-96 appearance-none rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" required>
                            <option value="" disabled>Select an account status from here</option>
                            <option value="3" @if($user->status === 3) selected @endif>Pending</option>
                            <option value="1" @if($user->status === 1) selected @endif>Active</option>
                            <option value="2" @if($user->status === 2) selected @endif>Deactivated</option>
                        </select> 
                    </div>
                    @error('status') <span class="text-red-500 error">{{ $message }}</span><br> @enderror
                    <br>
                    <button type="submit" class="passwordvalid disabled:opacity-25 inline-flex items-center px-4 py-2 bg-blue-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-900 focus:bg-blue-900 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-25">Update</button>                     
                </form>
            </div>
        </div>
    </div>
</div>
@endsection