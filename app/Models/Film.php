<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Film extends Model
{
    protected $guarded = [];
    protected $casts = ['published_at' => 'datetime'];

    public function scopePublished($q) { return $q->where('status', 'published'); }

    public function canBeWatchedBy(?User $user): bool
    {
        if ($this->status !== 'published' && !($user && $user->isAdmin())) return false;
        if ($this->access === 'free') return true;
        return $user && ($user->isAdmin() || $user->hasActiveSubscription());
    }

    /** URL video untuk pemutar. Untuk R2: URL bertanda tangan yang kedaluwarsa dalam 3 jam. */
    public function playbackUrl(): string
    {
        if ($this->video_provider !== 'r2') return (string) $this->video_ref;

        return Storage::disk('r2')->temporaryUrl($this->video_ref, now()->addHours(3));
    }

    public function posterImageUrl(): ?string
    {
        if (blank($this->poster_url)) return null;

        if (Str::startsWith($this->poster_url, ['https://', 'http://'])) {
            return $this->poster_url;
        }

        return Storage::disk('r2-posters')->temporaryUrl($this->poster_url, now()->addMinutes(30));
    }
}
