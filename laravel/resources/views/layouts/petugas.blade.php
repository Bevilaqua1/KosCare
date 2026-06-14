<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KosCare Petugas - @yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/koscare.css') }}">
</head>
<body>
    <div id="app-petugas" class="app-layout">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h2><img src="{{ asset('image/yop.png') }}" alt="KosCare Logo"> KosCare</h2>
                <span style="background: var(--warning-bg); color: #B45309;">Akses Petugas</span>
            </div>
            <ul class="menu-list">
                <li class="menu-item active" onclick="switchTab('petugas', 'jadwal-petugas', this)">
                    <i class="fa-solid fa-clipboard-list"></i> Tugas Hari Ini
                </li>
                <li class="menu-item" onclick="switchTab('petugas', 'riwayat-petugas', this)">
                    <i class="fa-solid fa-check-double"></i> Riwayat Angkut
                </li>
            </ul>
            <div class="sidebar-footer">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline" style="width: 100%;">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar Sistem
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="topbar">
                <button class="topbar-toggle" onclick="toggleSidebar('petugas')" aria-label="Buka menu">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="topbar-title" id="title-petugas">@yield('page-title', 'Daftar Tugas Hari Ini')</div>
                <div class="user-profile">
                    <div class="avatar" style="background: var(--warning);">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <div class="user-info">
                        <span class="user-name">{{ Auth::user()->name }}</span>
                        <span class="user-role">Petugas Lapangan</span>
                    </div>
                </div>
            </div>

            <div class="content-area">
                @yield('content')
            </div>
        </div>
    </div>
    <div class="sidebar-backdrop" id="petugasSidebarBackdrop" onclick="toggleSidebar('petugas')"></div>

    <script>
        function toggleSidebar(role) {
            const app = document.getElementById('app-' + role);
            if (!app) return;
            const sidebar = app.querySelector('.sidebar');
            const backdrop = document.getElementById(role + 'SidebarBackdrop');
            if (!sidebar || !backdrop) return;
            sidebar.classList.toggle('show');
            backdrop.classList.toggle('show');
        }

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

        // Auto-dismiss flash notifications after 3 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.flash-alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.maxHeight = alert.offsetHeight + 'px';
                    alert.style.transition = 'all 0.5s ease';
                    alert.offsetHeight; // force reflow
                    
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-10px)';
                    alert.style.maxHeight = '0';
                    alert.style.paddingTop = '0';
                    alert.style.paddingBottom = '0';
                    alert.style.marginTop = '0';
                    alert.style.marginBottom = '0';
                    
                    setTimeout(function() {
                        alert.remove();
                    }, 500);
                }, 3000);
            });
        });
    </script>
    @stack('scripts')
</body>
</html>