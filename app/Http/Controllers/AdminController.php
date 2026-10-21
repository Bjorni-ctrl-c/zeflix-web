<?php
namespace App\Http\Controllers;

use App\Models\{Film, Subscription, Transaction, User};
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard', [
            'films' => Film::count(),
            'users' => User::count(),
            'active' => Subscription::where('ends_at', '>', now())->distinct('user_id')->count('user_id'),
            'revenue' => Transaction::where('status', 'paid')->sum('amount'),
            'recent' => Transaction::with('user')->latest()->take(10)->get(),
        ]);
    }

    public function index() { return view('admin.films', ['films' => Film::latest()->get()]); }
    public function create() { return view('admin.film-form', ['film' => new Film(['access' => 'premium', 'status' => 'draft', 'video_provider' => 'youtube'])]); }
    public function edit(Film $film) { return view('admin.film-form', ['film' => $film]); }

    public function store(Request $r)
    {
        Film::create($this->data($r));

        return redirect()->route('admin.films.index')->with('ok', 'Film disimpan.');
    }

    public function update(Request $r, Film $film)
    {
        $film->update($this->data($r, $film));

        return redirect()->route('admin.films.index')->with('ok', 'Film diperbarui.');
    }

    public function transactions() { return view('admin.transactions', ['rows' => Transaction::with('user')->latest()->paginate(30)]); }
    public function users() { return view('admin.users', ['rows' => User::latest()->paginate(30)]); }

    private function data(Request $r, ?Film $film = null): array
    {
        $d = $r->validate([
            'title' => 'required|max:150',
            'synopsis' => 'required',
            'poster_url' => 'nullable|url|max:500',
            'poster_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|extensions:jpg,jpeg,png,webp|max:4096',
            'genre' => 'nullable|max:80',
            'duration_minutes' => 'required|integer|min:0|max:600',
            'access' => 'required|in:free,premium',
            'status' => 'required|in:draft,published,hidden,archived',
            'video_provider' => 'required|in:youtube,mp4,hls,r2',
            'video_ref' => 'nullable|string|max:500',
            'rights_note' => 'nullable',
        ]);
        if ($d['status'] === 'published' && (empty($d['video_ref']) || empty($d['rights_note']))) {
            abort(422, 'Untuk terbit, isi ID/URL video dan catatan hak penggunaan.');
        }
        $posterFile = $r->file('poster_file');
        unset($d['poster_file']);
        if ($posterFile) {
            $d['poster_url'] = $posterFile->store('posters', 'r2-posters');
        } elseif (blank($d['poster_url'])) {
            $d['poster_url'] = $film?->poster_url;
        }
        $d['slug'] = $film?->slug ?? Str::slug($d['title']).'-'.Str::lower(Str::random(4));
        if ($d['status'] === 'published' && !$film?->published_at) $d['published_at'] = now();
        return $d;
    }
}
