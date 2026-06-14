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
    @php
        $activeTab = $activeTab ?? 'dash-penghuni';
    @endphp
    <div id="app-penghuni" class="app-layout">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h2><img src="{{ asset('image/yop.png') }}" alt="KosCare Logo"> KosCare</h2>
                <span>Akses Penghuni</span>
            </div>
            <ul class="menu-list">
                <li class="menu-item active" onclick="switchTab('penghuni', 'dash-penghuni', this)">
                    <i class="fa-solid fa-border-all"></i> Beranda
                </li>
                <li class="menu-item" onclick="switchTab('penghuni', 'setor-penghuni', this)">
                    <i class="fa-solid fa-recycle"></i> Ajukan Setoran
                </li>
                <li class="menu-item" onclick="switchTab('penghuni', 'jadwal-penghuni', this)">
                    <i class="fa-regular fa-calendar-check"></i> Jadwal Angkut
                </li>
                <li class="menu-item {{ $activeTab == 'reward-resident' ? 'active' : '' }}"
                    onclick="switchTab('penghuni', 'reward-resident', this)">
                    <i class="fa-solid fa-gift"></i> Tukar Poin
                </li>
                <!-- <li class="menu-item {{ $activeTab == 'profile-resident' ? 'active' : '' }}" 
                    onclick="switchTab('penghuni', 'profile-resident', this)">
                    <i class="fa-solid fa-user-gear"></i> Profil Saya
                </li> -->
                <li class="menu-item {{ $activeTab == 'artikel-resident' ? 'active' : '' }}" 
                    onclick="switchTab('penghuni', 'artikel-resident', this)">
                    <i class="fa-solid fa-newspaper"></i> Artikel Edukasi
                </li>
                <li class="menu-item" onclick="switchTab('penghuni', 'riwayat-penghuni', this)">
                    <i class="fa-solid fa-clock-rotate-left"></i> Riwayat Transaksi
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
                <button class="topbar-toggle" onclick="toggleSidebar('penghuni')" aria-label="Buka menu">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="topbar-title" id="title-penghuni">@yield('page-title', 'Beranda')</div>
                <div class="topbar-actions">
                    <div class="user-profile" onclick="switchTab('penghuni', 'profile-resident', null)" style="cursor: pointer;">
                        <div class="avatar">
                            {{ Auth::user()->no_kamar ?? '?' }}
                        </div>
                        <div class="user-info">
                            <span class="user-name">{{ Auth::user()->name }}</span>
                            <span class="user-role">{{ Auth::user()->nama_kos ?? 'Kos Tidak Diketahui' }}</span>
                            <span class="text-sm">{{ Auth::user()->alamat_kos ?? 'Alamat belum diisi' }}</span>
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
    <div class="sidebar-backdrop" id="penghuniSidebarBackdrop" onclick="toggleSidebar('penghuni')"></div>

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
            
            // Jika elemen app tidak ada (berarti bukan halaman dashboard), redirect
            if (!app) {
                window.location.href = '/resident/dashboard?tab=' + tabId;
                return;
            }

            const targetTab = document.getElementById(tabId);
            
            // Jika tab yang dicari tidak ada di halaman ini, redirect
            if (!targetTab) {
                window.location.href = '/resident/dashboard?tab=' + tabId;
                return;
            }

            // Hapus kelas 'active' dari semua menu-item
            const menuItems = app.querySelectorAll('.menu-item');
            menuItems.forEach(item => item.classList.remove('active'));
            if (element) element.classList.add('active');

            // Sembunyikan semua tab-content, tampilkan yang dipilih
            const tabContents = app.querySelectorAll('.tab-content');
            tabContents.forEach(tab => tab.classList.remove('active'));
            
            void targetTab.offsetWidth; // trigger reflow
            targetTab.classList.add('active');

            // Update topbar title jika element diberikan
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