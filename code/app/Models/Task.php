<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    protected $fillable = [
        'user_id',
        'summary',
        'date',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public static $createRules = [
        'summary' => 'required|max:2500',
    ];
}
