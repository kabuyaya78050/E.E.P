<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::orderByDesc('created_at')->paginate(15);

        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        $event = new Event(['type' => 'actualite', 'is_published' => true]);

        return view('admin.events.form', compact('event'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['title']);
        $data['image'] = $this->saveImage($request);

        Event::create($data);

        return redirect()->route('admin.events.index')->with('ok', 'Publication créée.');
    }

    public function edit(Event $event)
    {
        return view('admin.events.form', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $data = $this->validated($request);

        if ($path = $this->saveImage($request)) {
            $this->deleteImage($event->image);
            $data['image'] = $path;
        }

        $event->update($data);

        return redirect()->route('admin.events.index')->with('ok', 'Publication mise à jour.');
    }

    public function destroy(Event $event)
    {
        $this->deleteImage($event->image);
        $event->delete();

        return redirect()->route('admin.events.index')->with('ok', 'Publication supprimée.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'type' => ['required', 'in:' . implode(',', array_keys(Event::TYPES))],
            'excerpt' => ['nullable', 'string', 'max:300'],
            'body' => ['required', 'string'],
            'starts_at' => ['nullable', 'date'],
            'location' => ['nullable', 'string', 'max:160'],
            'departure_time' => ['nullable', 'date_format:H:i'],
            'departure_place' => ['nullable', 'string', 'max:160'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ]);

        unset($data['image']);
        $data['is_published'] = $request->boolean('is_published');

        return $data;
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'publication';
        $slug = $base;
        $i = 2;

        while (Event::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    private function saveImage(Request $request): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        $file = $request->file('image');
        $name = Str::random(20) . '.' . $file->extension();
        $file->move(public_path('images/events'), $name);

        return 'images/events/' . $name;
    }

    private function deleteImage(?string $path): void
    {
        if ($path && str_starts_with($path, 'images/events/') && is_file(public_path($path))) {
            @unlink(public_path($path));
        }
    }
}
