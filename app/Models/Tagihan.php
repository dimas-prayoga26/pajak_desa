<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    protected $keyType = 'string';

    protected $guarded = [''];

    public function wajibPajak()
    {
        return $this->belongsTo(WajibPajak::class);
    }
}
