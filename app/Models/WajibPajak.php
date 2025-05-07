<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WajibPajak extends Model
{
    protected $keyType = 'string';

    protected $guarded = [''];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tagihans()
    {
        return $this->hasMany(Tagihan::class, 'wajib_pajak_id');
    }
}
