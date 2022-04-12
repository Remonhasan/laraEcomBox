<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{    
    /**
     * loginForm
     *
     * @return void
     */
    public function loginForm ()
    {
        return view ('admin.login');
    }
    
    /**
     * dashboard
     *
     * @return void
     */
    public function dashboard ()
    {
        return view ('admin-layouts.index');
    }

   /**
     * login Check
     *
     * @param  mixed $request
     * @return void
     */
    public function login(Request $request)
    {
        $loginCheck = $request->all();

        if(Auth::guard('admin')->attempt(['email'=>$loginCheck['email'],'password'=>$loginCheck['password']])){
            return redirect()->route('admin.dashboard')->with('error','Admin Login Successfully');
        }else{
            return redirect()->back()->with('error','Invalid Email or Password');
        }
    }

     /**
     * Logout
     *
     * @return void
     */
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('login.form')->with('error','Admin Logout Successfully');
    }

     /**
     * Register Page as a Admin
     *
     * @return void
     */
    public function registerForm()
    {
       return view('admin.register');
    }

    /**
     * Register Logic
     *
     * @return void
     */
    public function register(Request $request)
    {
      Admin::insert([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'created_at' => Carbon::now(),
      ]);
      return redirect()->route('login.form')->with('error','Admin Created Successfully!');
    }
}
