<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'no_hp', 'google_id', 'organizer_name', 'account_status'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'organizer_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }

    public function isSuperadmin(): bool { return $this->role === 'superadmin'; }
    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isPlatformStaff(): bool { return in_array($this->role, ['admin', 'superadmin']); }
    public function isOrganizer(): bool { return $this->role === 'organizer'; }
    public function isApprovedOrganizer(): bool { return $this->isOrganizer() && $this->account_status === 'approved'; }
}