<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['email_verified_at' => 'datetime', 'password' => 'hashed'];
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function activeUntil()
    {
        if ($this->subscriptions()->whereNull('ends_at')->exists()) {
            return null;
        }

        $end = $this->subscriptions()->where('ends_at', '>', now())->max('ends_at');

        return $end ? Carbon::parse($end) : null;
    }

    public function hasActiveSubscription(): bool
    {
        return $this->subscriptions()
            ->where(fn ($query) => $query->whereNull('ends_at')->orWhere('ends_at', '>', now()))
            ->exists();
    }
}
