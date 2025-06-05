<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;
    protected $table = 'addresses';

    protected $fillable = [
        'user_id',
        'address_type',
        'address_details',
        'primary',
    ];

    protected $casts = [
        'address_details' => 'array',  // Casts JSON to PHP array automatically
    ];

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
