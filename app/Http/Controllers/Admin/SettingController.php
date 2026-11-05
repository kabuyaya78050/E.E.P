<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\VideoEmbed;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    private const KEYS = [
        'address', 'map_query', 'phone', 'whatsapp', 'email',
        'youtube_url', 'facebook_url', 'tiktok_url', 'instagram_url', 'live_url', 'live_active',
    ];

    public function edit()
    {
        $values = [];
        foreach (self::KEYS as $key) {
            $values[$key] = Setting::get($key);
        }

        return view('admin.settings.edit', compact('values'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'address' => ['nullable', 'string', 'max:255'],
            'map_query' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:60'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'youtube_url' => ['nullable', 'url:http,https', 'max:300', 'regex:~^https?://([a-z0-9-]+\.)?(youtube\.com|youtu\.be)/~i'],
            'facebook_url' => ['nullable', 'url:http,https', 'max:300', 'regex:~^https?://([a-z0-9-]+\.)?(facebook\.com|fb\.com|fb\.watch)/~i'],
            'tiktok_url' => ['nullable', 'url:http,https', 'max:300', 'regex:~^https?://([a-z0-9-]+\.)?tiktok\.com/~i'],
            'instagram_url' => ['nullable', 'url:http,https', 'max:300', 'regex:~^https?://([a-z0-9-]+\.)?instagram\.com/~i'],
            'live_url' => ['nullable', 'url:http,https', 'max:500', function ($attribute, $value, $fail) {
                if ($value && ! VideoEmbed::embedUrl($value)) {
                    $fail('Le lien du direct doit être une vidéo YouTube ou Facebook valide.');
                }
            }],
        ], [
            'youtube_url.regex' => 'Saisissez un lien YouTube valide.',
            'facebook_url.regex' => 'Saisissez un lien Facebook valide.',
            'tiktok_url.regex' => 'Saisissez un lien TikTok valide.',
            'instagram_url.regex' => 'Saisissez un lien Instagram valide.',
        ]);

        $values = array_map(fn ($v) => $v === null ? '' : trim($v), $data);
        $values['live_active'] = $request->boolean('live_active') ? '1' : '';

        Setting::put($values);

        return redirect()->route('admin.settings.edit')->with('ok', 'Réglages enregistrés.');
    }
}
