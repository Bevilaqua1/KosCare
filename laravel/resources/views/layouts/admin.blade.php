<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KosCare Admin - @yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/koscare.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body>
    <div class="app-layout" id="app-admin">
        <!-- Sidebar Admin -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h2><img src="{{ asset('image/yop.png') }}" alt="KosCare Logo"> KosCare</h2>
                <span style="background: var(--text-main); color: white;">Administrator Panel</span>
            </div>
            <ul class="menu-list">
                <li class="menu-item active" onclick="switchTab('admin', 'dash-admin', this)">
                    <i class="fa-solid fa-chart-pie"></i> Beranda
                </li>
                <li class="menu-item {{ $activeTab == 'jadwal-admin' ? 'active' : '' }}" onclick="switchTab('admin', 'jadwal-admin', this)">
                    <i class="fa-solid fa-calendar-days"></i> Kelola Jadwal
                    @if(isset($pendingJadwalCount) && $pendingJadwalCount > 0)
                        <span class="badge">{{ $pendingJadwalCount }}</span>
                    @endif
                </li>
                <li class="menu-item {{ $activeTab == 'validasi-admin' ? 'active' : '' }}" onclick="switchTab('admin', 'validasi-admin', this)">
                    <i class="fa-solid fa-list-check"></i> Validasi Setoran
                    @if($pendingValidationCount > 0)
                        <span class="badge">{{ $pendingValidationCount }}</span>
                    @endif
                </li>
                <li class="menu-item {{ $activeTab == 'reward-admin' ? 'active' : '' }}" 
                    onclick="switchTab('admin', 'reward-admin', this)">
                    <i class="fa-solid fa-gift"></i> Kelola Reward
                    @if(isset($pendingRewardCount) && $pendingRewardCount > 0)
                        <span class="badge">{{ $pendingRewardCount }}</span>
                    @endif
                </li>
                <li class="menu-item {{ $activeTab == 'kategori-admin' ? 'active' : '' }}" onclick="switchTab('admin', 'kategori-admin', this)">
                    <i class="fa-solid fa-tags"></i> Kelola Kategori
                </li>
                <li class="menu-item {{ $activeTab == 'artikel-admin' ? 'active' : '' }}"
                    onclick="switchTab('admin', 'artikel-admin', this)">
                    <i class="fa-solid fa-newspaper"></i> Kelola Artikel
                </li>
                <li class="menu-item {{ $activeTab == 'laporan-admin' ? 'active' : '' }}" 
                    onclick="switchTab('admin', 'laporan-admin', this)">
                    <i class="fa-solid fa-chart-simple"></i> Laporan
                </li>
                <li class="menu-item" onclick="switchTab('admin', 'pengguna-admin', this)">
                    <i class="fa-solid fa-users-gear"></i> Kelola Pengguna
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
                <button class="topbar-toggle" onclick="toggleSidebar('admin')" aria-label="Buka menu">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="topbar-title" id="title-admin">@yield('page-title', 'Beranda')</div>
                <div class="topbar-actions">
                    <div class="user-profile">
                        <div class="avatar" style="background: var(--text-main);">AD</div>
                        <div class="user-info">
                            <span class="user-name">{{ Auth::user()->name }}</span>
                            <span class="user-role">Administrator</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="content-area">
                @yield('content')
            </div>
        </div>
    </div>
    <div class="sidebar-backdrop" id="adminSidebarBackdrop" onclick="toggleSidebar('admin')"></div>

    <script>
        // Fungsi toggle sidebar mobile
        function toggleSidebar(role) {
            const app = document.getElementById('app-' + role);
            if (!app) return;
            const sidebar = app.querySelector('.sidebar');
            const backdrop = document.getElementById(role + 'SidebarBackdrop');
            if (!sidebar || !backdrop) return;
            sidebar.classList.toggle('show');
            backdrop.classList.toggle('show');
        }

        // Fungsi switchTab global (sama seperti di desain)
        function switchTab(role, tabId, element) {
            const app = document.getElementById('app-' + role);
            const menuItems = app.querySelectorAll('.menu-item');
            menuItems.forEach(item => item.classList.remove('active'));
            if(element) element.classList.add('active');

            const tabContents = app.querySelectorAll('.tab-content');
            tabContents.forEach(tab => tab.classList.remove('active'));

            const targetTab = document.getElementById(tabId);
            if(targetTab) {
                void targetTab.offsetWidth;
                targetTab.classList.add('active');
            }

            const topbarTitle = document.getElementById('title-' + role);
            if(element && topbarTitle) {
                let clone = element.cloneNode(true);
                let icon = clone.querySelector('i');
                if(icon) clone.removeChild(icon);
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