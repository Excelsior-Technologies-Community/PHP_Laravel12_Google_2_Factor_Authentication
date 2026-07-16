<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoginHistory extends Model
{
    protected $fillable = [

        'user_id',

        'login_method',

        'status',

        'ip_address',

        'user_agent',

        'logged_in_at',

    ];

    protected $casts = [

        'logged_in_at' => 'datetime',

    ];

    /**
     * Login belongs to user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
