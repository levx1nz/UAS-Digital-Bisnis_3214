<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'organizer_id', 'event_id', 'code', 'type', 'value',
        'min_purchase', 'max_usage', 'used_count',
        'starts_at', 'expires_at', 'is_active',
    ];

    protected $casts = [
        'starts_at'  => 'datetime',
        'expires_at' => 'datetime',
        'is_active'  => 'boolean',
    ];

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function validateFor(int $subtotal, ?int $eventId = null): array
    {
        if (!$this->is_active) {
            return [false, 'Kupon sedang tidak aktif.'];
        }
        $now = now();
        if ($this->starts_at && $now->lt($this->starts_at)) {
            return [false, 'Kupon belum mulai berlaku.'];
        }
        if ($this->expires_at && $now->gt($this->expires_at)) {
            return [false, 'Kupon sudah kedaluwarsa.'];
        }
        if (!is_null($this->max_usage) && $this->used_count >= $this->max_usage) {
            return [false, 'Kuota pemakaian kupon sudah habis.'];
        }
        if (!is_null($this->event_id) && $eventId && $this->event_id != $eventId) {
            return [false, 'Kupon tidak berlaku untuk event ini.'];
        }
        if ($subtotal < $this->min_purchase) {
            return [false, 'Belum memenuhi minimum pembelian Rp ' . number_format($this->min_purchase, 0, ',', '.') . '.'];
        }
        return [true, 'Kupon berhasil diterapkan.'];
    }

    public function discountFor(int $subtotal): int
    {
        if ($this->type === 'percent') {
            return (int) floor($subtotal * ($this->value / 100));
        }
        return min((int) $this->value, $subtotal);
    }
}