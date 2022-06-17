<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    use HasFactory;

    public $table = 'categories';

    protected $fillable = ['name_en', 'name_bn', 'is_active'];

    /**
     * Get all Categories. Allowed pagination and Search.
     *
     * @param  mixed $args
     * @return void
     */
    public static function getCategories($args = array())
    {
        $lang = config('app.locale');
        $name = "name_{$lang}";

        $defaults = array(
            'exclude' => array(),
            'name_en' => null, // int|array
            'name_bn' => null, // int|array
            'is_active' => null, // int|array
            'order' => array(
                'categories.id' => 'desc',
                "categories.$name" => 'asc',
            ),
            'items_per_page' => -1,
            'paginate' => false, // ignored, if 'items_per_page' = -1
        );

        $arguments = parseArguments($args, $defaults);

        $categories = DB::table('categories');

        $categories = $categories->select(
            'categories.*',
        );

        if (!empty($arguments['exclude'])) {
            $categories = $categories->whereNotIn('categories.id', $arguments['exclude']);
        }

        if (!empty($arguments['is_active'])) {
            if ('active' === $arguments['is_active']) {
                $categories = $categories->where('categories.is_active', 1);
            } elseif ('inactive' === $arguments['is_active']) {
                $categories = $categories->where('categories.is_active', 0);
            }
        }

        if (!empty($arguments['name'])) {
            $search_name = $arguments['name'];
            $categories = $categories->where(
                function ($categories) use ($search_name) {
                    $categories->where('categories.name_en', 'LIKE', '%' . $search_name . '%');
                    $categories->orWhere('categories.name_bn', 'LIKE', '%' . $search_name . '%');
                }
            );
        }

        foreach ($arguments['order'] as $orderBy => $order) {
            $categories = $categories->orderBy($orderBy, $order);
        }

        if ($arguments['items_per_page'] == '-1') {
            $categories = $categories->get();
        } else {
            if (true == $arguments['paginate']) {
                $categories = $categories->paginate(intval($arguments['items_per_page']));
            } else {
                $categories = $categories->take(intval($arguments['items_per_page']));
                $categories = $categories->get();
            }
        }

        return $categories;
    }

}
