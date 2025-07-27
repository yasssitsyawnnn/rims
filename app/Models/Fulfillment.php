<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Branch;
use App\Models\Product;
use App\Models\User;

class Fulfillment extends Model
{
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function relased()
    {
        return $this->belongsTo(User::class, 'relased_by', 'id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'req_branch_id', 'id');
    }
}
