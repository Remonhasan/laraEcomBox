<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FrontendController extends Controller
{    
    /**
     * home
     *
     * @return void
     */
    public function home()
    {
        return view('welcome');
    }
}
