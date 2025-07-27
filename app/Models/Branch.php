<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\BranchProduct;

class Branch extends Model
{
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id', 'id');
    }

    public function products()
    {
        return $this->hasMany(BranchProduct::class);
    }
}
