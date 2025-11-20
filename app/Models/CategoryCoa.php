<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoryCoa extends Model
{
    protected $table = 'categories_coa';
    public $timestamps = false;

    protected $fillable = [
        'name_category'
    ];

    public function masterCoa(): HasMany{
        return $this->hasMany(MasterCoa::class);
    }
}
