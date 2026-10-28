<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Film extends Model
{
    protected $guarded = [];

    protected $casts = ['published_at' => 'datetime'];

    public function scopePublished($q)
    {
        return $q->where('status', 'published');
    }

    public function canBeWatchedBy(?User $user): bool
    {
        if ($this->status !== 'published' && ! ($user && $user->isAdmin())) {
            return false;
        }
        if ($this->access === 'free') {
            return true;
        }

        return $user && ($user->isAdmin() || $user->hasActiveSubscription());
    }

    /** URL video untuk pemutar. Untuk R2: URL bertanda tangan yang kedaluwarsa dalam 3 jam. */
    public function playbackUrl(): string
    {
        if ($this->video_provider !== 'r2') {
            return (string) $this->video_ref;
        }

        return Storage::disk('r2')->temporaryUrl($this->video_ref, now()->addHours(3));
    }

    public function youtubeVideoId(): ?string
    {
        return self::extractYoutubeVideoId((string) $this->video_ref);
    }

    public static function extractYoutubeVideoId(string $reference): ?string
    {
        if (preg_match('/^[A-Za-z0-9_-]{11}$/', $reference) === 1) {
            return $reference;
        }

        $parts = parse_url($reference);
        $host = strtolower($parts['host'] ?? '');
        $allowedHosts = ['youtu.be', 'www.youtu.be', 'youtube.com', 'www.youtube.com', 'm.youtube.com', 'youtube-nocookie.com', 'www.youtube-nocookie.com'];

        if (! in_array($host, $allowedHosts, true)) {
            return null;
        }

        parse_str($parts['query'] ?? '', $query);
        $videoId = $host === 'youtu.be'
            ? trim($parts['path'] ?? '', '/')
            : ($query['v'] ?? basename($parts['path'] ?? ''));

        return is_string($videoId) && preg_match('/^[A-Za-z0-9_-]{11}$/', $videoId) === 1
            ? $videoId
            : null;
    }

    public function posterImageUrl(): ?string
    {
        if (blank($this->poster_url)) {
            return null;
        }

        if (Str::startsWith($this->poster_url, ['https://', 'http://'])) {
            return $this->poster_url;
        }

        if (Str::startsWith($this->poster_url, 'local:')) {
            return Storage::disk('public')->url(Str::after($this->poster_url, 'local:'));
        }

        return Storage::disk('r2-posters')->temporaryUrl($this->poster_url, now()->addMinutes(30));
    }
}
