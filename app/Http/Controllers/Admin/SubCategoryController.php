<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Admin\Category;
use App\Models\Admin\SubCategory;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class SubCategoryController extends Controller
{
    public function index ()
    {
        $data = [];
        $data['allSubcategories'] = SubCategory::all();
        return view('admin.subcategory.list')->with($data);
    }

    public function create ()
    {
        $data = [];
        $data['categories'] = Category::all();
        return view('admin.subcategory.create')->with($data);
    }

    public function store (Request $request)
    {
        $inputs = $request->all();
        SubCategory::create($inputs);
        Toastr::success('Subcategory Created Successfully', 'Ecommerce', ["positionClass" => "toast-top-center"]);
        return redirect()->back();
    }

    public function edit ($subcategoryId)
    {
        $data = [];
        $data['categories'] = Category::all();
        $data['subcategory'] = SubCategory::findorFail($subcategoryId);
        return view('admin.subcategory.edit')->with($data);
    }

    public function update (Request $request, $subcategoryId)
    {
        $inputs = $request->all();
        $subcategory = SubCategory::findorfail($subcategoryId);
        $subcategory->update($inputs);
        Toastr::success('SubCategory Updated Successfully', 'Ecommerce', ["positionClass" => "toast-top-center"]);
        return redirect()->back();
    }

    public function delete ($subcategoryId)
    {
        $subcategory = SubCategory::find($subcategoryId);
        $subcategory->delete();
        Toastr::success('SubCategory Deleted Successfully', 'Ecommerce', ["positionClass" => "toast-top-center"]);
        return redirect()->back();
    }

}
