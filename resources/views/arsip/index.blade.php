{{-- filepath: c:\laragon\www\SertijabPelni\resources\views\arsip\index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Arsip Sertijab')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark mb-0">
            <i class="bi bi-archive-fill me-2"></i> Data Arsip Sertijab
        </h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Arsip Sertijab</li>
            </ol>
        </nav>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <!-- Total Arsip -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted fw-semibold mb-1">Total Arsip</h6>
                            <h3 class="fw-bold mb-2">{{ $stats['total_arsip'] }}</h3>
                            <p class="small text-muted mb-0">Dokumen tersimpan</p>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="bi bi-archive-fill text-primary fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Final Documents -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted fw-semibold mb-1">Final</h6>
                            <h3 class="fw-bold mb-2">{{ $stats['final_arsip'] }}</h3>
                            <p class="small text-muted mb-0">
                                <span class="text-success">{{ $stats['completion_rate'] }}%</span> dari total arsip
                            </p>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-check-circle-fill text-success fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Draft Documents -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted fw-semibold mb-1">Draft</h6>
                            <h3 class="fw-bold mb-2">{{ $stats['draft_arsip'] }}</h3>
                            <p class="small text-muted mb-0">
                                @if($stats['draft_arsip'] > 0)
                                    <span class="text-warning">Perlu tindak lanjut</span>
                                @else
                                    <span class="text-success">Semua final</span>
                                @endif
                            </p>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="bi bi-file-earmark-text text-warning fs-3"></i>
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
                            <h3 class="fw-bold mb-2">{{ $stats['pending_verification'] }}</h3>
                            <p class="small text-muted mb-0">
                                @if($stats['rejected_documents'] > 0)
                                    <span class="text-danger">{{ $stats['rejected_documents'] }} ditolak</span>
                                @else
                                    <span class="text-info">Tidak ada yang ditolak</span>
                                @endif
                            </p>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="bi bi-hourglass-split text-info fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Chart & Quick Actions -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0">Grafik Arsip Bulanan ({{ date('Y') }})</h5>
                    <a href="{{ route('arsip.laporan') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-graph-up me-1"></i> Lihat Laporan
                    </a>
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Aksi Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <a href="{{ route('arsip.search') }}" class="btn btn-outline-primary">
                            <i class="bi bi-search me-2"></i> Pencarian Arsip
                        </a>
                        <a href="{{ route('arsip.create') }}" class="btn btn-outline-success">
                            <i class="bi bi-plus-circle me-2"></i> Tambah Arsip Manual
                        </a>
                        <a href="{{ route('monitoring.sertijab') }}" class="btn btn-outline-info">
                            <i class="bi bi-eye me-2"></i> Monitoring Sertijab
                        </a>
                        <a href="{{ route('arsip.laporan') }}" class="btn btn-outline-dark">
                            <i class="bi bi-file-earmark-pdf me-2"></i> Generate Laporan
                        </a>
                    </div>

                    <hr class="my-4">

                    <div class="small text-muted mb-3">
                        <strong>Status Distribution:</strong>
                    </div>
                    <div class="mb-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="small">Final</span>
                            <span class="small fw-medium">{{ $stats['completion_rate'] }}%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $stats['completion_rate'] }}%"></div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="small">Draft</span>
                            <span class="small fw-medium">{{ 100 - $stats['completion_rate'] }}%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ 100 - $stats['completion_rate'] }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kapal Selection Cards -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0">Pilih Kapal untuk Melihat Arsip</h5>
                    <small class="text-muted">Klik kapal untuk melihat arsip sertijab</small>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @forelse($kapalList as $kapal)
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <a href="{{ route('arsip.search', ['kapal_id' => $kapal->id_kapal]) }}" class="text-decoration-none">
                                <div class="card h-100 border kapal-card hover-shadow">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start">
                                            <div class="bg-info bg-opacity-10 p-3 rounded me-3 flex-shrink-0">
                                                <i class="bi bi-ship text-info fs-4"></i>
                                            </div>
                                            <div class="flex-grow-1 min-w-0">
                                                <h6 class="fw-bold mb-1 text-truncate">{{ $kapal->nama_kapal }}</h6>
                                                <p class="small text-muted mb-2">{{ $kapal->jenis_kapal ?? 'Kapal Penumpang' }}</p>
                                                
                                                <div class="row text-center">
                                                    <div class="col-4">
                                                        <div class="small text-muted">Total</div>
                                                        <div class="fw-bold text-primary">{{ $kapal->total_arsip }}</div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="small text-muted">Final</div>
                                                        <div class="fw-bold text-success">{{ $kapal->final_arsip }}</div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="small text-muted">Draft</div>
                                                        <div class="fw-bold text-warning">{{ $kapal->draft_arsip }}</div>
                                                    </div>
                                                </div>

                                                @if($kapal->total_arsip > 0)
                                                <div class="mt-2">
                                                    @php
                                                        $completion = round(($kapal->final_arsip / $kapal->total_arsip) * 100);
                                                    @endphp
                                                    <div class="progress" style="height: 4px;">
                                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $completion }}%"></div>
                                                    </div>
                                                    <div class="small text-muted mt-1">{{ $completion }}% final</div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-transparent border-top-0 pt-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">Klik untuk lihat arsip</small>
                                            <i class="bi bi-arrow-right text-muted"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="bi bi-ship fs-1 text-muted mb-3"></i>
                                <h5 class="text-muted">Belum Ada Data Kapal</h5>
                                <p class="text-muted">Tambahkan data kapal terlebih dahulu</p>
                                <a href="{{ route('kapal.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah Kapal
                                </a>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.kapal-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.kapal-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1) !important;
}

.hover-shadow {
    transition: box-shadow 0.3s ease;
}

.min-w-0 {
    min-width: 0;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Monthly Chart
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        
        const monthlyChart = new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Dokumen Arsip',
                    data: @json($monthlyData),
                    borderColor: '#2A3F8E',
                    backgroundColor: 'rgba(42, 63, 142, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#2A3F8E',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                elements: {
                    point: {
                        hoverBackgroundColor: '#2A3F8E'
                    }
                }
            }
        });
    });
</script>
@endpush