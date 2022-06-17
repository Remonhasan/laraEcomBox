<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Category;
use App\Models\Admin\SubCategory;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        $data = [];
        $data['categories'] = Category::all();
        return view('admin.subcategory.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request)
    {
        try {
            $inputs = $request->all();
            $inputs['is_active'] = (int) $inputs['is_active'];

            // Validate.
            $rules = array(
                'name_en' => 'required|max:255',
                'name_bn' => 'required|max:255',
            );

            $validator = Validator::make($inputs, $rules);

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput($request->all);
            } else {
                // Starting database transaction
                DB::beginTransaction();

                SubCategory::create($inputs);

                DB::commit();

                Toastr::success('Subcategory Created Successfully', 'Ecommerce', ["positionClass" => "toast-top-center"]);

                return redirect()->back();
            }

        } catch (\Exception$e) {
            // Rollback all transaction if error occurred
            DB::rollBack();

            return back()
                ->withErrors($e->getMessage())
                ->withInput($request->all);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  mixed $subcategoryId
     * @return void
     */
    public function edit($subcategoryId)
    {
        $data = [];
        $data['categories'] = Category::all();
        $data['subcategory'] = SubCategory::findorFail($subcategoryId);
        return view('admin.subcategory.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  mixed $request
     * @param  mixed $subcategoryId
     * @return void
     */
    public function update(Request $request, $subcategoryId)
    {

        try {
            $inputs = $request->all();
            $inputs['is_active'] = (int) $inputs['is_active'];

            // Validate.
            $rules = array(
                'name_en' => 'required|max:255',
                'name_bn' => 'required|max:255',
            );

            $validator = Validator::make($inputs, $rules);

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput($request->all);
            } else {
                // Starting database transaction
                DB::beginTransaction();

                $subcategory = SubCategory::findorfail($subcategoryId);
                $subcategory->update($inputs);

                DB::commit();

                Toastr::success('SubCategory Updated Successfully', 'Ecommerce', ["positionClass" => "toast-top-center"]);

                return redirect()->back();
            }

        } catch (\Exception$e) {
            // Rollback all transaction if error occurred.
            DB::rollBack();

            return back()
                ->withErrors($e->getMessage())
                ->withInput($request->all);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  mixed $subcategoryId
     * @return void
     */
    public function delete($subcategoryId)
    {
        $subcategory = SubCategory::where('id', $subcategoryId)->first();

        if ($subcategory != null) {

            $subcategory->delete();

            Toastr::success('Category Deleted Successfully', 'Ecommerce', ["positionClass" => "toast-top-center"]);

            // Redirect to list page.
            return redirect()->back();

        } else {
            return back()->withErrors(__('Sorry, Please Try Again!'));
        }
    }

}
