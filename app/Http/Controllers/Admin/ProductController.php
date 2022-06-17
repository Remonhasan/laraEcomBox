<?php

namespace App\Http\Controllers\Admin;

use File;
use Illuminate\Http\Request;
use App\Models\Admin\Product;
use App\Models\Admin\Category;
use App\Models\Admin\SubCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;



class ProductController extends Controller
{
    public function index ()
    {
        $data = [];

        $itemsPerPage = itemsPerPage();

        $productModel = new Product();
        $data['productModel'] = $productModel;

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

        $data['products'] = $productModel->getProducts($args);

        return view('admin.product.list', compact('itemsPerPage'))->with($data);
    }

    public function create ()
    {
        $data = [];
        $data['categories'] = Category::all();
        $data['subcategories'] = SubCategory::all();
        return view('admin.product.create')->with($data);
    }

    public function store (Request $request)
    {
        $inputs = $request->all();

        $image = $request->file('image');

        if(isset($image))
        {
            $productImageName = time() . '-' . $request->name_en . '.' . $request->image->extension();


            if(!Storage::disk('public')->exists('product'))
            {
                Storage::disk('public')->makeDirectory('product');
            }
            

            $request->image->move(public_path('product', $productImageName));

   
        } else {
            $productImageName = "default.png";
        }

        // $image = $request->file('image');
        // $slug = str_slug($request->name_en);
    
        // if(isset($image))
        // {
        //     //make unique name for image
        //     $currentDate = Carbon::now()->toDateString();
        //     $imageName  = $slug.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();
   
        //     if(!Storage::disk('public')->exists('product'))
        //     {
        //         Storage::disk('public')->makeDirectory('product');
        //     }
   
        //     $productImage = Image::make($image)->resize(1600,1066)->stream();
        //     Storage::disk('public')->put('product/'.$imageName,$productImage);
   
        // } else {
        //     $imageName = "default.png";
        // }

        $inputs['image'] = $productImageName;
        Product::create($inputs);
        Toastr::success('Product Created Successfully', 'Ecommerce', ["positionClass" => "toast-top-center"]);
        return redirect()->back();
    }
}
