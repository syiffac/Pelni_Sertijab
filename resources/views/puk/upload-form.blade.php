{{-- filepath: c:\laragon\www\SertijabPelni\resources\views\puk\upload-form.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Upload Dokumen Mutasi - PELNI PUK</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/pelni_icon.png') }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-blue: #2563eb;
            --secondary-blue: #3b82f6;
            --light-blue: #dbeafe;
            --accent-blue: #1e40af;
            --success-green: #10b981;
            --warning-yellow: #f59e0b;
            --danger-red: #ef4444;
            --text-dark: #1f2937;
            --text-muted: #6b7280;
            --border-color: #e5e7eb;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
            color: var(--text-dark);
        }

        /* Header */
        .header-section {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--accent-blue) 100%);
            color: white;
            padding: 20px 0;
            box-shadow: 0 4px 20px rgba(37, 99, 235, 0.3);
        }

        .header-content {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .back-btn {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            padding: 8px 16px;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            transform: translateX(-3px);
        }

        .header-title {
            font-family: 'Poppins', sans-serif;
            font-size: 1.6rem;
            font-weight: 700;
            margin: 0;
        }

        /* Main content */
        .main-container {
            padding: 40px 0;
        }

        .form-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .form-header {
            background: linear-gradient(135deg, var(--light-blue) 0%, rgba(59, 130, 246, 0.1) 100%);
            padding: 30px;
            border-bottom: 1px solid var(--border-color);
        }

        .form-title {
            font-family: 'Poppins', sans-serif;
            font-size: 1.6rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 10px;
        }

        .form-subtitle {
            color: var(--text-muted);
            margin: 0;
            line-height: 1.6;
        }

        .form-body {
            padding: 40px;
        }

        /* Step indicators */
        .step-indicators {
            display: flex;
            justify-content: center;
            margin-bottom: 40px;
        }

        .step-indicator {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .step {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .step.active {
            background: var(--primary-blue);
            color: white;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .step.completed {
            background: var(--success-green);
            color: white;
        }

        .step.inactive {
            background: var(--border-color);
            color: var(--text-muted);
        }

        .step-divider {
            width: 80px;
            height: 3px;
            background: var(--border-color);
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .step-divider.completed {
            background: var(--success-green);
        }

        /* Form sections */
        .form-section {
            display: none;
        }

        .form-section.active {
            display: block;
            animation: fadeInUp 0.5s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
            display: block;
            font-size: 1rem;
        }

        .form-control {
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 14px 16px;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .form-control:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            outline: none;
        }

        .form-control.has-value {
            border-color: var(--primary-blue);
            background-color: rgba(37, 99, 235, 0.05);
        }

        /* Select2 Custom Styling */
        .select2-container--bootstrap-5 .select2-selection {
            border: 2px solid var(--border-color);
            border-radius: 12px;
            min-height: 52px;
            padding: 8px 12px;
            font-size: 1rem;
        }

        .select2-container--bootstrap-5.select2-container--focus .select2-selection,
        .select2-container--bootstrap-5.select2-container--open .select2-selection {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .select2-container--bootstrap-5 .select2-selection__rendered {
            padding: 0;
            line-height: 1.5;
        }

        .select2-container--bootstrap-5 .select2-selection__placeholder {
            color: var(--text-muted);
        }

        .select2-dropdown {
            border: 2px solid var(--primary-blue);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .select2-results__option {
            padding: 12px 16px;
            transition: all 0.2s ease;
        }

        .select2-results__option--highlighted {
            background-color: var(--light-blue);
            color: var(--primary-blue);
        }

        /* Kapal Info Badge */
        .kapal-info-container {
            margin-top: 20px;
            text-align: center;
            display: none;
        }

        .kapal-badge {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--accent-blue) 100%);
            color: white;
            padding: 12px 24px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 10px;
            animation: fadeInScale 0.5s ease-out;
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .kapal-code {
            background: rgba(255, 255, 255, 0.2);
            padding: 4px 12px;
            border-radius: 15px;
            font-family: 'Courier New', monospace;
            font-weight: 700;
            letter-spacing: 1px;
        }

        /* Buttons */
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--accent-blue) 100%);
            border: none;
            padding: 14px 32px;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 1rem;
        }

        .btn-primary-custom:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.4);
            color: white;
        }

        .btn-primary-custom:disabled {
            background: linear-gradient(135deg, #94a3b8 0%, #64748b 100%);
            cursor: not-allowed;
            opacity: 0.7;
            transform: none;
            box-shadow: none;
        }

        .btn-secondary-custom {
            background: transparent;
            border: 2px solid var(--text-muted);
            padding: 12px 32px;
            border-radius: 12px;
            color: var(--text-muted);
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 1rem;
        }

        .btn-secondary-custom:hover {
            border-color: var(--primary-blue);
            color: var(--primary-blue);
            background: rgba(37, 99, 235, 0.05);
        }

        /* Table Styles */
        .table-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .table-header {
            background: linear-gradient(135deg, var(--light-blue) 0%, rgba(59, 130, 246, 0.1) 100%);
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-color);
        }

        .table-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Submit controls */
        .table-controls {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .table-controls .btn {
            font-size: 0.875rem;
            padding: 6px 12px;
        }

        /* DataTables Custom Styling */
        .dataTables_wrapper {
            padding: 24px;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 20px;
        }

        .dataTables_wrapper .dataTables_filter input {
            border: 2px solid var(--border-color);
            border-radius: 8px;
            padding: 8px 12px;
            margin-left: 8px;
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: var(--primary-blue);
            outline: none;
        }

        .dataTables_wrapper .dataTables_length select {
            border: 2px solid var(--border-color);
            border-radius: 8px;
            padding: 6px 10px;
            margin: 0 8px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 8px;
            margin: 0 2px;
            padding: 8px 12px;
            border: 1px solid var(--border-color);
            background: white;
            color: var(--text-dark);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: var(--light-blue);
            border-color: var(--primary-blue);
            color: var(--primary-blue);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--primary-blue);
            border-color: var(--primary-blue);
            color: white;
        }

        #mutasiTable {
            width: 100% !important;
        }

        #mutasiTable thead th {
            background: var(--light-blue);
            color: var(--primary-blue);
            font-weight: 600;
            padding: 16px 12px;
            border: none;
            font-size: 0.9rem;
        }

        #mutasiTable tbody td {
            padding: 16px 12px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
            font-size: 0.9rem;
        }

        #mutasiTable tbody tr:hover {
            background-color: rgba(37, 99, 235, 0.02);
        }

        /* Status badges */
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-disetujui {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-green);
        }

        .status-selesai {
            background: rgba(37, 99, 235, 0.1);
            color: var(--primary-blue);
        }

        .status-draft {
            background: rgba(107, 114, 128, 0.1);
            color: var(--text-muted);
        }

        /* Document status indicators */
        .doc-status {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .doc-status.complete {
            color: var(--success-green);
        }

        .doc-status.incomplete {
            color: var(--danger-red);
        }

        .doc-status.partial {
            color: var(--warning-yellow);
        }

        /* Checkbox styling */
        .form-check-input {
            cursor: pointer;
        }

        .form-check-input:disabled {
            cursor: not-allowed;
        }

        /* Submitted row styling */
        tr.submitted {
            background-color: rgba(16, 185, 129, 0.05);
            opacity: 0.8;
        }

        tr.submitted td {
            color: var(--text-muted);
        }

        /* Loading */
        .loading-container {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid var(--border-color);
            border-top: 4px solid var(--primary-blue);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Alert messages */
        .alert-custom {
            border-radius: 12px;
            border: none;
            padding: 16px 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-green);
            border-left: 4px solid var(--success-green);
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-red);
            border-left: 4px solid var(--danger-red);
        }

        .alert-info {
            background: rgba(37, 99, 235, 0.1);
            color: var(--primary-blue);
            border-left: 4px solid var(--primary-blue);
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-icon {
            font-size: 4rem;
            color: var(--text-muted);
            margin-bottom: 20px;
        }

        .empty-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 10px;
        }

        .empty-desc {
            color: var(--text-muted);
            line-height: 1.6;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .form-body {
                padding: 24px;
            }

            .header-title {
                font-size: 1.3rem;
            }

            .step {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }

            .step-divider {
                width: 60px;
            }

            .kapal-badge {
                font-size: 0.9rem;
                padding: 10px 20px;
            }

            .table-controls {
                flex-direction: column;
                gap: 8px;
            }
        }

        /* Loading state styles */
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            pointer-events: none;
        }

        .processing-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(2px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .processing-content {
            text-align: center;
            color: var(--primary-blue);
        }

        .processing-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid var(--border-color);
            border-top: 4px solid var(--primary-blue);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 20px;
        }

        /* Alert transitions */
        .alert-custom {
            transition: all 0.3s ease;
        }

        .alert-custom.removing {
            opacity: 0;
            transform: translateY(-20px);
        }

        /* Submit button states */
        .btn-success:disabled {
            background: #6c757d;
            border-color: #6c757d;
        }

        /* Page transition overlay */
        .page-transition {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--accent-blue) 100%);
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            opacity: 0;
            visibility: hidden;
            transition: all 0.5s ease;
        }

        .page-transition.active {
            opacity: 1;
            visibility: visible;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header-section">
        <div class="container">
            <div class="header-content">
                <a href="{{ route('puk.dashboard') }}" class="back-btn">
                    <i class="bi bi-arrow-left"></i>
                    Kembali
                </a>
                <h1 class="header-title">Upload Dokumen Mutasi ABK</h1>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="form-card">
                        <div class="form-header">
                            <h2 class="form-title">Upload Dokumen Mutasi ABK</h2>
                            <p class="form-subtitle">
                                Pilih kapal untuk melihat daftar mutasi ABK, kemudian upload dokumen Serah Terima Jabatan, 
                                Familisasi, dan dokumen lampiran yang diperlukan untuk setiap mutasi.
                            </p>
                        </div>

                        <div class="form-body">
                            <!-- Step Indicators -->
                            <div class="step-indicators">
                                <div class="step-indicator">
                                    <div class="step active" id="step1">1</div>
                                    <div class="step-divider" id="divider1"></div>
                                    <div class="step inactive" id="step2">2</div>
                                </div>
                            </div>

                            <!-- Alert Container -->
                            <div id="alertContainer"></div>

                            <!-- Step 1: Pilih Kapal -->
                            <div class="form-section active" id="section1">
                                <div class="row">
                                    <div class="col-md-8 mx-auto">
                                        <div class="form-group">
                                            <label for="id_kapal" class="form-label">
                                                <i class="bi bi-ship me-2"></i>Pilih Kapal
                                            </label>
                                            <select class="form-control" id="id_kapal" name="id_kapal">
                                                <option value="">-- Pilih Kapal untuk Melihat Daftar Mutasi --</option>
                                                @foreach($kapals as $kapal)
                                                    <option value="{{ $kapal->id }}" 
                                                            data-kode="{{ $kapal->kode_kapal ?? 'KPL' . str_pad($kapal->id, 3, '0', STR_PAD_LEFT) }}"
                                                            data-home-base="{{ $kapal->home_base }}">
                                                        {{ $kapal->nama_kapal }}
                                                        @if($kapal->home_base)
                                                            - {{ $kapal->home_base }}
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Kapal Info Badge -->
                                        <div class="kapal-info-container" id="kapalInfoContainer">
                                            <div class="kapal-badge" id="kapalBadge">
                                                <i class="bi bi-ship"></i>
                                                <span id="selectedKapalName">Nama Kapal</span>
                                                <span class="kapal-code" id="selectedKapalCode">KPL001</span>
                                            </div>
                                        </div>

                                        <div class="text-center mt-4">
                                            <button type="button" class="btn-primary-custom" id="nextStep1">
                                                <i class="bi bi-arrow-right"></i>
                                                Tampilkan Daftar Mutasi
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2: Daftar Mutasi dan Upload -->
                            <div class="form-section" id="section2">
                                <!-- Kapal Info Header -->
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h4 class="mb-0">
                                        <i class="bi bi-list-task me-2"></i>
                                        Daftar Mutasi ABK
                                    </h4>
                                    <span class="text-muted" id="kapalInfo">
                                        <i class="bi bi-ship me-1"></i>
                                        <span id="kapalName"></span>
                                    </span>
                                </div>

                                <!-- Loading state -->
                                <div id="loadingMutasi" style="display: none;">
                                    <div class="loading-container">
                                        <div class="spinner"></div>
                                        <span class="ms-3 text-muted">Memuat data mutasi...</span>
                                    </div>
                                </div>

                                <!-- Empty state -->
                                <div id="emptyMutasi" style="display: none;">
                                    <div class="empty-state">
                                        <i class="bi bi-inbox empty-icon"></i>
                                        <h5 class="empty-title">Tidak Ada Mutasi Ditemukan</h5>
                                        <p class="empty-desc">
                                            Belum ada mutasi yang disetujui untuk kapal ini, atau semua mutasi sudah memiliki dokumen lengkap.
                                        </p>
                                    </div>
                                </div>

                                <!-- Mutasi Table -->
                                <div class="table-container" id="tableContainer" style="display: none;">
                                    <div class="table-header">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="table-title mb-0">
                                                <i class="bi bi-table"></i>
                                                Daftar Mutasi ABK
                                            </h5>
                                            <div class="table-controls">
                                                <button type="button" class="btn btn-success btn-sm me-2" id="batchSubmitBtn" style="display: none;">
                                                    <i class="bi bi-check-circle"></i>
                                                    Submit Terpilih ke Admin
                                                </button>
                                                <button type="button" class="btn btn-outline-primary btn-sm" id="selectAllBtn">
                                                    <i class="bi bi-check-all"></i>
                                                    Pilih Semua
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <table id="mutasiTable" class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th width="40">
                                                    <input type="checkbox" id="selectAllCheckbox" class="form-check-input">
                                                </th>
                                                <th>ID Mutasi</th>
                                                <th>Periode</th>
                                                <th>ABK Naik</th>
                                                <th>ABK Turun</th>
                                                <th>Status</th>
                                                <th>Status Dokumen</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="mutasiTableBody">
                                            <!-- Data will be populated by JavaScript -->
                                        </tbody>
                                    </table>
                                </div>

                                <div class="text-center mt-4">
                                    <button type="button" class="btn-secondary-custom" id="backStep1">
                                        <i class="bi bi-arrow-left"></i>
                                        Pilih Kapal Lain
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Upload Dokumen Mutasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="uploadModalContent">
                        <!-- Content will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery (required for Select2 and DataTables) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    
    // Ganti bagian JavaScript dengan yang sudah diperbaiki ini:

<script>
// Global variables
let dataTable = null;
let currentMutasis = [];
let isSubmitting = false;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2
    $('#id_kapal').select2({
        theme: 'bootstrap-5',
        placeholder: '-- Pilih Kapal untuk Melihat Daftar Mutasi --',
        allowClear: true
    });

    // Disable next button initially
    document.getElementById('nextStep1').disabled = true;

    // Setup event listeners
    setupEventListeners();
});

function setupEventListeners() {
    // Select2 change event
    $('#id_kapal').on('change', function() {
        const kapalId = $(this).val();
        const selectedOption = $(this).find('option:selected');
        
        if (kapalId) {
            document.getElementById('nextStep1').disabled = false;
            showKapalBadge(selectedOption);
        } else {
            document.getElementById('nextStep1').disabled = true;
            hideKapalBadge();
        }
    });

    // Next step button
    document.getElementById('nextStep1').addEventListener('click', function() {
        const kapalId = $('#id_kapal').val();
        if (kapalId) {
            goToStep2();
            loadMutasiData(kapalId);
        }
    });

    // Back button
    document.getElementById('backStep1').addEventListener('click', goToStep1);

    // Select all checkbox
    document.getElementById('selectAllCheckbox').addEventListener('change', function() {
        const isChecked = this.checked;
        document.querySelectorAll('.mutasi-checkbox').forEach(cb => {
            if (!cb.disabled) {
                cb.checked = isChecked;
            }
        });
        updateBatchSubmitBtn();
    });

    // Select all button
    document.getElementById('selectAllBtn').addEventListener('click', function() {
        const selectAllCb = document.getElementById('selectAllCheckbox');
        selectAllCb.checked = !selectAllCb.checked;
        selectAllCb.dispatchEvent(new Event('change'));
    });

    // Batch submit button
    document.getElementById('batchSubmitBtn').addEventListener('click', batchSubmit);

    // Individual checkboxes
    document.addEventListener('change', function(e) {
        if (e.target.matches('.mutasi-checkbox')) {
            updateBatchSubmitBtn();
        }
    });

    // File uploads
    document.addEventListener('change', function(e) {
        if (e.target.matches('input[type="file"]') && e.target.dataset.mutasiId) {
            const file = e.target.files[0];
            if (file) {
                uploadFile(e.target.dataset.mutasiId, e.target.dataset.jenis, file);
            }
        }
    });
}

function showKapalBadge(selectedOption) {
    const kapalName = selectedOption.text().split(' - ')[0];
    const kapalCode = selectedOption.data('kode') || `KPL${String(selectedOption.val()).padStart(3, '0')}`;
    
    document.getElementById('selectedKapalName').textContent = kapalName;
    document.getElementById('selectedKapalCode').textContent = kapalCode;
    document.getElementById('kapalInfoContainer').style.display = 'block';
}

function hideKapalBadge() {
    document.getElementById('kapalInfoContainer').style.display = 'none';
}

function goToStep2() {
    document.getElementById('step1').className = 'step completed';
    document.getElementById('step2').className = 'step active';
    document.getElementById('divider1').classList.add('completed');
    
    document.getElementById('section1').classList.remove('active');
    document.getElementById('section2').classList.add('active');
}

function goToStep1() {
    document.getElementById('step1').className = 'step active';
    document.getElementById('step2').className = 'step inactive';
    document.getElementById('divider1').classList.remove('completed');
    
    document.getElementById('section2').classList.remove('active');
    document.getElementById('section1').classList.add('active');
    
    if (dataTable) {
        dataTable.destroy();
        dataTable = null;
    }
}

function loadMutasiData(kapalId) {
    showLoading(true);
    
    fetch('{{ route("puk.get-mutasi-by-kapal") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ id_kapal: kapalId })
    })
    .then(response => response.json())
    .then(data => {
        showLoading(false);
        
        if (data.success) {
            document.getElementById('kapalName').textContent = data.kapal.nama_kapal;
            currentMutasis = data.mutasis;
            
            if (data.mutasis.length === 0) {
                showEmptyState();
            } else {
                showMutasiTable(data.mutasis);
            }
        } else {
            showAlert('Gagal memuat data mutasi: ' + data.message, 'error');
            showEmptyState();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showLoading(false);
        showAlert('Terjadi kesalahan saat memuat data.', 'error');
        showEmptyState();
    });
}

function showLoading(show) {
    document.getElementById('loadingMutasi').style.display = show ? 'block' : 'none';
    document.getElementById('emptyMutasi').style.display = 'none';
    document.getElementById('tableContainer').style.display = 'none';
}

function showEmptyState() {
    document.getElementById('emptyMutasi').style.display = 'block';
    document.getElementById('tableContainer').style.display = 'none';
}

function showMutasiTable(mutasis) {
    const tableBody = document.getElementById('mutasiTableBody');
    tableBody.innerHTML = '';

    mutasis.forEach(mutasi => {
        const row = createTableRow(mutasi);
        tableBody.appendChild(row);
    });

    document.getElementById('tableContainer').style.display = 'block';
    
    // Initialize DataTable
    if (dataTable) {
        dataTable.destroy();
    }
    
    dataTable = $('#mutasiTable').DataTable({
        responsive: true,
        pageLength: 10,
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        },
        columnDefs: [
            { orderable: false, targets: [0, 7] }
        ],
        order: [[2, 'desc']]
    });
}

function createTableRow(mutasi) {
    const tr = document.createElement('tr');
    const docStatus = getDocumentStatus(mutasi.dokumen);
    const isComplete = docStatus.class === 'complete';
    const isSubmitted = mutasi.submitted_by_puk;
    
    tr.innerHTML = `
        <td>
            ${!isSubmitted && isComplete ? `
                <input type="checkbox" class="form-check-input mutasi-checkbox" value="${mutasi.id}">
            ` : `
                <input type="checkbox" class="form-check-input" disabled>
            `}
        </td>
        <td>
            <strong>MUT-${String(mutasi.id).padStart(4, '0')}</strong>
            ${isSubmitted ? '<br><small class="badge bg-success">Submitted</small>' : ''}
        </td>
        <td>
            <div>${mutasi.periode || '-'}</div>
            <small class="text-muted">${mutasi.jenis_mutasi || 'Definitif'}</small>
        </td>
        <td>
            <div class="fw-bold">${mutasi.abk_naik.nama}</div>
            <small class="text-muted">
                ${mutasi.abk_naik.jabatan_tetap} → ${mutasi.abk_naik.jabatan_mutasi}
            </small>
        </td>
        <td>
            ${mutasi.abk_turun ? `
                <div class="fw-bold">${mutasi.abk_turun.nama}</div>
                <small class="text-muted">
                    ${mutasi.abk_turun.jabatan_tetap} → ${mutasi.abk_turun.jabatan_mutasi}
                </small>
            ` : `
                <span class="text-muted">-</span>
            `}
        </td>
        <td>
            <span class="status-badge status-${mutasi.status_mutasi.toLowerCase()}">
                ${mutasi.status_mutasi}
            </span>
        </td>
        <td>
            <div class="doc-status ${docStatus.class}">
                <i class="bi bi-${docStatus.icon}"></i>
                ${docStatus.text}
            </div>
            <div class="mt-1">
                <small class="text-muted">
                    ${mutasi.dokumen.sertijab ? '✓' : '✗'} Sertijab |
                    ${mutasi.dokumen.familisasi ? '✓' : '✗'} Familisasi |
                    ${mutasi.dokumen.lampiran ? '✓' : '✗'} Lampiran
                </small>
            </div>
        </td>
        <td>
            <div class="d-grid gap-1">
                <button type="button" class="btn btn-primary btn-sm" onclick="openUploadModal(${mutasi.id})">
                    <i class="bi bi-upload"></i> Upload
                </button>
                ${isComplete && !isSubmitted ? `
                    <button type="button" class="btn btn-success btn-sm" onclick="submitSingle(${mutasi.id})">
                        <i class="bi bi-send"></i> Submit
                    </button>
                ` : ''}
            </div>
        </td>
    `;
    
    return tr;
}

function getDocumentStatus(dokumen) {
    const sertijab = dokumen.sertijab;
    const familisasi = dokumen.familisasi;
    
    if (sertijab && familisasi) {
        return { class: 'complete', icon: 'check-circle-fill', text: 'Lengkap' };
    } else if (sertijab || familisasi) {
        return { class: 'partial', icon: 'exclamation-triangle-fill', text: 'Sebagian' };
    } else {
        return { class: 'incomplete', icon: 'x-circle-fill', text: 'Belum Lengkap' };
    }
}

function updateBatchSubmitBtn() {
    const checkedBoxes = document.querySelectorAll('.mutasi-checkbox:checked');
    const btn = document.getElementById('batchSubmitBtn');
    
    if (checkedBoxes.length > 0) {
        btn.style.display = 'inline-block';
        btn.innerHTML = `<i class="bi bi-check-circle"></i> Submit ${checkedBoxes.length} Terpilih ke Admin`;
    } else {
        btn.style.display = 'none';
    }
}

// Global functions
window.openUploadModal = function(mutasiId) {
    const mutasi = currentMutasis.find(m => m.id === mutasiId);
    if (!mutasi) return;

    const modalContent = createUploadModalContent(mutasi);
    document.getElementById('uploadModalContent').innerHTML = modalContent;
    document.getElementById('uploadModalLabel').textContent = 'Upload Dokumen Mutasi';

    const modal = new bootstrap.Modal(document.getElementById('uploadModal'));
    modal.show();
};

window.submitSingle = function(mutasiId) {
    // Confirmation dengan peringatan redirect
    const confirmed = confirm(
        'Apakah Anda yakin ingin submit mutasi ini ke admin?\n\n' +
        'Setelah submit berhasil, Anda akan diarahkan kembali ke dashboard dan ' +
        'mutasi ini tidak akan muncul lagi di daftar.'
    );
    
    if (!confirmed) return;
    
    submitMutasi([mutasiId]);
};

function batchSubmit() {
    const selectedIds = Array.from(document.querySelectorAll('.mutasi-checkbox:checked')).map(cb => cb.value);
    
    if (selectedIds.length === 0) {
        showAlert('Pilih minimal satu mutasi untuk disubmit.', 'error');
        return;
    }
    
    // Confirmation dengan peringatan redirect
    const confirmed = confirm(
        `Apakah Anda yakin ingin submit ${selectedIds.length} mutasi ke admin?\n\n` +
        'Setelah submit berhasil, Anda akan diarahkan kembali ke dashboard dan ' +
        'mutasi yang disubmit tidak akan muncul lagi di daftar.'
    );
    
    if (!confirmed) return;
    
    submitMutasi(selectedIds);
}

// PERBAIKAN: Hapus duplikasi dan buat function submit yang clean
function submitMutasi(mutasiIds) {
    // Cegah submit ganda
    if (isSubmitting) {
        console.log('Submit already in progress');
        return;
    }
    
    isSubmitting = true;
    
    const url = mutasiIds.length === 1 ? '{{ route("puk.submit-dokumen") }}' : '{{ route("puk.batch-submit-dokumen") }}';
    const data = mutasiIds.length === 1 ? 
        { id_mutasi: mutasiIds[0] } : 
        { mutasi_ids: mutasiIds };

    // Show loading state
    const submitButtons = document.querySelectorAll('button[onclick*="submitSingle"], #batchSubmitBtn');
    submitButtons.forEach(btn => {
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Memproses...';
    });

    // Disable semua interaksi
    document.body.style.pointerEvents = 'none';
    document.body.style.cursor = 'wait';

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        console.log('Submit response:', data);
        
        if (data.success) {
            showAlert(data.message, 'success');
            
            // Auto redirect ke dashboard setelah 2 detik
            setTimeout(() => {
                showAlert('Mengarahkan ke dashboard...', 'info');
                setTimeout(() => {
                    console.log('Redirecting to dashboard...');
                    window.location.href = '{{ route("puk.dashboard") }}';
                }, 1000);
            }, 2000);
            
        } else {
            // Reset jika error
            isSubmitting = false;
            document.body.style.pointerEvents = 'auto';
            document.body.style.cursor = 'default';
            
            showAlert(data.message, 'error');
            
            // Re-enable buttons
            submitButtons.forEach(btn => {
                btn.disabled = false;
                btn.innerHTML = btn.innerHTML.replace('<i class="bi bi-hourglass-split"></i> Memproses...', '<i class="bi bi-send"></i> Submit');
            });
        }
    })
    .catch(error => {
        console.error('Submit error:', error);
        
        // Reset jika error
        isSubmitting = false;
        document.body.style.pointerEvents = 'auto';
        document.body.style.cursor = 'default';
        
        showAlert('Terjadi kesalahan saat submit dokumen.', 'error');
        
        // Re-enable buttons
        submitButtons.forEach(btn => {
            btn.disabled = false;
            btn.innerHTML = btn.innerHTML.replace('<i class="bi bi-hourglass-split"></i> Memproses...', '<i class="bi bi-send"></i> Submit');
        });
    });
}

function createUploadModalContent(mutasi) {
    return `
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Mutasi ID:</strong> MUT-${String(mutasi.id).padStart(4, '0')}
            </div>
            <div class="col-md-6">
                <strong>Periode:</strong> ${mutasi.periode || '-'}
            </div>
        </div>
        
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="border rounded p-3">
                    <h6 class="text-primary mb-2">
                        <i class="bi bi-person-plus"></i> ABK Naik
                    </h6>
                    <div><strong>${mutasi.abk_naik.nama}</strong></div>
                    <small class="text-muted">
                        ${mutasi.abk_naik.jabatan_tetap} → ${mutasi.abk_naik.jabatan_mutasi}
                    </small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="border rounded p-3">
                    <h6 class="text-primary mb-2">
                        <i class="bi bi-person-dash"></i> ABK Turun
                    </h6>
                    ${mutasi.abk_turun ? `
                        <div><strong>${mutasi.abk_turun.nama}</strong></div>
                        <small class="text-muted">
                            ${mutasi.abk_turun.jabatan_tetap} → ${mutasi.abk_turun.jabatan_mutasi}
                        </small>
                    ` : `
                        <span class="text-muted">Tidak ada ABK turun</span>
                    `}
                </div>
            </div>
        </div>

        <h6 class="mb-3">Upload Dokumen:</h6>
        
        <div class="row">
            <div class="col-md-4">
                ${createUploadCard(mutasi.id, 'sertijab', 'Serah Terima Jabatan', 'Wajib', mutasi.dokumen.sertijab, mutasi.dokumen_urls.sertijab)}
            </div>
            <div class="col-md-4">
                ${createUploadCard(mutasi.id, 'familisasi', 'Familisasi', 'Wajib', mutasi.dokumen.familisasi, mutasi.dokumen_urls.familisasi)}
            </div>
            <div class="col-md-4">
                ${createUploadCard(mutasi.id, 'lampiran', 'Dokumen Lampiran', 'Opsional', mutasi.dokumen.lampiran, mutasi.dokumen_urls.lampiran)}
            </div>
        </div>
    `;
}

function createUploadCard(mutasiId, jenis, label, status, hasFile, fileUrl) {
    const uploadId = `upload-${jenis}-${mutasiId}`;
    
    return `
        <div class="card h-100" id="${uploadId}">
            <div class="card-body text-center">
                <input type="file" id="file-${jenis}-${mutasiId}" class="d-none" 
                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" 
                       data-mutasi-id="${mutasiId}" 
                       data-jenis="${jenis}">
                
                ${hasFile ? `
                    <i class="bi bi-file-earmark-check text-success" style="font-size: 2rem;"></i>
                    <h6 class="mt-2">${label}</h6>
                    <p class="text-success small">
                        <i class="bi bi-check-circle"></i> Sudah diunggah
                    </p>
                    <div class="d-grid gap-2">
                        <a href="${fileUrl}" target="_blank" class="btn btn-success btn-sm">
                            <i class="bi bi-eye"></i> Lihat
                        </a>
                        <button type="button" class="btn btn-danger btn-sm" onclick="deleteDokumen(${mutasiId}, '${jenis}')">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </div>
                ` : `
                    <div onclick="document.getElementById('file-${jenis}-${mutasiId}').click()" style="cursor: pointer;">
                        <i class="bi bi-cloud-upload text-primary" style="font-size: 2rem;"></i>
                        <h6 class="mt-2">${label}</h6>
                        <p class="text-muted small">${status}</p>
                        <button type="button" class="btn btn-primary btn-sm">
                            <i class="bi bi-upload"></i> Upload
                        </button>
                    </div>
                `}
            </div>
        </div>
    `;
}

function uploadFile(mutasiId, jenis, file) {
    const formData = new FormData();
    formData.append('id_mutasi', mutasiId);
    formData.append('jenis_dokumen', jenis);
    formData.append('file', file);

    const uploadCard = document.getElementById(`upload-${jenis}-${mutasiId}`);
    const originalContent = uploadCard.innerHTML;
    
    uploadCard.innerHTML = `
        <div class="card-body text-center">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2 mb-0">Mengunggah...</p>
        </div>
    `;

    fetch('{{ route("puk.upload-dokumen") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message, 'success');
            
            // Update current mutasis data
            const mutasiIndex = currentMutasis.findIndex(m => m.id == mutasiId);
            if (mutasiIndex !== -1) {
                currentMutasis[mutasiIndex].dokumen[jenis] = true;
                currentMutasis[mutasiIndex].dokumen_urls[jenis] = data.file_info.url;
            }
            
            // Refresh modal and table
            openUploadModal(mutasiId);
            showMutasiTable(currentMutasis);
        } else {
            showAlert(data.message, 'error');
            uploadCard.innerHTML = originalContent;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Terjadi kesalahan saat mengunggah file.', 'error');
        uploadCard.innerHTML = originalContent;
    });
}

window.deleteDokumen = function(mutasiId, jenis) {
    if (!confirm('Apakah Anda yakin ingin menghapus dokumen ini?')) return;

    fetch('{{ route("puk.delete-dokumen") }}', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ 
            id_mutasi: mutasiId, 
            jenis_dokumen: jenis 
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message, 'success');
            
            // Update current mutasis data
            const mutasiIndex = currentMutasis.findIndex(m => m.id == mutasiId);
            if (mutasiIndex !== -1) {
                currentMutasis[mutasiIndex].dokumen[jenis] = false;
                currentMutasis[mutasiIndex].dokumen_urls[jenis] = null;
            }
            
            // Refresh modal and table
            openUploadModal(mutasiId);
            showMutasiTable(currentMutasis);
        } else {
            showAlert(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Terjadi kesalahan saat menghapus file.', 'error');
    });
};

function showAlert(message, type) {
    const alertContainer = document.getElementById('alertContainer');
    const alertId = 'alert-' + Date.now();
    
    const alertHtml = `
        <div class="alert-custom alert-${type}" id="${alertId}">
            <i class="bi bi-${type === 'error' ? 'exclamation-triangle' : type === 'success' ? 'check-circle' : 'info-circle'}"></i>
            ${message}
        </div>
    `;
    
    alertContainer.insertAdjacentHTML('beforeend', alertHtml);
    
    // Auto remove alert setelah 5 detik
    setTimeout(() => {
        const alertElement = document.getElementById(alertId);
        if (alertElement) {
            alertElement.style.opacity = '0';
            alertElement.style.transform = 'translateY(-20px)';
            setTimeout(() => alertElement.remove(), 300);
        }
    }, 5000);
}

// Handle page unload prevention during submission
window.addEventListener('beforeunload', function(e) {
    if (isSubmitting) {
        e.preventDefault();
        e.returnValue = 'Sedang memproses submit dokumen. Jangan tutup halaman.';
        return e.returnValue;
    }
});
</script>
</body>
</html>