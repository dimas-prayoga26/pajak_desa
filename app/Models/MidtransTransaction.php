<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MidtransTransaction extends Model
{
    protected $keyType = 'string';

    protected $guarded = [''];

    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class);
    }
}
