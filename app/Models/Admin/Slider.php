<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    public $table = 'sliders';

    protected $fillable = ['title', 'sub_title', 'image', 'is_active'];
}
