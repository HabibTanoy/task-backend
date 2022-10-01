<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $hidden = ['created_at', 'updated_at'];

    protected $guarded = ['id'];
    protected $table = 'products';

    public function images()
    {
        return $this->hasMany(Image::class, 'productId', 'id');
    }
}
