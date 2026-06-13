<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriLayanan extends Model
{
    protected $guarded = ['id'];

    public function layanans(): HasMany
    {
        return $this->hasMany(Layanan::class);
    }
}