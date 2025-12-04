<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Provider extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'companyName',
        'contactName',
        'email',
        'phoneNumber',
        'address',
        'city',
        'state',
        'postalCode',
        'country',
        'userId',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that created this provider.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId');
    }
}