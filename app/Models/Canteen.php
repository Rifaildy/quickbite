<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Canteen extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'user_id',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Get the user that owns the canteen.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the seller that owns the canteen.
     * This is an alias for the user relationship.
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the menus for the canteen.
     */
    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    /**
     * Get the orders for the canteen.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

