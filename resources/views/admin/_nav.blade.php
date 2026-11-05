<div class="admin-nav">
    <div class="container admin-nav-inner">
        <div class="admin-links">
            <a href="{{ route('admin.events.index') }}" @class(['active' => request()->routeIs('admin.events.*')])>Publications</a>
            <a href="{{ route('admin.sermons.index') }}" @class(['active' => request()->routeIs('admin.sermons.*')])>Prédications</a>
            <a href="{{ route('admin.donations.index') }}" @class(['active' => request()->routeIs('admin.donations.*')])>Dons</a>
            <a href="{{ route('admin.settings.edit') }}" @class(['active' => request()->routeIs('admin.settings.*')])>Réglages</a>
            <a href="{{ route('admin.users.index') }}" @class(['active' => request()->routeIs('admin.users.*')])>Administrateurs</a>
            <a href="{{ route('home') }}">Voir le site</a>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="admin-logout">Se déconnecter</button>
        </form>
    </div>
</div>
