<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;

    public $table = 'products';

    protected $fillable = [
        'category_id', 
        'subcategory_id', 
        'name_en', 
        'name_bn', 
        'description', 
        'image', 
        'size', 
        'price', 
        'discount', 
        'price_with_discount', 
        'is_stock', 
        'is_active'
    ];

    /**
     * Get all products. Allowed pagination and Search.
     *
     * @param  mixed $args
     * @return void
     */
    public static function getProducts($args = array())
    {
        $lang = config('app.locale');
        $name = "name_{$lang}";

        $defaults = array(
            'exclude' => array(),
            'name_en' => null, // int|array
            'name_bn' => null, // int|array
            'is_active' => null, // int|array
            'order' => array(
                'products.id' => 'desc',
                "products.$name" => 'asc',
            ),
            'items_per_page' => -1,
            'paginate' => false, // ignored, if 'items_per_page' = -1
        );

        $arguments = parseArguments($args, $defaults);

        $products = DB::table('products');

        $products = $products->select(
            'products.*',
        );

        if (!empty($arguments['exclude'])) {
            $products = $products->whereNotIn('products.id', $arguments['exclude']);
        }

        if (!empty($arguments['is_active'])) {
            if ('active' === $arguments['is_active']) {
                $products = $products->where('products.is_active', 1);
            } elseif ('inactive' === $arguments['is_active']) {
                $products = $products->where('products.is_active', 0);
            }
        }

        if (!empty($arguments['name'])) {
            $search_name = $arguments['name'];
            $products = $products->where(
                function ($products) use ($search_name) {
                    $products->where('products.name_en', 'LIKE', '%' . $search_name . '%');
                    $products->orWhere('products.name_bn', 'LIKE', '%' . $search_name . '%');
                }
            );
        }

        foreach ($arguments['order'] as $orderBy => $order) {
            $products = $products->orderBy($orderBy, $order);
        }

        if ($arguments['items_per_page'] == '-1') {
            $products = $products->get();
        } else {
            if (true == $arguments['paginate']) {
                $products = $products->paginate(intval($arguments['items_per_page']));
            } else {
                $products = $products->take(intval($arguments['items_per_page']));
                $products = $products->get();
            }
        }

        return $products;
    }
}
