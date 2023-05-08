<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //Show Register / Create Form
    public function create()
    {
        return view('users.register');
    }

    // Create New User
    public function store(Request $request)
    {
        $formFields = $request->validate([
            'name' =>'required|string|max:255',
            'email' =>'required|string|email|unique:users',
            'password' =>'required|string|min:6|confirmed',
        ]);
        //Hash password
       $formFields['password'] = bcrypt($formFields['password']);

       //create User
       $user = User::create($formFields);
       

       //Login
       auth()->login($user);

       return redirect('/')->with('message', 'User created successfully and Logged in.'); 

       
    }

    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('message', 'You have logged out successfully.');
    }

    //show login form
    public function login()
    {
        return view('users.login');
    }

    ///authenticate user
    public function authenticate(Request $request)
    {
         $formFields = $request->validate([
            'email' =>'required|string|email',
            'password' =>'required'
        ]);

        if(auth()->attempt($formFields)){
            $request->session()->regenerate(); 
            
            return redirect('/')->with('message', 'User logged in successfully.');
        }

        back()->withErrors(['email' => 'Invalid Credentials.'])->onlyInput('email');
    }
}