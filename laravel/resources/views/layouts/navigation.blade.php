@auth
    @if(auth()->user()->isAdmin())
        <x-responsive-nav-link :href="route('admin.dashboard')">Dashboard Admin</x-responsive-nav-link>
    @elseif(auth()->user()->isPetugas())
        <x-responsive-nav-link :href="route('petugas.dashboard')">Dashboard Petugas</x-responsive-nav-link>
    @else
        <x-responsive-nav-link :href="route('resident.dashboard')">Dashboard Penghuni</x-responsive-nav-link>
    @endif
@endauth