<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class FreelancerProfile extends Model
{
    protected $fillable = [
        'user_id',
        'bio',
        'hourly_rate',
        'availability_status',
        'avatar',
        'portfolio_links',
        'skills_summary',
    ];

    protected $casts = [
        'portfolio_links' => 'array',
        'skills_summary'  => 'array',
            'price' => 'decimal:2',
    'delivery_time_days' => 'integer',
    'hourly_rate' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


