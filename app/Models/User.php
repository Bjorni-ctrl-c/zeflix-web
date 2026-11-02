<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function watchlists(): HasMany
    {
        return $this->hasMany(Watchlist::class);
    }

    public function watchlistFilms(): BelongsToMany
    {
        return $this->belongsToMany(Film::class, 'watchlists')->withTimestamps();
    }

    public function watchProgresses(): HasMany
    {
        return $this->hasMany(WatchProgress::class);
    }

    public function isInWatchlist(Film $film): bool
    {
        return $this->watchlistFilms()->where('film_id', $film->id)->exists();
    }

    public function getWatchProgress(Film $film): ?WatchProgress
    {
        return $this->watchProgresses()->where('film_id', $film->id)->first();
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function activeUntil(): ?Carbon
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
