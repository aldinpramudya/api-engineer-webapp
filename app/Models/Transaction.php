<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $table = "transactions";
    public $timestamps = false;

    protected $fillable = [
        "date",
        "masters_coa_id",
        "description",
        "debit",
        "credit",
    ];

    public function masterCoa(): BelongsTo{
        return $this->belongsTo(MasterCoa::class, "masters_coa_id", 'id');
    }
}
