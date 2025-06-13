@extends('layouts.modern')

@push('styles')
<style>
    .dashboard-container {
        min-height: calc(100vh - 4rem);
    }
      .sidebar {
        background: linear-gradient(135deg, #1976d2 0%, #1565c0 50%, #0d47a1 100%);
        min-height: calc(100vh - 4rem);
        transition: all 0.3s ease;
        box-shadow: 2px 0 10px rgba(25, 118, 210, 0.1);
    }
      .sidebar-item {
        transition: all 0.3s ease;
        border-radius: 0.5rem;
        margin: 0.25rem 0;
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    .sidebar-item:hover {
        background: rgba(255, 255, 255, 0.15) !important;
        transform: translateX(8px);
        color: white !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }    .sidebar-item.active {
        background: rgba(255, 255, 255, 0.25) !important;
        border-left: 4px solid #43a047;
        color: white !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }
      .main-content {
        background: #f8fafc;
        min-height: calc(100vh - 4rem);
        padding: 2rem;
    }
      /* Gradient pour l'en-tête de bienvenue */
    .gradient-bg {
        background: linear-gradient(135deg, #1976d2 0%, #1565c0 50%, #0d47a1 100%);
        box-shadow: 0 8px 32px rgba(25, 118, 210, 0.3);
    }
    
    /* Cartes de contenu */
    .content-card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(229, 231, 235, 0.8);
        transition: all 0.3s ease;
    }
    
    .content-card:hover {
        box-shadow: 0 8px 25px rgba(25, 118, 210, 0.15);
        transform: translateY(-2px);
    }
    
    @media (max-width: 1024px) {
        .sidebar {
            position: fixed;
            top: 4rem;
            left: -100%;
            width: 280px;
            z-index: 40;
            transition: left 0.3s ease;
        }
        
        .sidebar.open {
            left: 0;
        }
        
        .main-content {
            padding: 1rem;
        }
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }
    
    .stat-card {
        background: linear-gradient(135deg, var(--card-color-1, #3b82f6), var(--card-color-2, #1d4ed8));
        border-radius: 1rem;
        padding: 1.5rem;
        color: white;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: translate(30px, -30px);
    }
    
    .stat-card:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }
    
    .content-card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .content-card:hover {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }
    
    /* Amélioration de la lisibilité des éléments sidebar */
    .sidebar .sidebar-item i {
        color: rgba(255, 255, 255, 0.8) !important;
        transition: all 0.3s ease;
        margin-right: 0.75rem;
    }
    
    .sidebar .sidebar-item:hover i {
        color: white !important;
        transform: scale(1.1);
    }
    
    .sidebar .sidebar-item.active i {
        color: white !important;
    }
    
    /* Correction pour tous les liens dans la sidebar */
    .sidebar a {
        color: rgba(255, 255, 255, 0.9) !important;
        text-decoration: none;
    }
    
    .sidebar a:hover {
        color: white !important;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')
<div class="dashboard-container flex">
    <!-- Sidebar -->
    <div id="sidebar" class="sidebar w-72 text-white">
        <div class="p-6">
            <div class="flex items-center mb-8">
                <div class="bg-white bg-opacity-20 p-3 rounded-lg mr-3">
                    <i class="fas fa-user-circle text-2xl"></i>
                </div>
                <div>
                    <h2 class="font-bold text-lg">@yield('dashboard-title', 'Espace Utilisateur')</h2>
                    <p class="text-blue-200 text-sm">{{ auth()->user()->prenoms . ' ' . auth()->user()->nom }}</p>
                </div>
            </div>
            
            <nav>
                <ul class="space-y-2">
                    @yield('sidebar-menu')
                </ul>
            </nav>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="flex-1 main-content">
        <!-- Mobile Sidebar Toggle -->
        <div class="lg:hidden mb-4">
            <button id="sidebar-toggle" class="bg-primary-600 text-white p-2 rounded-lg hover:bg-primary-700 transition-colors duration-200">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        
        @yield('dashboard-content')
    </div>
</div>

<!-- Mobile Sidebar Overlay -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden opacity-0 pointer-events-none transition-opacity duration-300"></div>
@endsection

@push('scripts')
<script>
    // Mobile sidebar toggle
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebar-overlay');
    
    function toggleSidebar() {
        sidebar.classList.toggle('open');
        if (sidebar.classList.contains('open')) {
            sidebarOverlay.classList.remove('opacity-0', 'pointer-events-none');
        } else {
            sidebarOverlay.classList.add('opacity-0', 'pointer-events-none');
        }
    }
    
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', toggleSidebar);
    }
    
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', toggleSidebar);
    }
    
    // Auto-close sidebar on desktop resize
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 1024) {
            sidebar.classList.remove('open');
            sidebarOverlay.classList.add('opacity-0', 'pointer-events-none');
        }
    });
    
    // Stats animation
    function animateStats() {
        const statNumbers = document.querySelectorAll('.stat-number');
        
        statNumbers.forEach(stat => {
            const target = parseInt(stat.textContent);
            const duration = 1000;
            const start = performance.now();
            
            function update(currentTime) {
                const elapsed = currentTime - start;
                const progress = Math.min(elapsed / duration, 1);
                
                const current = Math.floor(progress * target);
                stat.textContent = current;
                
                if (progress < 1) {
                    requestAnimationFrame(update);
                }
            }
            
            stat.textContent = '0';
            requestAnimationFrame(update);
        });
    }
    
    // Trigger stats animation when page loads
    window.addEventListener('load', function() {
        setTimeout(animateStats, 500);
    });    // Smooth scroll for internal links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            // Ignore empty hash or just '#'
            if (!href || href === '#' || href.length <= 1) {
                return;
            }
            
            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
</script>
@endpush

