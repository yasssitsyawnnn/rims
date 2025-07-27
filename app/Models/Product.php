<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BranchProduct;

class Product extends Model
{
    public function branchProducts()
    {
        return $this->hasMany(BranchProduct::class, 'product_id', 'id');
    }
}
