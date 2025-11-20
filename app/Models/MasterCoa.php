<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterCoa extends Model
{
    protected $table = "masters_coa";
    public $timestamps = false;

    protected $fillable = [
        "code",
        "name",
        "category_coa_id"
    ];

    public function categoryCoa(): BelongsTo{
        return $this->belongsTo(CategoryCoa::class);
    }

    public function transaction(): HasMany{
        return $this->hasMany(Transaction::class);
    }


}
