<div class="subnav">
    <nav class="container" aria-label="Section À propos">
        <a href="{{ route('about') }}" @class(['active' => request()->routeIs('about')])>Présentation</a>
        <a href="{{ route('vision') }}" @class(['active' => request()->routeIs('vision')])>Vision</a>
        <a href="{{ route('mission') }}" @class(['active' => request()->routeIs('mission')])>Mission et devise</a>
        <a href="{{ route('history') }}" @class(['active' => request()->routeIs('history')])>Historique</a>
    </nav>
</div>
