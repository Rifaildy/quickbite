<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'favorable_type',
        'favorable_id',
    ];

    /**
     * Get the user that owns the favorite.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the favorable model.
     */
    public function favorable()
    {
        return $this->morphTo();
    }
    /**
     * Get the canteen if the favorable type is a canteen.
     */
    public function canteen()
    {
        return $this->belongsTo(Canteen::class, 'favorable_id')
            ->where('favorable_type', 'App\Models\Canteen');
    }

    /**
     * Get the menu if the favorable type is a menu.
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'favorable_id')
            ->where('favorable_type', 'App\Models\Menu');
    }
}

