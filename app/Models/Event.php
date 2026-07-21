<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $fillable = [
        'organizer_id', 'category_id', 'title', 'description', 'date',
        'location', 'price', 'stock', 'poster_path', 'is_published'
    ];

    protected $casts = [
        'date' => 'datetime',
        'is_published' => 'boolean',
    ];

    public function category(): BelongsTo { return $this->belongsTo(Category::class); }

    public function organizer(): BelongsTo { return $this->belongsTo(User::class, 'organizer_id'); }

    public function reviews(): HasMany { return $this->hasMany(Review::class); }
    public function transactions(): HasMany { return $this->hasMany(Transaction::class); }

    public function ticketTiers(): HasMany
    {
        return $this->hasMany(TicketTier::class);
    }

    public function currentTier(): ?TicketTier
    {
        $tiers = $this->ticketTiers()->orderBy('sort_order')->orderBy('price')->get();
        if ($tiers->isEmpty()) {
            return null;
        }

        $active = $tiers->first(fn (TicketTier $t) => $t->isActiveNow() && $t->hasQuota());

        return $active ?? $tiers->last();
    }

    public function currentPrice(): int
    {
        $tier = $this->currentTier();
        return $tier ? (int) $tier->price : (int) $this->price;
    }
    
    public function scopePubliclyVisible($query)
    {
        return $query->where('is_published', true)
            ->where(function ($q) {
                $q->whereNull('organizer_id')
                    ->orWhereHas('organizer', function ($o) {
                        $o->where('account_status', 'approved');
                    });
            });
    }
}