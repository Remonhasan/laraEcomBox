<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Admin\Category;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class CategoryController extends Controller
{
    public function index ()
    {
        $data = [];
        $data['allCategories'] = Category::all();
        return view('admin.category.list')->with($data);
    }

    public function create ()
    {
        return view('admin.category.create');
    }

    public function store (Request $request)
    {
        $inputs = $request->all();
        Category::create($inputs);
        Toastr::success('Category Created Successfully', 'Ecommerce', ["positionClass" => "toast-top-center"]);
        return redirect()->back();
    }

    public function edit ($categoryId)
    {
        $data = [];
        $data['category'] = Category::findorFail($categoryId);
        return view('admin.category.edit')->with($data);
    }

    public function update (Request $request, $categoryId)
    {
        $inputs = $request->all();
        $category = Category::findorfail($categoryId);
        $category->update($inputs);
        Toastr::success('Category Updated Successfully', 'Ecommerce', ["positionClass" => "toast-top-center"]);
        return redirect()->back();
    }

    public function delete ($categoryId)
    {
        $category = Category::find($categoryId);
        $category->delete();
        Toastr::success('Category Deleted Successfully', 'Ecommerce', ["positionClass" => "toast-top-center"]);
        return redirect()->back();
    }
}

