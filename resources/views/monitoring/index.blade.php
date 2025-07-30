{{-- filepath: c:\laragon\www\SertijabPelni\resources\views\monitoring\index.blade.php --}}
@extends('layouts.app')

@section('title', 'Monitoring Dashboard')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark mb-0">
            <i class="bi bi-graph-up-arrow me-2"></i> Monitoring Dashboard
        </h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Monitoring</li>
            </ol>
        </nav>
    </div>

    <!-- Info Cards -->
    <div class="row g-4 mb-4">
        <!-- Total ABK Mutasi -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted fw-semibold mb-1">Total ABK Mutasi</h6>
                            <h3 class="fw-bold mb-2">{{ $stats['total_mutasi'] }}</h3>
                            <p class="small text-muted mb-0">ABK yang memerlukan sertijab</p>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="bi bi-people-fill text-primary fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submitted Documents -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted fw-semibold mb-1">Dokumen Terkumpul</h6>
                            <h3 class="fw-bold mb-2">{{ $stats['sertijab_submitted'] }}</h3>
                            <p class="small text-muted mb-0">
                                <span class="text-{{ $stats['submission_progress'] < 50 ? 'danger' : ($stats['submission_progress'] < 80 ? 'warning' : 'success') }}">
                                    {{ $stats['submission_progress'] }}%
                                </span> dari total ABK mutasi
                            </p>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-file-earmark-check text-success fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Verified Documents -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted fw-semibold mb-1">Terverifikasi</h6>
                            <h3 class="fw-bold mb-2">{{ $stats['sertijab_verified'] }}</h3>
                            <p class="small text-muted mb-0">
                                <span class="text-{{ $stats['verification_progress'] < 50 ? 'danger' : ($stats['verification_progress'] < 80 ? 'warning' : 'success') }}">
                                    {{ $stats['verification_progress'] }}%
                                </span> dari total ABK mutasi
                            </p>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="bi bi-patch-check-fill text-info fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Verification -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted fw-semibold mb-1">Menunggu Verifikasi</h6>
                            <h3 class="fw-bold mb-2">{{ $stats['sertijab_pending'] }}</h3>
                            <p class="small text-muted mb-0">
                                @if($stats['sertijab_pending'] > 0)
                                    <span class="text-warning">Perlu ditindaklanjuti</span>
                                @else
                                    <span class="text-success">Tidak ada dokumen tertunda</span>
                                @endif
                            </p>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="bi bi-hourglass-split text-warning fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress & Status -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0">Status Sertijab per Kapal</h5>
                    <a href="{{ route('monitoring.sertijab') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-list-ul me-1"></i> Lihat Detail
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Kapal</th>
                                    <th>Mutasi</th>
                                    <th>Dokumen Terkumpul</th>
                                    <th>Progress</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($monitoringData as $data)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-info bg-opacity-10 p-2 rounded me-2">
                                                <i class="bi bi-ship text-info"></i>
                                            </div>
                                            <span class="fw-medium">{{ $data->nama_kapal }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $data->total_mutasi }}</td>
                                    <td>{{ $data->submitted }}/{{ $data->total_mutasi }}</td>
                                    <td>
                                        @if($data->total_mutasi > 0)
                                            @php
                                                $percentage = round(($data->submitted / $data->total_mutasi) * 100);
                                                $colorClass = $percentage < 50 ? 'danger' : ($percentage < 80 ? 'warning' : 'success');
                                            @endphp
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-{{ $colorClass }}" 
                                                     role="progressbar" 
                                                     style="width: {{ $percentage }}%;" 
                                                     aria-valuenow="{{ $percentage }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                </div>
                                            </div>
                                            <small class="text-{{ $colorClass }} fw-medium">{{ $percentage }}%</small>
                                        @else
                                            <span class="badge bg-secondary">Tidak Ada Data</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($data->total_mutasi == 0)
                                            <span class="badge bg-secondary">Tidak Ada Data</span>
                                        @elseif($data->submitted == 0)
                                            <span class="badge bg-danger">Belum Terkumpul</span>
                                        @elseif($data->submitted < $data->total_mutasi)
                                            <span class="badge bg-warning">Sebagian Terkumpul</span>
                                        @elseif($data->verified == $data->total_mutasi)
                                            <span class="badge bg-success">Semua Terverifikasi</span>
                                        @elseif($data->verified > 0)
                                            <span class="badge bg-info">Sebagian Terverifikasi</span>
                                        @else
                                            <span class="badge bg-primary">Menunggu Verifikasi</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('monitoring.sertijab', ['kapal_id' => $data->id_kapal]) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="bi bi-inbox fs-1 text-muted mb-2"></i>
                                            <p class="mb-0">Belum ada data monitoring</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0">Status Verifikasi</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <canvas id="verificationChart" height="240"></canvas>
                    </div>
                    
                    <div class="mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                <div class="bg-success rounded-circle me-2" style="width:12px;height:12px;"></div>
                                <span>Terverifikasi</span>
                            </div>
                            <span class="fw-medium">{{ $stats['sertijab_verified'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning rounded-circle me-2" style="width:12px;height:12px;"></div>
                                <span>Menunggu Verifikasi</span>
                            </div>
                            <span class="fw-medium">{{ $stats['sertijab_pending'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                <div class="bg-danger rounded-circle me-2" style="width:12px;height:12px;"></div>
                                <span>Ditolak</span>
                            </div>
                            <span class="fw-medium">{{ $stats['sertijab_rejected'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="bg-secondary rounded-circle me-2" style="width:12px;height:12px;"></div>
                                <span>Belum Dikumpulkan</span>
                            </div>
                            <span class="fw-medium">{{ $stats['total_mutasi'] - $stats['sertijab_submitted'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest Activity -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0">Aktivitas Terbaru</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <!-- List akan diisi dengan aktivitas terbaru dari model Activity (jika ada) -->
                        <div class="text-center py-5">
                            <i class="bi bi-clock-history fs-1 text-muted mb-2"></i>
                            <p class="mb-0">Belum ada aktivitas terbaru</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Verification Status Chart
        const verificationCtx = document.getElementById('verificationChart').getContext('2d');
        
        const verificationChart = new Chart(verificationCtx, {
            type: 'doughnut',
            data: {
                labels: ['Terverifikasi', 'Menunggu Verifikasi', 'Ditolak', 'Belum Dikumpulkan'],
                datasets: [{
                    data: [
                        {{ $stats['sertijab_verified'] }}, 
                        {{ $stats['sertijab_pending'] }}, 
                        {{ $stats['sertijab_rejected'] }}, 
                        {{ $stats['total_mutasi'] - $stats['sertijab_submitted'] }}
                    ],
                    backgroundColor: [
                        '#28a745',  // success
                        '#ffc107',  // warning
                        '#dc3545',  // danger
                        '#6c757d'   // secondary
                    ],
                    borderWidth: 0,
                    borderRadius: 4,
                    hoverOffset: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    });
</script>
@endpush