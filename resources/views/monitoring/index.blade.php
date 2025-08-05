{{-- filepath: c:\laragon\www\SertijabPelni\resources\views\monitoring\index.blade.php --}}
@extends('layouts.app')

@section('title', 'Monitoring Dashboard')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header dengan styling yang sama -->
    <div class="page-header">
        <div class="header-content">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">
                                <i class="bi bi-house-door"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item active">Monitoring</li>
                    </ol>
                </nav>
                <h1 class="page-title">
                    <i class="bi bi-graph-up-arrow"></i>
                    Monitoring Dashboard
                </h1>
                <p class="page-subtitle">Pantau status dan progress dokumen sertijab ABK</p>
            </div>
            <div class="header-actions">
                <div class="action-buttons">
                    <a href="{{ route('monitoring.sertijab') }}" class="btn btn-primary">
                        <i class="bi bi-file-earmark-check me-2"></i>
                        Lihat Semua Sertijab
                    </a>
                    <a href="{{ route('monitoring.documents') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-shield-check me-2"></i>
                        Verifikasi Dokumen
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards dengan styling baru -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card stats-card-primary">
                <div class="stats-icon">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="stats-content">
                    <div class="stats-number">{{ $stats['total_mutasi'] }}</div>
                    <div class="stats-label">Total ABK Mutasi</div>
                    <div class="stats-description">ABK yang memerlukan sertijab</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card stats-card-success">
                <div class="stats-icon">
                    <i class="bi bi-file-earmark-check"></i>
                </div>
                <div class="stats-content">
                    <div class="stats-number">{{ $stats['sertijab_submitted'] }}</div>
                    <div class="stats-label">Dokumen Terkumpul</div>
                    <div class="stats-description">
                        <span class="progress-indicator progress-{{ $stats['submission_progress'] < 50 ? 'danger' : ($stats['submission_progress'] < 80 ? 'warning' : 'success') }}">
                            {{ $stats['submission_progress'] }}%
                        </span> dari total ABK mutasi
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card stats-card-info">
                <div class="stats-icon">
                    <i class="bi bi-patch-check-fill"></i>
                </div>
                <div class="stats-content">
                    <div class="stats-number">{{ $stats['sertijab_verified'] }}</div>
                    <div class="stats-label">Terverifikasi</div>
                    <div class="stats-description">
                        <span class="progress-indicator progress-{{ $stats['verification_progress'] < 50 ? 'danger' : ($stats['verification_progress'] < 80 ? 'warning' : 'success') }}">
                            {{ $stats['verification_progress'] }}%
                        </span> dari total ABK mutasi
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card stats-card-warning">
                <div class="stats-icon">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div class="stats-content">
                    <div class="stats-number">{{ $stats['sertijab_pending'] }}</div>
                    <div class="stats-label">Menunggu Verifikasi</div>
                    <div class="stats-description">
                        @if($stats['sertijab_pending'] > 0)
                            <span class="text-warning">Perlu ditindaklanjuti</span>
                        @else
                            <span class="text-success">Tidak ada dokumen tertunda</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress & Status - styling table tetap sama -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="table-section">
                <div class="table-card">
                    <div class="table-header">
                        <h5 class="table-title">
                            <i class="bi bi-table me-2"></i>
                            Status Sertijab per Kapal
                        </h5>
                        <div class="table-info">
                            @if($monitoringData->pagination->has_pages)
                            <small class="text-muted me-3">
                                {{ $monitoringData->pagination->from }}-{{ $monitoringData->pagination->to }} 
                                dari {{ $monitoringData->pagination->total }} kapal
                            </small>
                            @endif
                            <a href="{{ route('monitoring.sertijab') }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-list-ul me-1"></i> Lihat Semua
                            </a>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-data">
                            <thead>
                                <tr>
                                    <th width="8%">No</th>
                                    <th width="25%">Nama Kapal</th>
                                    <th width="12%">Mutasi</th>
                                    <th width="15%">Dokumen Terkumpul</th>
                                    <th width="15%">Progress</th>
                                    <th width="15%">Status</th>
                                    <th width="10%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($monitoringData->data as $index => $data)
                                <tr class="table-row">
                                    <td>
                                        <span class="code-badge">
                                            {{ $monitoringData->pagination->from + $index }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="kapal-info">
                                            <strong>{{ $data->nama_kapal }}</strong>
                                            <small class="d-block text-muted">ID: {{ $data->id }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary bg-opacity-10 text-primary fw-medium">
                                            {{ $data->total_mutasi }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="fw-medium">{{ $data->submitted }}</span>
                                            <span class="text-muted">/</span>
                                            <span class="text-muted">{{ $data->total_mutasi }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($data->total_mutasi > 0)
                                            @php
                                                $percentage = round(($data->submitted / $data->total_mutasi) * 100);
                                                $colorClass = $percentage < 50 ? 'danger' : ($percentage < 80 ? 'warning' : 'success');
                                            @endphp
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="progress flex-grow-1" style="height: 8px; width: 60px;">
                                                    <div class="progress-bar bg-{{ $colorClass }}" 
                                                         role="progressbar" 
                                                         style="width: {{ $percentage }}%;" 
                                                         aria-valuenow="{{ $percentage }}" 
                                                         aria-valuemin="0" 
                                                         aria-valuemax="100">
                                                    </div>
                                                </div>
                                                <small class="text-{{ $colorClass }} fw-medium">{{ $percentage }}%</small>
                                            </div>
                                        @else
                                            <span class="badge bg-secondary">Tidak Ada Data</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($data->total_mutasi == 0)
                                            <span class="status-badge status-unknown">Tidak Ada Data</span>
                                        @elseif($data->submitted == 0)
                                            <span class="status-badge status-draft">Belum Terkumpul</span>
                                        @elseif($data->submitted < $data->total_mutasi)
                                            <span class="status-badge status-partial">Sebagian Terkumpul</span>
                                        @elseif($data->verified == $data->total_mutasi)
                                            <span class="status-badge status-final">Semua Terverifikasi</span>
                                        @elseif($data->verified > 0)
                                            <span class="status-badge status-partial">Sebagian Terverifikasi</span>
                                        @else
                                            <span class="status-badge status-draft">Menunggu Verifikasi</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-buttons-mini">
                                            <button class="btn-action btn-view" 
                                                    onclick="window.location.href='{{ route('monitoring.sertijab', ['kapal_id' => $data->id]) }}'"
                                                    title="Lihat Detail">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            {{-- <button class="btn-action btn-verify" 
                                                    onclick="window.location.href='{{ route('monitoring.documents', ['kapal_id' => $data->id]) }}'"
                                                    title="Verifikasi Dokumen">
                                                <i class="bi bi-check2-circle"></i>
                                            </button> --}}
                                            <button class="btn-action btn-info" 
                                                    onclick="window.location.href='{{ route('mutasi.index', ['kapal_id' => $data->id]) }}'"
                                                    title="Data Mutasi">
                                                <i class="bi bi-info-circle"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="empty-state">
                                            <i class="bi bi-inbox display-4 text-muted mb-3"></i>
                                            <h5 class="text-muted">Belum ada kapal dengan mutasi yang memerlukan sertijab</h5>
                                            <p class="text-muted mb-3">Data akan muncul setelah ada mutasi ABK yang perlu sertijab</p>
                                            <a href="{{ route('mutasi.index') }}" class="btn btn-primary">
                                                <i class="bi bi-plus-circle me-2"></i>
                                                Tambah Data Mutasi
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination dengan styling yang sama -->
                    @if($monitoringData->pagination->has_pages)
                    <div class="table-footer">
                        <div class="pagination-wrapper">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="small text-muted">
                                    Menampilkan {{ $monitoringData->pagination->from }}-{{ $monitoringData->pagination->to }} 
                                    dari {{ $monitoringData->pagination->total }} kapal
                                </div>
                                
                                <nav aria-label="Pagination Monitoring">
                                    <ul class="pagination pagination-sm mb-0">
                                        <!-- Previous Page Link -->
                                        @if($monitoringData->pagination->on_first_page)
                                            <li class="page-item disabled">
                                                <span class="page-link">
                                                    <i class="bi bi-chevron-left"></i>
                                                </span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $monitoringData->pagination->current_page - 1]) }}">
                                                    <i class="bi bi-chevron-left"></i>
                                                </a>
                                            </li>
                                        @endif

                                        <!-- Page Numbers -->
                                        @php
                                            $start = max(1, $monitoringData->pagination->current_page - 2);
                                            $end = min($monitoringData->pagination->last_page, $monitoringData->pagination->current_page + 2);
                                        @endphp

                                        @if($start > 1)
                                            <li class="page-item">
                                                <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => 1]) }}">1</a>
                                            </li>
                                            @if($start > 2)
                                                <li class="page-item disabled">
                                                    <span class="page-link">...</span>
                                                </li>
                                            @endif
                                        @endif

                                        @for($i = $start; $i <= $end; $i++)
                                            @if($i == $monitoringData->pagination->current_page)
                                                <li class="page-item active">
                                                    <span class="page-link">{{ $i }}</span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $i]) }}">{{ $i }}</a>
                                                </li>
                                            @endif
                                        @endfor

                                        @if($end < $monitoringData->pagination->last_page)
                                            @if($end < $monitoringData->pagination->last_page - 1)
                                                <li class="page-item disabled">
                                                    <span class="page-link">...</span>
                                                </li>
                                            @endif
                                            <li class="page-item">
                                                <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $monitoringData->pagination->last_page]) }}">
                                                    {{ $monitoringData->pagination->last_page }}
                                                </a>
                                            </li>
                                        @endif

                                        <!-- Next Page Link -->
                                        @if($monitoringData->pagination->has_more_pages)
                                            <li class="page-item">
                                                <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $monitoringData->pagination->current_page + 1]) }}">
                                                    <i class="bi bi-chevron-right"></i>
                                                </a>
                                            </li>
                                        @else
                                            <li class="page-item disabled">
                                                <span class="page-link">
                                                    <i class="bi bi-chevron-right"></i>
                                                </span>
                                            </li>
                                        @endif
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="table-section">
                <div class="table-card">
                    <div class="table-header">
                        <h5 class="table-title">
                            <i class="bi bi-pie-chart me-2"></i>
                            Status Verifikasi
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($chartData['total'] > 0)
                            <div class="chart-container mb-4">
                                <canvas id="verificationChart" width="240" height="240"></canvas>
                            </div>
                            
                            <div class="chart-legend">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-success rounded-circle me-2" style="width:12px;height:12px;"></div>
                                        <span>Terverifikasi</span>
                                    </div>
                                    <span class="fw-medium">{{ $chartData['values'][0] }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-warning rounded-circle me-2" style="width:12px;height:12px;"></div>
                                        <span>Menunggu Verifikasi</span>
                                    </div>
                                    <span class="fw-medium">{{ $chartData['values'][1] }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-info rounded-circle me-2" style="width:12px;height:12px;"></div>
                                        <span>Sebagian Verified</span>
                                    </div>
                                    <span class="fw-medium">{{ $chartData['values'][2] }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-danger rounded-circle me-2" style="width:12px;height:12px;"></div>
                                        <span>Ditolak</span>
                                    </div>
                                    <span class="fw-medium">{{ $chartData['values'][3] }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-secondary rounded-circle me-2" style="width:12px;height:12px;"></div>
                                        <span>Belum Dikumpulkan</span>
                                    </div>
                                    <span class="fw-medium">{{ $chartData['values'][4] }}</span>
                                </div>
                            </div>
                            
                            <div class="text-center mt-3 pt-3 border-top">
                                <small class="text-muted">
                                    Total: {{ $chartData['total'] }} Dokumen
                                </small>
                            </div>
                        @else
                            <div class="empty-state py-4">
                                <i class="bi bi-pie-chart fs-1 text-muted mb-2"></i>
                                <p class="mb-0 text-muted">Belum ada data untuk ditampilkan</p>
                                <small class="text-muted">Data akan muncul setelah ada mutasi yang perlu sertijab</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest Activity - styling juga diupdate -->
    <div class="row">
        <div class="col-12">
            <div class="table-section">
                <div class="table-card">
                    <div class="table-header">
                        <h5 class="table-title">
                            <i class="bi bi-clock-history me-2"></i>
                            Aktivitas Terbaru
                        </h5>
                        <div class="table-info">
                            <small class="text-muted">{{ $recentActivities->count() }} aktivitas terakhir</small>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if($recentActivities->count() > 0)
                            <div class="activity-list">
                                @foreach($recentActivities as $activity)
                                    <div class="activity-item">
                                        <div class="activity-icon activity-{{ $activity['color'] }}">
                                            <i class="{{ $activity['icon'] }}"></i>
                                        </div>
                                        <div class="activity-content">
                                            <div class="activity-title">{{ $activity['title'] }}</div>
                                            <div class="activity-description">{{ $activity['description'] }}</div>
                                            <div class="activity-time">
                                                <i class="bi bi-clock"></i>
                                                {{ $activity['time']->diffForHumans() }}
                                            </div>
                                        </div>
                                        <div class="activity-action">
                                            <a href="{{ $activity['url'] }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="activity-footer">
                                <div class="text-center">
                                    <a href="{{ route('monitoring.sertijab') }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-list-ul me-1"></i>
                                        Lihat Semua Aktivitas
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="empty-state py-4">
                                <i class="bi bi-clock-history fs-1 text-muted mb-3"></i>
                                <h6 class="text-muted">Belum Ada Aktivitas</h6>
                                <p class="text-muted mb-3">Aktivitas akan muncul setelah ada submit atau verifikasi dokumen</p>
                                <a href="{{ route('monitoring.sertijab') }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus-circle me-1"></i>
                                    Mulai Monitoring
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Import all styling variables from sertijab page */
:root {
    --primary-blue: #2563eb;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --info-color: #06b6d4;
    --text-dark: #1f2937;
    --text-muted: #6b7280;
    --border-color: #e5e7eb;
    --background-light: #f8fafc;
    --border-radius: 12px;
    --shadow-light: 0 1px 3px rgba(0, 0, 0, 0.1);
    --shadow-medium: 0 4px 6px rgba(0, 0, 0, 0.1);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Page Header - sama seperti sertijab */
.page-header {
    background: white;
    border-radius: var(--border-radius);
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: var(--shadow-light);
    border: 1px solid var(--border-color);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
}

.header-actions {
    display: flex;
    flex-direction: column;
    gap: 12px;
    align-items: flex-end;
}

.action-buttons {
    display: flex;
    gap: 12px;
}

.breadcrumb {
    background: none;
    padding: 0;
    margin-bottom: 12px;
    font-size: 14px;
}

.breadcrumb-item a {
    color: var(--text-muted);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 4px;
}

.breadcrumb-item.active {
    color: var(--text-dark);
    font-weight: 600;
}

.page-title {
    font-size: 28px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.page-title i {
    color: var(--primary-blue);
}

.page-subtitle {
    color: var(--text-muted);
    margin: 4px 0 0 0;
    font-size: 14px;
}

/* Stats Cards - sama seperti sertijab */
.stats-card {
    background: white;
    border-radius: var(--border-radius);
    padding: 24px;
    border: 1px solid var(--border-color);
    transition: var(--transition);
    display: flex;
    align-items: center;
    gap: 16px;
    position: relative;
    overflow: hidden;
}

.stats-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--accent-color);
}

.stats-card-primary {
    --accent-color: var(--primary-blue);
}

.stats-card-primary .stats-icon {
    background: linear-gradient(135deg, var(--primary-blue), #3b82f6);
}

.stats-card-success {
    --accent-color: var(--success-color);
}

.stats-card-success .stats-icon {
    background: linear-gradient(135deg, var(--success-color), #34d399);
}

.stats-card-warning {
    --accent-color: var(--warning-color);
}

.stats-card-warning .stats-icon {
    background: linear-gradient(135deg, var(--warning-color), #fbbf24);
}

.stats-card-info {
    --accent-color: var(--info-color);
}

.stats-card-info .stats-icon {
    background: linear-gradient(135deg, var(--info-color), #22d3ee);
}

.stats-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-medium);
}

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    flex-shrink: 0;
}

.stats-content {
    flex: 1;
}

.stats-number {
    font-size: 24px;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 4px;
}

.stats-label {
    color: var(--text-dark);
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 2px;
}

.stats-description {
    color: var(--text-muted);
    font-size: 12px;
    line-height: 1.4;
}

.progress-indicator {
    font-weight: 600;
}

.progress-success {
    color: var(--success-color);
}

.progress-warning {
    color: var(--warning-color);
}

.progress-danger {
    color: var(--danger-color);
}

/* Table Section - sama seperti sertijab */
.table-section {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-light);
    border: 1px solid var(--border-color);
    overflow: hidden;
    margin-bottom: 24px;
}

.table-card {
    background: white;
    border-radius: var(--border-radius);
    overflow: hidden;
}

.table-header {
    background: var(--background-light);
    padding: 20px 24px;
    border-bottom: 2px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.table-title {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: var(--text-dark);
    display: flex;
    align-items: center;
}

.table-info {
    color: var(--text-muted);
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 12px;
}

/* Table Styling - sama seperti sertijab */
.table-data {
    margin: 0;
    background: white;
}

.table-data th {
    background: var(--background-light);
    color: var(--text-dark);
    font-weight: 600;
    font-size: 13px;
    padding: 16px 12px;
    border-bottom: 2px solid var(--border-color);
    vertical-align: middle;
}

.table-data td {
    padding: 16px 12px;
    border-bottom: 1px solid var(--border-color);
    font-size: 13px;
    vertical-align: middle;
}

.table-row:hover {
    background-color: #f8fafc;
    transition: var(--transition);
}

.code-badge {
    background: #f3f4f6;
    color: var(--text-dark);
    padding: 6px 10px;
    border-radius: 6px;
    font-family: 'Courier New', monospace;
    font-size: 11px;
    font-weight: 600;
}

/* Kapal Info */
.kapal-info strong {
    color: var(--text-dark);
    font-size: 13px;
    display: block;
}

.kapal-info small {
    font-size: 11px;
}

/* Status Badges - sama seperti sertijab */
.status-badge {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    white-space: nowrap;
}

.status-draft {
    background: #fef3c7;
    color: #92400e;
}

.status-partial {
    background: #dbeafe;
    color: #1e40af;
}

.status-final {
    background: #d1fae5;
    color: #065f46;
}

.status-unknown {
    background: #f3f4f6;
    color: #374151;
}

/* Action Buttons - sama seperti sertijab */
.action-buttons-mini {
    display: flex;
    gap: 4px;
}

.btn-action {
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    transition: var(--transition);
}

.btn-view {
    background: #dbeafe;
    color: #1e40af;
}

.btn-view:hover {
    background: #bfdbfe;
    transform: scale(1.05);
}

.btn-verify {
    background: #d1fae5;
    color: #065f46;
}

.btn-verify:hover {
    background: #a7f3d0;
    transform: scale(1.05);
}

.btn-info {
    background: #e0f2fe;
    color: #0891b2;
}

.btn-info:hover {
    background: #bae6fd;
    transform: scale(1.05);
}

/* Empty State */
.empty-state {
    padding: 40px 20px;
    text-align: center;
}

/* Table Footer */
.table-footer {
    background: var(--background-light);
    padding: 16px 24px;
    border-top: 1px solid var(--border-color);
}

/* Buttons - sama seperti sertijab */
.btn {
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    border: none;
    cursor: pointer;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    text-decoration: none;
}

.btn-primary {
    background: var(--primary-blue);
    color: white;
}

.btn-primary:hover {
    background: #1d4ed8;
    transform: translateY(-2px);
    box-shadow: var(--shadow-medium);
    color: white;
    text-decoration: none;
}

.btn-outline-secondary {
    background: transparent;
    color: var(--text-muted);
    border: 2px solid var(--border-color);
}

.btn-outline-secondary:hover {
    background: var(--text-muted);
    color: white;
    border-color: var(--text-muted);
    text-decoration: none;
}

/* Chart Container */
.chart-container {
    position: relative;
    height: 240px;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}

.chart-legend {
    font-size: 13px;
}

.chart-legend .rounded-circle {
    flex-shrink: 0;
}

/* Activity List Styles */
.activity-list {
    padding: 0;
    margin: 0;
}

.activity-item {
    display: flex;
    align-items: center;
    padding: 16px 24px;
    border-bottom: 1px solid var(--border-color);
    transition: var(--transition);
    gap: 16px;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-item:hover {
    background-color: #f8fafc;
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    color: white;
    flex-shrink: 0;
}

.activity-primary {
    background: linear-gradient(135deg, var(--primary-blue), #3b82f6);
}

.activity-success {
    background: linear-gradient(135deg, var(--success-color), #34d399);
}

.activity-info {
    background: linear-gradient(135deg, var(--info-color), #22d3ee);
}

.activity-warning {
    background: linear-gradient(135deg, var(--warning-color), #fbbf24);
}

.activity-danger {
    background: linear-gradient(135deg, var(--danger-color), #f87171);
}

.activity-content {
    flex: 1;
    min-width: 0;
}

.activity-title {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 4px;
}

.activity-description {
    font-size: 13px;
    color: var(--text-muted);
    margin-bottom: 6px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.activity-time {
    font-size: 11px;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: 4px;
}

.activity-time i {
    font-size: 10px;
}

.activity-action {
    flex-shrink: 0;
}

.activity-footer {
    padding: 16px 24px;
    background: var(--background-light);
    border-top: 1px solid var(--border-color);
}

/* Responsive Activity List */
@media (max-width: 768px) {
    .activity-item {
        padding: 12px 16px;
        gap: 12px;
    }
    
    .activity-icon {
        width: 36px;
        height: 36px;
        font-size: 14px;
    }
    
    .activity-description {
        white-space: normal;
        overflow: visible;
        text-overflow: initial;
        line-height: 1.3;
    }
    
    .activity-action .btn {
        padding: 4px 8px;
        font-size: 11px;
    }
    
    .chart-container {
        height: 200px;
    }
}

/* Chart responsive */
#verificationChart {
    max-width: 100%;
    height: auto !important;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Verification Status Pie Chart
    const ctx = document.getElementById('verificationChart');
    if (ctx) {
        @if($chartData['total'] > 0)
        const chart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($chartData['labels']) !!},
                datasets: [{
                    data: {!! json_encode($chartData['values']) !!},
                    backgroundColor: {!! json_encode($chartData['colors']) !!},
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverBorderWidth: 3,
                    hoverBorderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: true,
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : '0.0';
                                return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                            }
                        },
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: '#ffffff',
                        borderWidth: 1,
                        cornerRadius: 6,
                        displayColors: true,
                        titleFont: {
                            size: 13,
                            weight: '600'
                        },
                        bodyFont: {
                            size: 12
                        }
                    }
                },
                cutout: '60%',
                animation: {
                    animateRotate: true,
                    duration: 1000,
                    easing: 'easeOutQuart'
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
        
        // Add click event for chart segments
        ctx.onclick = function(event) {
            const points = chart.getElementsAtEventForMode(event, 'nearest', { intersect: true }, true);
            if (points.length) {
                const firstPoint = points[0];
                const label = chart.data.labels[firstPoint.index];
                console.log('Clicked on:', label);
                // You can add navigation to specific filtered view here
            }
        };
        @else
        // Show message for no data
        const canvasContainer = ctx.parentElement;
        canvasContainer.innerHTML = `
            <div class="empty-state text-center py-4">
                <i class="bi bi-pie-chart display-4 text-muted mb-3"></i>
                <h6 class="text-muted">Belum Ada Data</h6>
                <p class="text-muted mb-0">Data akan muncul setelah ada mutasi yang perlu sertijab</p>
            </div>
        `;
        @endif
    }
    
    // Auto refresh chart data every 5 minutes
    setInterval(function() {
        // Only reload if page is visible
        if (!document.hidden) {
            fetch(window.location.href, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                // Parse and update chart data if needed
                console.log('Chart data refreshed');
            })
            .catch(error => {
                console.log('Auto-refresh failed:', error);
            });
        }
    }, 300000); // 5 minutes
    
    // Handle page visibility change
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            // Page became visible, refresh data
            console.log('Page visible, refreshing data');
        }
    });
});
</script>
@endpush