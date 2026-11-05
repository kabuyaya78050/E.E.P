<?php

namespace App\Http\Controllers;

use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        return $this->listing(null);
    }

    public function category(string $type)
    {
        abort_unless(isset(Event::TYPES[$type]), 404);

        return $this->listing($type);
    }

    public function show(Event $event)
    {
        abort_unless($event->is_published, 404);

        return view('events.show', compact('event'));
    }

    private function listing(?string $type)
    {
        $events = Event::published()
            ->when($type, fn ($q) => $q->where('type', $type))
            ->orderByDesc('starts_at')
            ->orderByDesc('created_at')
            ->paginate(9);

        return view('events.index', compact('events', 'type'));
    }
}
