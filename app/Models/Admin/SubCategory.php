<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SubCategory extends Model
{
    use HasFactory;

    public $table = 'subcategories';

    protected $fillable = ['category_id', 'name_en', 'name_bn', 'is_active'];

    /**
     * Get all SubCategories. Allowed pagination and Search.
     *
     * @param  mixed $args
     * @return void
     */
    public static function getSubcategories($args = array())
    {
        $lang = config('app.locale');
        $name = "name_{$lang}";

        $defaults = array(
            'exclude' => array(),
            'name_en' => null, // int|array
            'name_bn' => null, // int|array
            'is_active' => null, // int|array
            'order' => array(
                'subcategories.id' => 'desc',
                "subcategories.$name" => 'asc',
            ),
            'items_per_page' => -1,
            'paginate' => false, // ignored, if 'items_per_page' = -1
        );

        $arguments = parseArguments($args, $defaults);

        $subcategories = DB::table('subcategories');

        $subcategories = $subcategories->select(
            'subcategories.*',
        );

        if (!empty($arguments['exclude'])) {
            $subcategories = $subcategories->whereNotIn('subcategories.id', $arguments['exclude']);
        }

        if (!empty($arguments['is_active'])) {
            if ('active' === $arguments['is_active']) {
                $subcategories = $subcategories->where('subcategories.is_active', 1);
            } elseif ('inactive' === $arguments['is_active']) {
                $subcategories = $subcategories->where('subcategories.is_active', 0);
            }
        }

        if (!empty($arguments['name'])) {
            $search_name = $arguments['name'];
            $subcategories = $subcategories->where(
                function ($subcategories) use ($search_name) {
                    $subcategories->where('subcategories.name_en', 'LIKE', '%' . $search_name . '%');
                    $subcategories->orWhere('subcategories.name_bn', 'LIKE', '%' . $search_name . '%');
                }
            );
        }

        foreach ($arguments['order'] as $orderBy => $order) {
            $subcategories = $subcategories->orderBy($orderBy, $order);
        }

        if ($arguments['items_per_page'] == '-1') {
            $subcategories = $subcategories->get();
        } else {
            if (true == $arguments['paginate']) {
                $subcategories = $subcategories->paginate(intval($arguments['items_per_page']));
            } else {
                $subcategories = $subcategories->take(intval($arguments['items_per_page']));
                $subcategories = $subcategories->get();
            }
        }

        return $subcategories;
    }

}
