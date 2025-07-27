<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id', 'desc')->paginate(25);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'type' => 'required',
            'contact' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:users,contact',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{6,}$/',
            'confirm_password' => 'required|same:password',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->type = $request->type;
        $user->contact = $request->contact;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        if($user){
            return redirect()->route('users.index')->with('status', 'User registrated successfully.');        
        }
        return redirect()->route('users.index')->with('delete', 'User registration faild, try again.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'type' => 'required',
            'contact' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:users,contact, ' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'status' => 'required',
        ]);

        $user->name = $request->name;
        $user->type = $request->type;
        $user->contact = $request->contact;
        $user->email = $request->email;
        $user->status = $request->status;
        $user->save();

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(Request $request)
    {
        $user = User::find($request->data_id);
        if($user)
        {
            if($user->type == '1'){
                return redirect()->route('users.index')->with('delete', 'Admin user can not delete.');
            }
            $user->delete();
            return redirect()->route('users.index')->with('delete', 'User deleted successfully.');
        }
        else
        {
            return redirect()->route('users.index')->with('delete', 'No user found!.');
        }    
    }

    public function emailcheck(Request $request)
    {
        $email = $request->input("email");
        $success = false;
        $user = User::where('email', $email)->first();
        if($user == null){
            $success = true;
        }else{
            $success = false;
        }

        return response()->json([
            "success" => $success
        ]);
    }

    public function contactcheck(Request $request)
    {
        $contact = $request->input("contact");
        $success = false;
        $user = User::where('contact', $contact)->first();
        if($user == null){
            $success = true;
        }else{
            $success = false;
        }

        return response()->json([
            "success" => $success
        ]);
    }
}
