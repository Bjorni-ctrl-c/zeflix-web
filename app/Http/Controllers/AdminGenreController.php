<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminGenreController extends Controller
{
    public function index()
    {
        return view('admin.genres', ['genres' => Genre::withCount('films')->orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|min:2|max:80|unique:genres,name']);
        Genre::create($data);

        return back()->with('ok', 'Genre ditambahkan.');
    }

    public function update(Request $request, Genre $genre)
    {
        $data = $request->validate(['name' => ['required', 'string', 'min:2', 'max:80', Rule::unique('genres', 'name')->ignore($genre->id)]]);
        DB::transaction(function () use ($genre, $data) {
            Film::where('genre', $genre->name)->update(['genre' => $data['name']]);
            $genre->update($data);
        });

        return redirect()->route('admin.genres.index')->with('ok', 'Genre diperbarui.');
    }

    public function destroy(Genre $genre)
    {
        if (Film::where('genre', $genre->name)->exists()) {
            return back()->with('err', 'Pindahkan film ke genre lain sebelum menghapus genre ini.');
        }

        $genre->delete();

        return back()->with('ok', 'Genre dihapus.');
    }
}
