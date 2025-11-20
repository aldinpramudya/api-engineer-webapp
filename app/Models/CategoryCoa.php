<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoryCoa extends Model
{
    protected $table = 'category_coa';

    protected $fillable = [
        'name'
    ];

    public function masterCoa(): HasMany{
        return $this->hasMany(MasterCoa::class);
    }
}
