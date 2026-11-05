<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sermon;
use Illuminate\Http\Request;

class SermonController extends Controller
{
    public function index()
    {
        $sermons = Sermon::orderByDesc('preached_at')->orderByDesc('id')->paginate(15);

        return view('admin.sermons.index', compact('sermons'));
    }

    public function create()
    {
        $sermon = new Sermon(['preached_at' => now(), 'is_published' => true]);

        return view('admin.sermons.form', compact('sermon'));
    }

    public function store(Request $request)
    {
        Sermon::create($this->validated($request));

        return redirect()->route('admin.sermons.index')->with('ok', 'Prédication ajoutée.');
    }

    public function edit(Sermon $sermon)
    {
        return view('admin.sermons.form', compact('sermon'));
    }

    public function update(Request $request, Sermon $sermon)
    {
        $sermon->update($this->validated($request));

        return redirect()->route('admin.sermons.index')->with('ok', 'Prédication mise à jour.');
    }

    public function destroy(Sermon $sermon)
    {
        $sermon->delete();

        return redirect()->route('admin.sermons.index')->with('ok', 'Prédication supprimée.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'preacher' => ['nullable', 'string', 'max:120'],
            'scripture' => ['nullable', 'string', 'max:120'],
            'preached_at' => ['required', 'date'],
            'video_url' => ['required', 'url:http,https', 'max:500', function ($attribute, $value, $fail) {
                if (! (new Sermon(['video_url' => $value]))->embedUrl()) {
                    $fail('Le lien doit être une vidéo YouTube ou Facebook valide.');
                }
            }],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        $data['is_published'] = $request->boolean('is_published');

        return $data;
    }
}
