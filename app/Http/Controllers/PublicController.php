<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Genre;
use App\Models\WatchProgress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home(Request $r)
    {
        $user = $r->user();
        $isSubscriber = $user && ($user->isAdmin() || $user->hasActiveSubscription());

        $featured = null;
        $continueWatching = collect();
        $watchlistFilms = collect();
        $top10 = collect();
        $trending = collect();
        $genresWithFilms = collect();
        $freeFilms = collect();

        if ($user) {
            // All logged-in users can see free films; subscribers see everything
            $allPublished = Film::published()->get();

            $featured = Film::published()->where('is_featured', true)->first()
                ?? Film::published()->latest('published_at')->first();

            $top10 = Film::published()
                ->orderByDesc('views_count')
                ->orderByDesc('match_rate')
                ->take(10)
                ->get();

            $trending = Film::published()->latest('published_at')->take(10)->get();

            // Free films row — always visible to logged-in users
            $freeFilms = $allPublished->where('access', 'free')->take(10);

            if ($isSubscriber) {
                // Continue Watching
                $continueWatching = WatchProgress::where('user_id', $user->id)
                    ->where('progress_percent', '<', 95)
                    ->with('film')
                    ->latest('last_watched_at')
                    ->take(6)
                    ->get()
                    ->filter(fn ($p) => $p->film && $p->film->status === 'published');

                // User's My List
                $watchlistFilms = $user->watchlistFilms()->published()->latest()->take(10)->get();
            }

            // Group films by genre for Netflix rows
            $genres = Genre::orderBy('name')->pluck('name');
            foreach ($genres as $genreName) {
                $genreFilms = $isSubscriber
                    ? $allPublished->where('genre', $genreName)->take(8)
                    : $allPublished->where('genre', $genreName)->where('access', 'free')->take(8);
                if ($genreFilms->isNotEmpty()) {
                    $genresWithFilms->put($genreName, $genreFilms);
                }
            }
        }

        $watchlistIds = $user ? $user->watchlistFilms()->pluck('films.id')->all() : [];

        return view('home', [
            'canBrowseCatalog' => (bool) $user,
            'isSubscriber' => $isSubscriber ?? false,
            'featured' => $featured,
            'top10' => $top10,
            'continueWatching' => $continueWatching,
            'watchlistFilms' => $watchlistFilms,
            'trending' => $trending,
            'freeFilms' => $freeFilms,
            'genresWithFilms' => $genresWithFilms,
            'watchlistIds' => $watchlistIds,
        ]);
    }

    public function catalog(Request $r)
    {
        $q = Film::published();

        if ($r->filled('q')) {
            $keyword = trim($r->input('q'));
            $q->where(function ($sub) use ($keyword) {
                $sub->where('title', 'like', "%{$keyword}%")
                    ->orWhere('genre', 'like', "%{$keyword}%")
                    ->orWhere('synopsis', 'like', "%{$keyword}%");
            });
        }

        if ($r->filled('genre')) {
            $q->where('genre', $r->genre);
        }

        $sort = $r->input('sort', 'latest');
        if ($sort === 'popular') {
            $q->orderByDesc('views_count')->orderByDesc('match_rate');
        } elseif ($sort === 'match') {
            $q->orderByDesc('match_rate');
        } else {
            $q->latest('published_at');
        }

        $films = $q->get();
        $genres = Genre::orderBy('name')->pluck('name');
        $user = $r->user();
        $watchlistIds = $user ? $user->watchlistFilms()->pluck('films.id')->all() : [];

        return view('catalog', [
            'films' => $films,
            'genres' => $genres,
            'watchlistIds' => $watchlistIds,
            'searchQuery' => $r->input('q', ''),
            'currentGenre' => $r->input('genre', ''),
            'currentSort' => $sort,
        ]);
    }

    public function watchlist(Request $r)
    {
        $user = $r->user();
        $films = $user->watchlistFilms()->published()->latest()->get();
        $watchlistIds = $films->pluck('id')->all();

        return view('watchlist', [
            'films' => $films,
            'watchlistIds' => $watchlistIds,
        ]);
    }

    public function toggleWatchlist(Film $film, Request $r)
    {
        $user = $r->user();
        abort_unless($user, 401);

        $exists = $user->isInWatchlist($film);
        if ($exists) {
            $user->watchlistFilms()->detach($film->id);
            $inList = false;
            $msg = "'{$film->title}' dihapus dari Daftar Saya.";
        } else {
            $user->watchlistFilms()->attach($film->id);
            $inList = true;
            $msg = "'{$film->title}' ditambahkan ke Daftar Saya.";
        }

        if ($r->wantsJson() || $r->ajax()) {
            return response()->json([
                'status' => 'ok',
                'in_watchlist' => $inList,
                'count' => $user->watchlistFilms()->count(),
                'message' => $msg,
            ]);
        }

        return back()->with('ok', $msg);
    }

    public function updateProgress(Film $film, Request $r): JsonResponse
    {
        $user = $r->user();
        abort_unless($user, 401);

        $validated = $r->validate([
            'progress_percent' => 'required|integer|min:0|max:100',
            'seconds' => 'nullable|integer|min:0',
            'duration' => 'nullable|integer|min:0',
        ]);

        $progress = WatchProgress::updateOrCreate(
            ['user_id' => $user->id, 'film_id' => $film->id],
            [
                'progress_percent' => $validated['progress_percent'],
                'seconds' => $validated['seconds'] ?? 0,
                'duration_seconds' => $validated['duration'] ?? 0,
                'last_watched_at' => now(),
            ]
        );

        $film->increment('views_count');

        return response()->json(['status' => 'ok', 'progress' => $progress->progress_percent]);
    }

    public function quickView(Film $film, Request $r): JsonResponse
    {
        abort_unless($film->status === 'published' || $r->user()?->isAdmin(), 404);

        $user = $r->user();
        $canWatch = $film->canBeWatchedBy($user);
        $inWatchlist = $user ? $user->isInWatchlist($film) : false;

        return response()->json([
            'id' => $film->id,
            'title' => $film->title,
            'slug' => $film->slug,
            'synopsis' => $film->synopsis,
            'genre' => $film->genre ?? 'Film',
            'duration' => $film->duration_minutes ? $film->duration_minutes.'m' : '',
            'access' => $film->access,
            'match_rate' => $film->match_rate ?? 95,
            'age_rating' => $film->age_rating ?? '13+',
            'quality' => $film->quality ?? '4K',
            'poster_url' => $film->posterImageUrl(),
            'can_watch' => $canWatch,
            'in_watchlist' => $inWatchlist,
            'watch_url' => route('watch', $film),
            'detail_url' => route('film', $film),
            'checkout_url' => route('checkout.page'),
        ]);
    }

    public function show(Film $film, Request $r)
    {
        abort_unless($film->status === 'published' || $r->user()?->isAdmin(), 404);

        $user = $r->user();
        $inWatchlist = $user ? $user->isInWatchlist($film) : false;
        $progress = $user ? $user->getWatchProgress($film) : null;
        $similar = Film::published()
            ->where('id', '!=', $film->id)
            ->where('genre', $film->genre)
            ->take(6)
            ->get();

        if ($similar->isEmpty()) {
            $similar = Film::published()->where('id', '!=', $film->id)->take(6)->get();
        }

        $watchlistIds = $user ? $user->watchlistFilms()->pluck('films.id')->all() : [];

        return view('film', [
            'film' => $film,
            'can' => $film->canBeWatchedBy($user),
            'inWatchlist' => $inWatchlist,
            'progress' => $progress,
            'similar' => $similar,
            'watchlistIds' => $watchlistIds,
        ]);
    }

    public function watch(Film $film, Request $r)
    {
        abort_unless($film->status === 'published' || $r->user()?->isAdmin(), 404);
        if (! $film->canBeWatchedBy($r->user())) {
            return redirect()->route('film', $film)->with('err', 'Film ini khusus pelanggan aktif.');
        }

        $progress = $r->user() ? $r->user()->getWatchProgress($film) : null;

        return view('watch', [
            'film' => $film,
            'progress' => $progress,
        ]);
    }

    public function account(Request $r)
    {
        $u = $r->user();

        return view('account', [
            'active' => $u->hasActiveSubscription(),
            'until' => $u->activeUntil(),
            'transactions' => $u->transactions()->latest()->take(10)->get(),
            'watchlistCount' => $u->watchlistFilms()->count(),
            'watchedCount' => $u->watchProgresses()->count(),
        ]);
    }
}
