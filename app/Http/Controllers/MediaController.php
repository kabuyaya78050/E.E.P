<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Sermon;
use App\Support\VideoEmbed;

class MediaController extends Controller
{
    public function index()
    {
        $sermons = Sermon::published()
            ->orderByDesc('preached_at')
            ->orderByDesc('id')
            ->take(5)
            ->get();

        return view('media.index', compact('sermons'));
    }

    public function live()
    {
        $url = Setting::get('live_url');
        $embed = VideoEmbed::embedUrl($url);
        $active = (bool) Setting::get('live_active');

        return view('media.live', compact('url', 'embed', 'active'));
    }
}
