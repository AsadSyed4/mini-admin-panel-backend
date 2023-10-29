<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Company extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'email', 'logo', 'website'];

    /**
     * Relationship with employees table.
     */
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    /**
     * Get the notification routing information for the entity.
     *
     * @return string
     */
    public function routeNotificationFor($notification)
    {
        return $this->email;
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
