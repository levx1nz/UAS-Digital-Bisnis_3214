<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketTier extends Model
{
    protected $fillable = [
        'event_id', 'name', 'price', 'quota', 'sold',
        'starts_at', 'ends_at', 'sort_order',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at'   => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function isActiveNow(): bool
    {
        $now = now();
        $startOk = is_null($this->starts_at) || $now->gte($this->starts_at);
        $endOk   = is_null($this->ends_at)   || $now->lte($this->ends_at);
        return $startOk && $endOk;
    }

    public function hasQuota(): bool
    {
        return is_null($this->quota) || $this->sold < $this->quota;
    }
}