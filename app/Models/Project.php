<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{ use HasFactory;
protected $table = 'projects';

    protected $fillable = [
        'client_id',
        'title',
        'description',
        'budget_type',
        'budget_amount',
        'deadline',
        'status',
    ];

    protected $appends = [
        'formatted_budget',
        'deadline_status',
    ];

    protected $casts = [
        'budget_amount' => 'decimal:2',
    ];

   
    //---------------------------- Relationships----------------------------------------------
    

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    
    //-----------------------Scopes---------------------------------------------------
   

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeBudgetAbove($query, $amount)
    {
        return $query->where('budget_amount', '>=', $amount);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month);
    }
   public function scopeForProjectListing($query)
{
    return $query
        // ->with(['client', 'tags'])
        ->with(['client.city', 'tags']) 
        // ->with(['reviews']) 

        ->withAvg('reviews', 'rating')
        ->withCount('bids')
        ->open()
        ->orderByDesc('created_at');
}
public function scopeForProjectDetails($query)
{
    return $query
        ->with(['client', 'tags', 'attachments', 'bids.freelancer'])
        ->withAvg('reviews', 'rating')
        ->withCount('bids');
}
// this add for review 
public function scopeClosed($query)
{
    return $query->where('status', 'closed');
}

public function scopeCompletedByFreelancer($query, $freelancerId)
{
    return $query->whereHas('bids', function ($q) use ($freelancerId) {
        $q->where('freelancer_id', $freelancerId)
          ->where('status', 'accepted');
    });
}
    //------------- Accessors------------------
    
    protected function deadlineStatus(): Attribute
    {
        return Attribute::get(function () {
            if (! $this->deadline) {
                return null;
            }

            if (now()->greaterThan($this->deadline)) {
                return 'Expired';
            }

            $days = now()->diffInDays($this->deadline);

            return "{$days} days left";
        });
    }

    protected function formattedBudget(): Attribute
    {
        return Attribute::get(function () {
            if ($this->budget_type === 'fixed') {
                return "{$this->budget_amount} USD";
            }

            if ($this->budget_type === 'hourly') {
                return "\${$this->budget_amount}/hr";
            }

            return null;
        });
    }
 public function scopeFilterByTag($query, ?string $tag)
{
    return $query->when($tag, function ($q) use ($tag) {
        $q->whereHas('tags', fn ($q2) => $q2->where('name', $tag));
    });
}
 public function scopeSortByNewest($query)
{
    return $query->orderByDesc('created_at');
}
public function scopeFilterByBudgetRange($query, ?int $min, ?int $max)
{
    return $query
        ->when($min, fn ($q) => $q->where('budget_amount', '>=', $min))
        ->when($max, fn ($q) => $q->where('budget_amount', '<=', $max));
}

}
