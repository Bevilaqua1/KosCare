<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KosCare Admin - @yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/koscare.css') }}">

</head>
<body>
    <div class="app-layout" id="app-admin">
        <!-- Sidebar Admin -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h2><i class="fa-solid fa-shield-halved"></i> KosCare</h2>
                <span style="background: var(--text-main); color: white;">Administrator Panel</span>
            </div>
            <ul class="menu-list">
                <li class="menu-item active" onclick="switchTab('admin', 'dash-admin', this)">
                    <i class="fa-solid fa-chart-pie"></i> Ikhtisar Utama
                </li>
                <li class="menu-item" onclick="switchTab('admin', 'validasi-admin', this)">
                    <i class="fa-solid fa-list-check"></i> Validasi Setoran
                </li>
                <li class="menu-item" onclick="switchTab('admin', 'kategori-admin', this)">
                    <i class="fa-solid fa-tags"></i> Kelola Kategori
                </li>
                <li class="menu-item" onclick="switchTab('admin', 'jadwal-admin', this)">
                    <i class="fa-solid fa-calendar-days"></i> Kelola Jadwal
                </li>
                <li class="menu-item {{ $activeTab == 'reward-admin' ? 'active' : '' }}" 
                    onclick="switchTab('admin', 'reward-admin', this)">
                    <i class="fa-solid fa-gift"></i> Kelola Reward
                </li>
                <li class="menu-item" onclick="switchTab('admin', 'pengguna-admin', this)">
                    <i class="fa-solid fa-users-gear"></i> Kelola Pengguna
                </li>
            </ul>
            <div class="sidebar-footer">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline btn-danger" style="width: 100%; border-color: var(--danger); color: var(--danger);">
                        <i class="fa-solid fa-power-off"></i> Keluar Sistem
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="topbar">
                <div class="topbar-title" id="title-admin">@yield('page-title', 'Ikhtisar Utama')</div>
                <div class="topbar-actions">
                    <div class="notification-btn">
                        <i class="fa-solid fa-inbox"></i>
                        <div class="notification-dot" style="background: var(--warning);"></div>
                    </div>
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

    <script>
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
    </script>
    @stack('scripts')
</body>
</html>