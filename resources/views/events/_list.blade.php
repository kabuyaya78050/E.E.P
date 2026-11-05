@if ($events->isEmpty())
    <p class="empty">Aucune publication pour le moment. Revenez bientôt.</p>
@else
    <ul class="event-list">
        @foreach ($events as $event)
            <li @class(['event-row', 'has-img' => $event->image])>
                <div class="event-date">
                    @if ($event->starts_at)
                        <span class="d">{{ $event->starts_at->translatedFormat('d') }}</span>
                        <span class="m">{{ $event->starts_at->translatedFormat('M Y') }}</span>
                    @else
                        <span class="m">{{ $event->created_at->translatedFormat('d M Y') }}</span>
                    @endif
                </div>
                <div class="event-body">
                    <h3><a href="{{ route('events.show', $event) }}">{{ $event->title }}</a></h3>
                    <p class="meta">
                        <span class="tag">{{ $event->typeLabel() }}</span>
                        @if ($event->location)<span>{{ $event->location }}</span>@endif
                    </p>
                    @if ($event->excerpt)<p>{{ $event->excerpt }}</p>@endif
                </div>
                @if ($event->image)
                    <a class="event-thumb" href="{{ route('events.show', $event) }}" tabindex="-1" aria-hidden="true">
                        <img src="{{ asset($event->image) }}" alt="" loading="lazy">
                    </a>
                @endif
            </li>
        @endforeach
    </ul>
@endif
