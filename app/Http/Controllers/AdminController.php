<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Genre;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard', [
            'films' => Film::count(),
            'users' => User::count(),
            'active' => Subscription::where(fn ($query) => $query->whereNull('ends_at')->orWhere('ends_at', '>', now()))
                ->distinct('user_id')->count('user_id'),
            'revenue' => Transaction::where('status', 'paid')->sum('amount'),
            'recent' => Transaction::with('user')->latest()->take(10)->get(),
        ]);
    }

    public function index()
    {
        return view('admin.films', ['films' => Film::latest()->get()]);
    }

    public function create()
    {
        return view('admin.film-form', ['film' => new Film(['access' => 'premium', 'status' => 'draft', 'video_provider' => 'youtube']), 'genres' => Genre::orderBy('name')->get()]);
    }

    public function edit(Film $film)
    {
        return view('admin.film-form', ['film' => $film, 'genres' => Genre::orderBy('name')->get()]);
    }

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

    public function destroy(Film $film)
    {
        $film->delete();

        return redirect()->route('admin.films.index')->with('ok', 'Film dihapus.');
    }

    public function transactions()
    {
        return view('admin.transactions', ['rows' => Transaction::with('user')->latest()->paginate(30)]);
    }

    public function users()
    {
        return view('admin.users', ['rows' => User::latest()->paginate(30)]);
    }

    private function data(Request $r, ?Film $film = null): array
    {
        $d = $r->validate([
            'title' => 'required|max:150',
            'synopsis' => 'required',
            'poster_url' => 'nullable|url|max:500',
            'poster_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|extensions:jpg,jpeg,png,webp|max:4096',
            'genre' => 'nullable|string|max:80|exists:genres,name',
            'duration_minutes' => 'required|integer|min:0|max:600',
            'access' => 'required|in:free,premium',
            'status' => 'required|in:draft,published,hidden,archived',
            'video_provider' => 'required|in:youtube,mp4,hls,r2',
            'video_ref' => 'nullable|required_if:status,published|string|max:500',
            'rights_note' => 'nullable|required_if:status,published|string',
        ], [
            'video_ref.required_if' => 'Isi ID atau URL video sebelum menerbitkan film.',
            'rights_note.required_if' => 'Isi catatan hak penggunaan sebelum menerbitkan film.',
        ]);
        if ($d['video_provider'] === 'youtube' && filled($d['video_ref'])) {
            $youtubeVideoId = Film::extractYoutubeVideoId($d['video_ref']);

            if ($youtubeVideoId === null) {
                throw ValidationException::withMessages([
                    'video_ref' => 'Masukkan ID YouTube 11 karakter atau URL YouTube yang valid.',
                ]);
            }

            $d['video_ref'] = $youtubeVideoId;
        }
        $posterFile = $r->file('poster_file');
        unset($d['poster_file']);
        if ($posterFile) {
            $posterDisk = filled(config('filesystems.disks.r2-posters.bucket')) ? 'r2-posters' : 'public';
            $posterPath = $posterFile->store('posters', $posterDisk);

            if ($posterPath === false) {
                throw new \RuntimeException('Unable to store uploaded poster.');
            }

            $d['poster_url'] = $posterDisk === 'public' ? 'local:'.$posterPath : $posterPath;
        } elseif (blank($d['poster_url'])) {
            $d['poster_url'] = $film?->poster_url;
        }
        $d['slug'] = $film?->slug ?? Str::slug($d['title']).'-'.Str::lower(Str::random(4));
        if ($d['status'] === 'published' && ! $film?->published_at) {
            $d['published_at'] = now();
        }

        return $d;
    }
}
