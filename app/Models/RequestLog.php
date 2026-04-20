<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestLog extends Model
{
    // protected $fillable = [
    //     'user_id',
    //     'method',
    //     'endpoint',
    //     'duration_ms',
    // ];
protected $fillable = [
    'user_id',
    'method',
    'endpoint',
    'duration_ms',
    'status_code',
    'query_count',
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
