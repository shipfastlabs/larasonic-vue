<?php

// app/Models/PrivateBetaInvitation.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrivateBetaInvitation extends Model
{
    protected $fillable = [
        'email',
        'status',
        'access_code',
        'expire_at',
    ];

    protected $casts = [
        'expire_at' => 'datetime',
        'num_requests' => 'integer',
        'last_access_at' => 'datetime',
    ];
}
