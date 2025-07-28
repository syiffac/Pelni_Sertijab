@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Admin')
@section('page-description', 'Sistem Manajemen Sertijab PELNI - Overview dan Statistik')

@section('header') @endsection

@section('content')
<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="welcome-card">
                <div class="d-flex align-items-center">
                    <div class="welcome-icon">
                        <i class="bi bi-speedometer2"></i>
                    </div>
                    <div class="welcome-content">
                        <h3 class="welcome-title">Selamat Datang di Dashboard Sertijab PELNI</h3>
                        <p class="welcome-text">Kelola sistem serah terima jabatan dengan mudah dan efisien</p>
                    </div>
                    <div class="welcome-actions">
                        <button class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>
                            Tambah ABK Baru
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="stats-card">
                <div class="stats-content">
                    <div class="stats-info">
                        <h5 class="stats-title">Total ABK</h5>
                        <h2 class="stats-number">1,245</h2>
                        <span class="stats-change positive">
                            <i class="bi bi-arrow-up"></i> +12% dari bulan lalu
                        </span>
                    </div>
                    <div class="stats-icon bg-primary">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="stats-card">
                <div class="stats-content">
                    <div class="stats-info">
                        <h5 class="stats-title">Sertijab Bulan Ini</h5>
                        <h2 class="stats-number">89</h2>
                        <span class="stats-change positive">
                            <i class="bi bi-arrow-up"></i> +5% dari bulan lalu
                        </span>
                    </div>
                    <div class="stats-icon bg-success">
                        <i class="bi bi-file-earmark-check"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="stats-card">
                <div class="stats-content">
                    <div class="stats-info">
                        <h5 class="stats-title">Menunggu Verifikasi</h5>
                        <h2 class="stats-number">23</h2>
                        <span class="stats-change negative">
                            <i class="bi bi-arrow-down"></i> -3% dari bulan lalu
                        </span>
                    </div>
                    <div class="stats-icon bg-warning">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="stats-card">
                <div class="stats-content">
                    <div class="stats-info">
                        <h5 class="stats-title">Total Arsip</h5>
                        <h2 class="stats-number">3,567</h2>
                        <span class="stats-change positive">
                            <i class="bi bi-arrow-up"></i> +8% dari bulan lalu
                        </span>
                    </div>
                    <div class="stats-icon bg-info">
                        <i class="bi bi-archive-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row mb-4">
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="chart-card">
                <div class="chart-header">
                    <h5 class="chart-title">Trend Sertijab Tahunan</h5>
                    <div class="chart-actions">
                        <button class="btn btn-sm btn-outline-primary">Export</button>
                    </div>
                </div>
                <div class="chart-body">
                    <div class="chart-placeholder">
                        <i class="bi bi-bar-chart-line"></i>
                        <p>Chart akan ditampilkan di sini</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="chart-card">
                <div class="chart-header">
                    <h5 class="chart-title">Status Sertijab</h5>
                </div>
                <div class="chart-body">
                    <div class="chart-placeholder">
                        <i class="bi bi-pie-chart"></i>
                        <p>Pie Chart akan ditampilkan di sini</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities & Quick Actions -->
    <div class="row">
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="activity-card">
                <div class="activity-header">
                    <h5 class="activity-title">Aktivitas Terbaru</h5>
                    <a href="#" class="activity-link">Lihat Semua</a>
                </div>
                <div class="activity-body">
                    <div class="activity-list">
                        <div class="activity-item">
                            <div class="activity-icon bg-primary">
                                <i class="bi bi-person-plus"></i>
                            </div>
                            <div class="activity-content">
                                <p class="activity-text">ABK baru <strong>Ahmad Yani</strong> ditambahkan ke sistem</p>
                                <span class="activity-time">2 menit yang lalu</span>
                            </div>
                        </div>
                        
                        <div class="activity-item">
                            <div class="activity-icon bg-success">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <div class="activity-content">
                                <p class="activity-text">Sertijab <strong>Kapten Budi</strong> telah diverifikasi</p>
                                <span class="activity-time">15 menit yang lalu</span>
                            </div>
                        </div>
                        
                        <div class="activity-item">
                            <div class="activity-icon bg-warning">
                                <i class="bi bi-file-earmark-text"></i>
                            </div>
                            <div class="activity-content">
                                <p class="activity-text">Dokumen sertijab menunggu persetujuan</p>
                                <span class="activity-time">1 jam yang lalu</span>
                            </div>
                        </div>
                        
                        <div class="activity-item">
                            <div class="activity-icon bg-info">
                                <i class="bi bi-download"></i>
                            </div>
                            <div class="activity-content">
                                <p class="activity-text">Backup database berhasil diunduh</p>
                                <span class="activity-time">3 jam yang lalu</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="quick-actions-card">
                <div class="quick-actions-header">
                    <h5 class="quick-actions-title">Aksi Cepat</h5>
                </div>
                <div class="quick-actions-body">
                    <div class="quick-actions-grid">
                        <a href="#" class="quick-action-item">
                            <div class="quick-action-icon bg-primary">
                                <i class="bi bi-person-plus"></i>
                            </div>
                            <span class="quick-action-text">Tambah ABK</span>
                        </a>
                        
                        <a href="#" class="quick-action-item">
                            <div class="quick-action-icon bg-success">
                                <i class="bi bi-file-earmark-plus"></i>
                            </div>
                            <span class="quick-action-text">Buat Sertijab</span>
                        </a>
                        
                        <a href="#" class="quick-action-item">
                            <div class="quick-action-icon bg-warning">
                                <i class="bi bi-search"></i>
                            </div>
                            <span class="quick-action-text">Cari Arsip</span>
                        </a>
                        
                        <a href="#" class="quick-action-item">
                            <div class="quick-action-icon bg-info">
                                <i class="bi bi-download"></i>
                            </div>
                            <span class="quick-action-text">Export Data</span>
                        </a>
                        
                        <a href="#" class="quick-action-item">
                            <div class="quick-action-icon bg-secondary">
                                <i class="bi bi-gear"></i>
                            </div>
                            <span class="quick-action-text">Pengaturan</span>
                        </a>
                        
                        <a href="#" class="quick-action-item">
                            <div class="quick-action-icon bg-dark">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <span class="quick-action-text">Backup</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Dashboard Specific Styles */
.welcome-card {
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
    border-radius: var(--border-radius);
    padding: 24px;
    color: white;
    box-shadow: var(--shadow-medium);
    margin-bottom: 0;
}

.welcome-icon {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    margin-right: 20px;
}

.welcome-content {
    flex: 1;
}

.welcome-title {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 8px;
    font-family: 'Poppins', sans-serif;
}

.welcome-text {
    margin: 0;
    opacity: 0.9;
    font-size: 16px;
}

.welcome-actions .btn {
    background: rgba(255, 255, 255, 0.2);
    border: 2px solid rgba(255, 255, 255, 0.3);
    color: white;
    font-weight: 600;
    padding: 12px 24px;
    border-radius: 10px;
    transition: var(--transition);
}

.welcome-actions .btn:hover {
    background: white;
    color: var(--primary-blue);
    border-color: white;
}

/* Stats Cards */
.stats-card {
    background: white;
    border-radius: var(--border-radius);
    padding: 24px;
    box-shadow: var(--shadow-light);
    border: 1px solid var(--border-color);
    height: 100%;
    transition: var(--transition);
}

.stats-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-medium);
}

.stats-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.stats-info {
    flex: 1;
}

.stats-title {
    font-size: 14px;
    color: var(--text-muted);
    margin-bottom: 8px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stats-number {
    font-size: 32px;
    font-weight: 800;
    color: var(--text-dark);
    margin-bottom: 8px;
    font-family: 'Poppins', sans-serif;
}

.stats-change {
    font-size: 12px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
}

.stats-change.positive {
    color: var(--success-color);
}

.stats-change.negative {
    color: var(--error-color);
}

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
}

/* Chart Cards */
.chart-card, .activity-card, .quick-actions-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-light);
    border: 1px solid var(--border-color);
    height: 100%;
}

.chart-header, .activity-header, .quick-actions-header {
    padding: 20px 24px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chart-title, .activity-title, .quick-actions-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
    font-family: 'Poppins', sans-serif;
}

.chart-body, .activity-body, .quick-actions-body {
    padding: 24px;
}

.chart-placeholder {
    height: 300px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
    background: #f8fafc;
    border-radius: 8px;
    border: 2px dashed var(--border-color);
}

.chart-placeholder i {
    font-size: 48px;
    margin-bottom: 16px;
    opacity: 0.5;
}

.chart-placeholder p {
    margin: 0;
    font-size: 14px;
}

/* Activity List */
.activity-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 16px;
    background: #f8fafc;
    border-radius: 10px;
    transition: var(--transition);
}

.activity-item:hover {
    background: #f1f5f9;
    transform: translateX(4px);
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
    flex-shrink: 0;
}

.activity-content {
    flex: 1;
}

.activity-text {
    margin: 0 0 4px 0;
    font-size: 14px;
    color: var(--text-dark);
    line-height: 1.5;
}

.activity-time {
    font-size: 12px;
    color: var(--text-muted);
}

.activity-link {
    color: var(--primary-blue);
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
}

/* Quick Actions */
.quick-actions-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
}

.quick-action-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    padding: 20px;
    background: #f8fafc;
    border-radius: 12px;
    text-decoration: none;
    color: var(--text-dark);
    transition: var(--transition);
    border: 2px solid transparent;
}

.quick-action-item:hover {
    color: var(--text-dark);
    transform: translateY(-4px);
    box-shadow: var(--shadow-light);
    border-color: var(--border-color);
    background: white;
}

.quick-action-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
}

.quick-action-text {
    font-size: 12px;
    font-weight: 600;
    text-align: center;
    line-height: 1.3;
}

/* Responsive Design */
@media (max-width: 768px) {
    .welcome-card {
        text-align: center;
    }
    
    .welcome-card .d-flex {
        flex-direction: column;
        gap: 20px;
    }
    
    .welcome-title {
        font-size: 20px;
    }
    
    .stats-number {
        font-size: 28px;
    }
    
    .chart-placeholder {
        height: 200px;
    }
    
    .quick-actions-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }
    
    .quick-action-item {
        padding: 16px 12px;
    }
    
    .quick-action-icon {
        width: 40px;
        height: 40px;
        font-size: 18px;
    }
    
    .quick-action-text {
        font-size: 11px;
    }
}

@media (max-width: 480px) {
    .quick-actions-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add entrance animations
    const cards = document.querySelectorAll('.stats-card, .chart-card, .activity-card, .quick-actions-card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
        card.classList.add('fade-in');
    });
    
    // Add click handlers for quick actions
    const quickActions = document.querySelectorAll('.quick-action-item');
    quickActions.forEach(action => {
        action.addEventListener('click', function(e) {
            e.preventDefault();
            const actionText = this.querySelector('.quick-action-text').textContent;
            showToast(`Fitur "${actionText}" akan segera tersedia`, 'info');
        });
    });
    
    // Simulate real-time updates (for demo purposes)
    setTimeout(() => {
        showToast('Dashboard berhasil dimuat', 'success', 3000);
    }, 1000);
});
</script>
@endpush