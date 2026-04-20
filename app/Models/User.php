<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
        'city_id',
        'phone',
        'is_verified',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $casts = [
        'availability_status' => \App\Enums\AvailabilityStatus::class,
    ];

    protected $appends = [
        'full_name',
        'avatar_url',
        'rating_text',
        'member_since',
    ];

 
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function freelancerProfile()
    {
        return $this->hasOne(FreelancerProfile::class);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class)
                    ->withPivot('years_of_experience')
                    ->withTimestamps();
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'client_id');
    }

    public function bids()
    {
        return $this->hasMany(Bid::class, 'freelancer_id');
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function requestLogs()
    {
        return $this->hasMany(RequestLog::class);
    }

   
    // -------------------------------- Scopes---------------------------------------------------------
  

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('availability_status', 'available');
    }

    public function scopeTopRated($query)
    {
        return $query->orderByDesc('reviews_avg_rating');
    }
    public function scopeForFreelancerListing($query, ?string $skill = null, ?int $cityId = null)
{  // eager laoding 
        // solving n+1 problem 
// 5 query 
    return $query
  
        ->with(['skills', 'city'])
        ->withAvg('reviews', 'rating')
        ->withCount('projects')
        ->verified()
        ->when($skill, function ($q) use ($skill) {
            $q->whereHas('skills', fn ($q2) => $q2->where('name', $skill));
        })
        ->when($cityId, function ($q) use ($cityId) {
            $q->where('city_id', $cityId);
        })
        ->orderByDesc('reviews_avg_rating');
}
public function scopeForFreelancerProfile($query)
{
    return $query
        ->with(['skills', 'city', 'freelancerProfile', 'reviews'])
        ->withAvg('reviews', 'rating')
        ->withCount(['reviews', 'projects']);
} 

    
    // -------------------------------Accessors & Mutators-------------------------------------------
    

    protected function password(): Attribute
    {
        return Attribute::set(function ($value) {
            if (! $value) {
                return null;
            }

            if (password_get_info($value)['algo'] !== 0) {
                return $value;
            }

            return bcrypt($value);
        });
    }
// this n+1 problem 
    // protected function ratingText(): Attribute
    // {
    //     return Attribute::get(function () {
    // سيتم تنفيذ هذا الاستعلام في كل مرو=ة يتم قيه استدعاء السكوب
            // if ($this->reviews()->count() === 0) 
    // {
    //             return 'No reviews yet';
    //         }

    //         $avg = round($this->reviews()->avg('rating'), 1);

    //         return "{$avg} ★";
    //     });
    // }
// الحل هنا بالجلب المسبق للبيانات يتم حساب المعدل مرة واحدة وليس مع كل استخدام
    protected function ratingText(): Attribute
{

    return Attribute::get(function () {
        if ($this->reviews_count === 0) {
            return 'No reviews yet';
        }

        $avg = round($this->reviews_avg_rating, 1);

        return "{$avg} ★";
    });
}
    protected function phone(): Attribute
    {
        return Attribute::set(function ($value) {
            if (! $value) {
                return null;
            }

            $clean = preg_replace('/[^0-9]/', '', $value);
            return $clean;
        });
    }

    protected function avatarUrl(): Attribute
    {
        return Attribute::get(function () {
            if (! $this->avatar) {
                return asset('images/default-avatar.png');
            }

            return Storage::url($this->avatar);
        });
    }

    protected function memberSince(): Attribute
    {
        return Attribute::get(function () {
            if (! $this->created_at) {
                return null;
            }

            return 'Member since ' . $this->created_at->format('F Y');
        });
    }

    protected function fullName(): Attribute
    {
        return Attribute::get(function () {
            return trim($this->first_name . ' ' . $this->last_name);
        });
    }
    public function scopeFilterBySkill($query, ?string $skill)
{
    return $query->when($skill, function ($q) use ($skill) {
        $q->whereHas('skills', fn ($q2) => $q2->where('name', $skill));
    });
}
public function scopeFilterByCity($query, ?int $cityId)
{ 
    return $query->when($cityId, fn ($q) => $q->where('city_id', $cityId));
}
public function scopeSortByRating($query)
{
    return $query->orderByDesc('reviews_avg_rating');
}
// use scope to filtering "ocp"
}
