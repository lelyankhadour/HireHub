<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{ use HasFactory;
    protected $fillable = [
        'project_id',
        'freelancer_id',
        'amount',
        'cover_letter',
        'delivery_days',
        'status',
    ];
   protected $casts = [
 'amount'
 =>"integer",
    'delivery_days' => 'integer',
    ];
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}
