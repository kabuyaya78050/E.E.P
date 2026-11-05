@php
    $networks = array_filter([
        'facebook' => ['Facebook', $facebook ?? null,
            '<path d="M13.5 22v-8.2h2.8l.5-3.3h-3.3V8.4c0-1 .4-1.7 1.8-1.7h1.6V3.8c-.3 0-1.3-.1-2.4-.1-2.5 0-4.1 1.5-4.1 4.2v2.6H7.6v3.3h2.8V22h3.1z"/>'],
        'tiktok' => ['TikTok', $tiktok ?? null,
            '<path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>'],
        'instagram' => ['Instagram', $instagram ?? null,
            '<rect x="3.5" y="3.5" width="17" height="17" rx="5" fill="none" stroke="#fff" stroke-width="2"/><circle cx="12" cy="12" r="4" fill="none" stroke="#fff" stroke-width="2"/><circle cx="17.3" cy="6.7" r="1.2" fill="#fff"/>'],
        'youtube' => ['YouTube', $youtube ?? null,
            '<rect x="2" y="5" width="20" height="14" rx="4"/><path class="tri" d="M10 9l5.5 3-5.5 3z"/>'],
    ], fn ($n) => ! empty($n[1]));
@endphp

@if (count($networks))
    <ul class="social">
        @foreach ($networks as $key => [$label, $url, $svg])
            <li>
                <a class="social-link social-{{ $key }}" href="{{ $url }}" target="_blank" rel="noopener" aria-label="{{ $label }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">{!! $svg !!}</svg>
                </a>
            </li>
        @endforeach
    </ul>
@endif
