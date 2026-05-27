<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KosCare Penghuni - @yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/koscare.css') }}">
</head>
<body>
    <div id="app-penghuni" class="app-layout">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h2><i class="fa-solid fa-leaf"></i> KosCare</h2>
                <span>Akses Penghuni</span>
            </div>
            <ul class="menu-list">
                <li class="menu-item active" onclick="switchTab('penghuni', 'dash-penghuni', this)">
                    <i class="fa-solid fa-border-all"></i> Ikhtisar
                </li>
                <li class="menu-item" onclick="switchTab('penghuni', 'setor-penghuni', this)">
                    <i class="fa-solid fa-recycle"></i> Ajukan Setoran
                </li>
                <li class="menu-item" onclick="switchTab('penghuni', 'jadwal-penghuni', this)">
                    <i class="fa-regular fa-calendar-check"></i> Jadwal Angkut
                </li>
                <li class="menu-item" onclick="switchTab('penghuni', 'riwayat-penghuni', this)">
                    <i class="fa-solid fa-clock-rotate-left"></i> Riwayat Transaksi
                </li>
            </ul>
            <div class="sidebar-footer">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline" style="width: 100%;">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar Akun
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="topbar">
                <div class="topbar-title" id="title-penghuni">@yield('page-title', 'Ikhtisar Dashboard')</div>
                <div class="topbar-actions">
                    <div class="notification-btn">
                        <i class="fa-regular fa-bell"></i>
                        <div class="notification-dot"></div>
                    </div>
                    <div class="user-profile">
                        <div class="avatar">
                            {{ Auth::user()->no_kamar ?? '?' }}
                        </div>
                        <div class="user-info">
                            <span class="user-name">{{ Auth::user()->name }}</span>
                            <span class="user-role">Kamar {{ Auth::user()->no_kamar ?? '-' }}</span>
                        </div>
                        <i class="fa-solid fa-chevron-down" style="color: var(--text-light); margin-left: 8px; font-size: 12px;"></i>
                    </div>
                </div>
            </div>

            <div class="content-area">
                @yield('content')
            </div>
        </div>
    </div>

    <script>
        function switchTab(role, tabId, element) {
            const app = document.getElementById('app-' + role);
            const menuItems = app.querySelectorAll('.menu-item');
            menuItems.forEach(item => item.classList.remove('active'));
            if (element) element.classList.add('active');

            const tabContents = app.querySelectorAll('.tab-content');
            tabContents.forEach(tab => tab.classList.remove('active'));

            const targetTab = document.getElementById(tabId);
            if (targetTab) {
                void targetTab.offsetWidth;
                targetTab.classList.add('active');
            }

            const topbarTitle = document.getElementById('title-' + role);
            if (element && topbarTitle) {
                let clone = element.cloneNode(true);
                let icon = clone.querySelector('i');
                if (icon) clone.removeChild(icon);
                topbarTitle.innerText = clone.innerText.trim();
            }
        }
    </script>
    @stack('scripts')
</body>
</html>