<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = ['first_name', 'last_name', 'company_id', 'email', 'phone'];

    /**
     * Relationship with company table.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Convert created_at in human readable form.
     */
    public function getCreatedAtAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->diffForHumans();
    }

    /**
     * Convert updated_at in human readable form.
     */
    public function getUpdatedAtAttribute()
    {
        return Carbon::parse($this->attributes['updated_at'])->diffForHumans();
    }
}
