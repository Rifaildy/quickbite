<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
      'order_number',
      'user_id',
      'canteen_id',
      'total_price',
      'status',
      'payment_status',
      'payment_type',
      'transaction_id',
      'barcode',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
      'total_price' => 'decimal:2',
  ];

  /**
   * Get the user that owns the order.
   */
  public function user()
  {
      return $this->belongsTo(User::class);
  }

  /**
   * Get the canteen that owns the order.
   */
  public function canteen()
  {
      return $this->belongsTo(Canteen::class);
  }

  /**
   * Get the order items for the order.
   */
  public function orderItems()
  {
      return $this->hasMany(OrderItem::class);
  }
}

