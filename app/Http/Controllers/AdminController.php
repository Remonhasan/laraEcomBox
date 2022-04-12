<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
}
