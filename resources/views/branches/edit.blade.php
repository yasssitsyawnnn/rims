@extends('layouts.app')
@section('bodycontent')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <a href="{{ route('branches.index') }}" title="back" class="inline-flex items-center px-4 py-2 bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-900 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150" ><i class="fa-solid fa-arrow-left-long"></i></a><br><br>
                <h5 class="font-bold text-center text-gray-900 text-xl">Edit Branch</h5><br>                
                <form action="{{ route('branches.update', $branch) }}" method="POST" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div>
                        <label for="name">Branch Name</label><br>
                        <input type="text" name="name" value="{{ $branch->name }}" class="block w-96 appearance-none rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" placeholder="branch name" required>
                    </div>
                    @error('name') <span class="text-red-500 error">{{ $message }}</span><br> @enderror
                    <br>
                    <div>
                        @php
                            $managers = App\Models\User::where('type', 3)->where('status', 1)->get();
                        @endphp
                        <label for="manager_id">Branch Manager</label><br>
                        <select name="manager_id" id="manager_id" class="block w-96 appearance-none rounded-md border border-gray-300 px-3 py-2" required>
                            <option value="" disabled selected>Select a manager from here</option>
                            @foreach($managers as $manager)
                            <option value="{{ $manager->id }}" @if($branch->manager_id == $manager->id) selected @endif>{{ $manager->name }}</option>
                            @endforeach
                        </select> 
                    </div>
                    @error('manager_id') <span class="text-red-500 error">{{ $message }}</span><br> @enderror
                    <br>     
                    <div>
                        <label for="status">Branch Status</label><br>
                        <select name="status" class="block w-96 appearance-none rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" required>
                            <option value="" disabled>Select an branch status from here</option>
                            <option value="1" @if($branch->status === 1) selected @endif>Active</option>
                            <option value="2" @if($branch->status === 2) selected @endif>Deactivated</option>
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