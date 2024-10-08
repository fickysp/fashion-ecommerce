<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable =[
        'image',
        'product_name',
        'desc',
        'price',
        'stock',
        'category'
    ];

    public function sizes(){
        return $this->hashMany(ProductSize::class);
    }
}
