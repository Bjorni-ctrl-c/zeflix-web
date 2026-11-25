<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
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
        $genre = Genre::create($data);
        AuditLog::record('GENRE_CREATE', "Menambahkan genre '{$genre->name}'");

        return back()->with('ok', 'Genre ditambahkan.');
    }

    public function update(Request $request, Genre $genre)
    {
        $oldName = $genre->name;
        $data = $request->validate(['name' => ['required', 'string', 'min:2', 'max:80', Rule::unique('genres', 'name')->ignore($genre->id)]]);
        DB::transaction(function () use ($genre, $data) {
            Film::where('genre', $genre->name)->update(['genre' => $data['name']]);
            $genre->update($data);
        });
        AuditLog::record('GENRE_UPDATE', "Mengubah nama genre '{$oldName}' menjadi '{$data['name']}'");

        return redirect()->route('admin.genres.index')->with('ok', 'Genre diperbarui.');
    }

    public function destroy(Genre $genre)
    {
        if (Film::where('genre', $genre->name)->exists()) {
            return back()->with('err', 'Pindahkan film ke genre lain sebelum menghapus genre ini.');
        }

        $name = $genre->name;
        $genre->delete();
        AuditLog::record('GENRE_DELETE', "Menghapus genre '{$name}'");

        return back()->with('ok', 'Genre dihapus.');
    }
}
