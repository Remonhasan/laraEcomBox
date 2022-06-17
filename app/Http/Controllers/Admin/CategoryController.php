<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Category;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display the list of Category.
     *
     * @return void
     */
    public function index()
    {
        $data = [];

        $itemsPerPage = itemsPerPage();

        $categoryModel = new Category();
        $data['categoryModel'] = $categoryModel;

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

        $data['categories'] = $categoryModel->getCategories($args);

        return view('admin.category.list', compact('itemsPerPage'))->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        return view('admin.category.create');
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

                Category::create($inputs);

                DB::commit();

                Toastr::success('Category Created Successfully', 'Ecommerce', ["positionClass" => "toast-top-center"]);

                return redirect()->back();
            }

        } catch (\Exception $e) {
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
     * @param  mixed $categoryId
     * @return void
     */
    public function edit($categoryId)
    {
        $data = [];
        $data['category'] = Category::findorFail($categoryId);
        return view('admin.category.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  mixed $request
     * @param  mixed $categoryId
     * @return void
     */
    public function update(Request $request, $categoryId)
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

                $category = Category::findorfail($categoryId);
                $category->update($inputs);

                DB::commit();

                Toastr::success('Category Updated Successfully', 'Ecommerce', ["positionClass" => "toast-top-center"]);

                return redirect()->back();
            }

        } catch (\Exception $e) {
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
     * @param  mixed $categoryId
     * @return void
     */
    public function delete($categoryId)
    {
        $category = Category::where('id', $categoryId)->first();

        if ($category != null) {

            $category->delete();

            Toastr::success('Category Deleted Successfully', 'Ecommerce', ["positionClass" => "toast-top-center"]);

            // Redirect to list page.
            return redirect()->back();

        } else {
            return back()->withErrors(__('Sorry, Please Try Again!'));
        }
    }
}
