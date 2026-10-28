<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Genre;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home(Request $r)
    {
        $user = $r->user();
        $canBrowseCatalog = $user && ($user->isAdmin() || $user->hasActiveSubscription());

        $films = $canBrowseCatalog
            ? Film::published()->latest('published_at')->take(8)->get()
            : collect();

        return view('home', ['films' => $films]);
    }

    public function catalog(Request $r)
    {
        $q = Film::published()->latest('published_at');
        if ($r->filled('genre')) {
            $q->where('genre', $r->genre);
        }

        return view('catalog', [
            'films' => $q->get(),
            'genres' => Genre::orderBy('name')->pluck('name'),
        ]);
    }

    public function show(Film $film, Request $r)
    {
        abort_unless($film->status === 'published' || $r->user()?->isAdmin(), 404);

        return view('film', ['film' => $film, 'can' => $film->canBeWatchedBy($r->user())]);
    }

    public function watch(Film $film, Request $r)
    {
        abort_unless($film->status === 'published' || $r->user()?->isAdmin(), 404);
        if (! $film->canBeWatchedBy($r->user())) {
            return redirect()->route('film', $film)->with('err', 'Film ini khusus pelanggan aktif.');
        }

        return view('watch', ['film' => $film]);
    }

    public function account(Request $r)
    {
        $u = $r->user();

        return view('account', [
            'active' => $u->hasActiveSubscription(),
            'until' => $u->activeUntil(),
            'transactions' => $u->transactions()->latest()->take(10)->get(),
        ]);
    }
}
