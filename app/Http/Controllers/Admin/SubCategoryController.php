<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Admin\Category;
use App\Models\Admin\SubCategory;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class SubCategoryController extends Controller
{
     /**
     * Display the list of Subcategory.
     *
     * @return void
     */
    public function index()
    {
        $data = [];

        $itemsPerPage = itemsPerPage();

        $subcategoryModel = new SubCategory();
        $data['subcategoryModel'] = $subcategoryModel;

        $args = array(
            'items_per_page' => $itemsPerPage,
            'paginate' => true,
        );

        // Push Filter/Search Parameters.
        $args = filterParams(
            $args,
            array(
                'name' => 'name',
                'is_active' => 'is_active',
            )
        );

        $data['subcategories'] = $subcategoryModel->getSubcategories($args);

        return view('admin.subcategory.list', compact('itemsPerPage'))->with($data);
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
