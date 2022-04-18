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
        $data['allProducts'] = Product::all();
        return view('admin.product.list')->with($data);
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
        $slug = str_slug($request->name_en);
    
        if(isset($image))
        {
            //make unique name for image
            $currentDate = Carbon::now()->toDateString();
            $imageName  = $slug.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();
   
            if(!Storage::disk('public')->exists('product'))
            {
                Storage::disk('public')->makeDirectory('product');
            }
   
            $productImage = Image::make($image)->resize(1600,1066)->stream();
            Storage::disk('public')->put('product/'.$imageName,$productImage);
   
        } else {
            $imageName = "default.png";
        }

        $product = new Product();
        $product->category_id = $request->category_id;
        $product->subcategory_id = $request->subcategory_id;
        $product->name_en = $request->name_en;
        $product->name_bn = $request->name_en;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->is_active = $request->is_active;
        $product->is_stock = $request->is_stock;
        $product->price_with_discount = $request->price_with_discount;
        $product->size = $request->size;
        $product->image = $imageName;
        $product->save();
        // Product::create($inputs);
        Toastr::success('Product Created Successfully', 'Ecommerce', ["positionClass" => "toast-top-center"]);
        return redirect()->back();
    }
}
