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
}
