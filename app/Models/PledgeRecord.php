<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PledgeRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'pledge_text_version',
        'signature_base64',
        'signature_path',
        'signed_at',
        'app_version',
        'device_uuid',
        'local_uuid',
        'synced_at',
    ];

    protected $casts = [
        'signed_at'  => 'datetime',
        'synced_at'  => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Hide the raw base64 from API responses to reduce payload size.
     */
    protected $hidden = ['signature_base64'];
}
