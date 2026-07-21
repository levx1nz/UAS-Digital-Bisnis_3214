<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id', 'event_id', 'ticket_tier_id', 'ticket_tier_name', 'original_price',
        'coupon_code', 'discount', 'order_id', 'customer_name', 'customer_email',
        'customer_phone', 'total_price', 'status', 'snap_token'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ticketTier()
    {
        return $this->belongsTo(TicketTier::class);
    }
}