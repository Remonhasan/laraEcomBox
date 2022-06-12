<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
