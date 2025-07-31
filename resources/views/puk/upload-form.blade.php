{{-- filepath: c:\laragon\www\SertijabPelni\resources\views\puk\upload-form.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Upload Dokumen Sertijab - PELNI PUK</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/pelni_icon.png') }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
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
            padding: 14px 0;
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
            padding: 5px 15px;
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
            font-size: 1.5rem;
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
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 10px;
        }

        .form-subtitle {
            color: var(--text-muted);
            margin: 0;
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
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .step.active {
            background: var(--primary-blue);
            color: white;
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
            width: 60px;
            height: 2px;
            background: var(--border-color);
            transition: all 0.3s ease;
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
        }

        .form-control {
            border: 2px solid var(--border-color);
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .form-control:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            outline: none;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--accent-blue) 100%);
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            color: white;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.4);
            color: white;
        }

        .btn-secondary-custom {
            background: transparent;
            border: 2px solid var(--text-muted);
            padding: 10px 30px;
            border-radius: 10px;
            color: var(--text-muted);
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-secondary-custom:hover {
            border-color: var(--primary-blue);
            color: var(--primary-blue);
        }

        /* Mutasi table */
        .mutasi-table-container {
            max-height: 600px;
            overflow-y: auto;
            border: 1px solid var(--border-color);
            border-radius: 10px;
        }

        .mutasi-table {
            margin: 0;
        }

        .mutasi-table th {
            background: var(--light-blue);
            border: none;
            color: var(--text-dark);
            font-weight: 600;
            padding: 15px 12px;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .mutasi-table td {
            border: none;
            border-bottom: 1px solid var(--border-color);
            padding: 15px 12px;
            vertical-align: middle;
        }

        .mutasi-row:hover {
            background: rgba(37, 99, 235, 0.05);
        }

        /* Upload components */
        .upload-section {
            background: var(--light-blue);
            border-radius: 10px;
            padding: 15px;
            margin-top: 10px;
        }

        .upload-area {
            border: 2px dashed var(--primary-blue);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            background: white;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .upload-area:hover {
            border-color: var(--accent-blue);
            background: rgba(37, 99, 235, 0.05);
        }

        .upload-area.dragover {
            border-color: var(--success-green);
            background: rgba(16, 185, 129, 0.05);
        }

        .file-input {
            display: none;
        }

        .upload-icon {
            font-size: 2rem;
            color: var(--primary-blue);
            margin-bottom: 10px;
        }

        .upload-text {
            color: var(--text-dark);
            font-weight: 500;
            margin-bottom: 5px;
        }

        .upload-subtext {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        /* Status badges */
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .status-pending {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning-yellow);
        }

        .status-uploaded {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-green);
        }

        .status-error {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-red);
        }

        /* File preview */
        .file-preview {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }

        .file-icon {
            width: 40px;
            height: 40px;
            background: var(--light-blue);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-blue);
        }

        .file-info {
            flex: 1;
        }

        .file-name {
            font-weight: 500;
            color: var(--text-dark);
            margin-bottom: 2px;
        }

        .file-size {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .file-actions {
            display: flex;
            gap: 5px;
        }

        .btn-sm-custom {
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 0.8rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-success-sm {
            background: var(--success-green);
            color: white;
        }

        .btn-danger-sm {
            background: var(--danger-red);
            color: white;
        }

        .btn-sm-custom:hover {
            transform: translateY(-1px);
        }

        /* Loading states */
        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            z-index: 100;
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
            border-radius: 10px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
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

        /* Responsive */
        @media (max-width: 768px) {
            .form-body {
                padding: 20px;
            }

            .step-indicators {
                overflow-x: auto;
                padding-bottom: 10px;
            }

            .mutasi-table-container {
                font-size: 0.9rem;
            }

            .mutasi-table th,
            .mutasi-table td {
                padding: 10px 8px;
            }
        }

        @media (max-width: 576px) {
            .header-content {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }

            .header-title {
                font-size: 1.4rem;
            }

            .step-indicator {
                gap: 10px;
            }

            .step {
                width: 35px;
                height: 35px;
                font-size: 0.9rem;
            }

            .step-divider {
                width: 40px;
            }
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
                <h1 class="header-title">Upload Dokumen Sertijab</h1>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="form-card">
                        <div class="form-header">
                            <h2 class="form-title">Upload Dokumen Serah Terima Jabatan</h2>
                            <p class="form-subtitle">Pilih kapal dan unggah dokumen sertijab untuk setiap mutasi ABK</p>
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
                                                <option value="">-- Pilih Kapal --</option>
                                                @foreach($kapals as $kapal)
                                                    <option value="{{ $kapal->id_kapal }}">{{ $kapal->nama_kapal }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="text-center">
                                            <button type="button" class="btn-primary-custom" id="nextStep1">
                                                <i class="bi bi-arrow-right"></i>
                                                Lanjutkan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2: Daftar Mutasi -->
                            <div class="form-section" id="section2">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h4 class="mb-0">Daftar Mutasi ABK</h4>
                                    <span class="text-muted" id="kapalName"></span>
                                </div>

                                <!-- Loading state -->
                                <div id="loadingMutasi" style="display: none;">
                                    <div class="text-center py-5">
                                        <div class="spinner mx-auto mb-3"></div>
                                        <p class="text-muted">Memuat data mutasi...</p>
                                    </div>
                                </div>

                                <!-- Empty state -->
                                <div id="emptyMutasi" style="display: none;">
                                    <div class="text-center py-5">
                                        <i class="bi bi-inbox" style="font-size: 3rem; color: var(--text-muted);"></i>
                                        <h5 class="mt-3 text-muted">Tidak ada mutasi yang perlu dokumen sertijab</h5>
                                        <p class="text-muted">Semua mutasi untuk kapal ini sudah memiliki dokumen sertijab atau belum ada mutasi yang terdaftar.</p>
                                    </div>
                                </div>

                                <!-- Mutasi table -->
                                <div id="mutasiTableContainer" style="display: none;">
                                    <div class="mutasi-table-container">
                                        <table class="table mutasi-table">
                                            <thead>
                                                <tr>
                                                    <th>TMT</th>
                                                    <th>ABK Turun</th>
                                                    <th>ABK Naik</th>
                                                    <th>Jabatan</th>
                                                    <th>Kapal</th>
                                                    <th>Upload Dokumen</th>
                                                </tr>
                                            </thead>
                                            <tbody id="mutasiTableBody">
                                                <!-- Data will be populated by JavaScript -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="text-center mt-4">
                                    <button type="button" class="btn-secondary-custom" id="backStep1">
                                        <i class="bi bi-arrow-left"></i>
                                        Kembali
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const step1 = document.getElementById('step1');
            const step2 = document.getElementById('step2');
            const divider1 = document.getElementById('divider1');
            const section1 = document.getElementById('section1');
            const section2 = document.getElementById('section2');
            const nextStep1Btn = document.getElementById('nextStep1');
            const backStep1Btn = document.getElementById('backStep1');
            const kapalSelect = document.getElementById('id_kapal');
            const alertContainer = document.getElementById('alertContainer');

            // Step navigation
            nextStep1Btn.addEventListener('click', function() {
                const kapalId = kapalSelect.value;
                if (!kapalId) {
                    showAlert('Silakan pilih kapal terlebih dahulu.', 'error');
                    return;
                }

                // Update steps
                step1.classList.remove('active');
                step1.classList.add('completed');
                step2.classList.remove('inactive');
                step2.classList.add('active');
                divider1.classList.add('completed');

                // Switch sections
                section1.classList.remove('active');
                section2.classList.add('active');

                // Load mutasi data
                loadMutasiData(kapalId);
            });

            backStep1Btn.addEventListener('click', function() {
                // Update steps
                step1.classList.remove('completed');
                step1.classList.add('active');
                step2.classList.remove('active');
                step2.classList.add('inactive');
                divider1.classList.remove('completed');

                // Switch sections
                section2.classList.remove('active');
                section1.classList.add('active');
            });

            // Load mutasi data
            function loadMutasiData(kapalId) {
                const loadingMutasi = document.getElementById('loadingMutasi');
                const emptyMutasi = document.getElementById('emptyMutasi');
                const mutasiTableContainer = document.getElementById('mutasiTableContainer');
                const kapalName = document.getElementById('kapalName');

                // Show loading
                loadingMutasi.style.display = 'block';
                emptyMutasi.style.display = 'none';
                mutasiTableContainer.style.display = 'none';

                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch('{{ route("puk.get-mutasi-by-kapal") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        id_kapal: kapalId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    loadingMutasi.style.display = 'none';

                    if (data.success) {
                        kapalName.textContent = data.kapal.nama_kapal;

                        if (data.mutasis.length === 0) {
                            emptyMutasi.style.display = 'block';
                        } else {
                            mutasiTableContainer.style.display = 'block';
                            populateMutasiTable(data.mutasis);
                        }
                    } else {
                        showAlert('Gagal memuat data mutasi.', 'error');
                        emptyMutasi.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    loadingMutasi.style.display = 'none';
                    showAlert('Terjadi kesalahan saat memuat data.', 'error');
                    emptyMutasi.style.display = 'block';
                });
            }

            // Populate mutasi table
            function populateMutasiTable(mutasis) {
                const tableBody = document.getElementById('mutasiTableBody');
                tableBody.innerHTML = '';

                mutasis.forEach(mutasi => {
                    const row = document.createElement('tr');
                    row.classList.add('mutasi-row');
                    
                    row.innerHTML = `
                        <td>
                            <strong>${formatDate(mutasi.TMT)}</strong>
                            ${mutasi.keterangan ? `<br><small class="text-muted">${mutasi.keterangan}</small>` : ''}
                        </td>
                        <td>
                            <strong>${mutasi.abk_turun.nama}</strong><br>
                            <small class="text-muted">NRP: ${mutasi.abk_turun.NRP}</small>
                        </td>
                        <td>
                            ${mutasi.abk_naik ? `
                                <strong>${mutasi.abk_naik.nama}</strong><br>
                                <small class="text-muted">NRP: ${mutasi.abk_naik.NRP}</small>
                            ` : '<span class="text-muted">-</span>'}
                        </td>
                        <td>
                            <strong>Dari:</strong> ${mutasi.jabatan_lama}<br>
                            <strong>Ke:</strong> ${mutasi.jabatan_baru || '-'}
                        </td>
                        <td>
                            <strong>Dari:</strong> ${mutasi.kapal_asal}<br>
                            <strong>Ke:</strong> ${mutasi.kapal_tujuan || '-'}
                        </td>
                        <td>
                            <div id="upload-section-${mutasi.id}">
                                ${createUploadSection(mutasi.id)}
                            </div>
                        </td>
                    `;

                    tableBody.appendChild(row);
                });

                // Initialize upload functionality
                initializeUploadFunctionality();
            }

            // Create upload section HTML
            function createUploadSection(mutasiId) {
                return `
                    <div class="upload-section">
                        <div class="upload-area" onclick="document.getElementById('file-${mutasiId}').click()">
                            <input type="file" id="file-${mutasiId}" class="file-input" accept=".pdf,.doc,.docx" data-mutasi-id="${mutasiId}">
                            <div class="upload-icon">
                                <i class="bi bi-cloud-upload"></i>
                            </div>
                            <div class="upload-text">Klik untuk upload dokumen</div>
                            <div class="upload-subtext">PDF, DOC, DOCX (Max: 5MB)</div>
                        </div>
                        <div id="file-preview-${mutasiId}"></div>
                        <div class="mt-2">
                            <textarea class="form-control" id="keterangan-${mutasiId}" placeholder="Keterangan tambahan (opsional)" rows="2"></textarea>
                        </div>
                    </div>
                `;
            }

            // Initialize upload functionality
            function initializeUploadFunctionality() {
                const fileInputs = document.querySelectorAll('.file-input');
                
                fileInputs.forEach(input => {
                    input.addEventListener('change', function() {
                        const mutasiId = this.dataset.mutasiId;
                        const file = this.files[0];
                        
                        if (file) {
                            showFilePreview(mutasiId, file);
                        }
                    });
                });

                // Drag and drop functionality
                const uploadAreas = document.querySelectorAll('.upload-area');
                uploadAreas.forEach(area => {
                    area.addEventListener('dragover', function(e) {
                        e.preventDefault();
                        this.classList.add('dragover');
                    });

                    area.addEventListener('dragleave', function(e) {
                        e.preventDefault();
                        this.classList.remove('dragover');
                    });

                    area.addEventListener('drop', function(e) {
                        e.preventDefault();
                        this.classList.remove('dragover');
                        
                        const files = e.dataTransfer.files;
                        if (files.length > 0) {
                            const mutasiId = this.querySelector('.file-input').dataset.mutasiId;
                            showFilePreview(mutasiId, files[0]);
                        }
                    });
                });
            }

            // Show file preview
            function showFilePreview(mutasiId, file) {
                const previewContainer = document.getElementById(`file-preview-${mutasiId}`);
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                
                previewContainer.innerHTML = `
                    <div class="file-preview">
                        <div class="file-icon">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <div class="file-info">
                            <div class="file-name">${file.name}</div>
                            <div class="file-size">${fileSize} MB</div>
                        </div>
                        <div class="file-actions">
                            <button type="button" class="btn-sm-custom btn-success-sm" onclick="uploadFile(${mutasiId})">
                                <i class="bi bi-upload"></i> Upload
                            </button>
                            <button type="button" class="btn-sm-custom btn-danger-sm" onclick="removeFile(${mutasiId})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
            }

            // Upload file function (global scope)
            window.uploadFile = function(mutasiId) {
                const fileInput = document.getElementById(`file-${mutasiId}`);
                const keteranganInput = document.getElementById(`keterangan-${mutasiId}`);
                const file = fileInput.files[0];

                if (!file) {
                    showAlert('Tidak ada file yang dipilih.', 'error');
                    return;
                }

                // Validate file size (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    showAlert('Ukuran file tidak boleh lebih dari 5MB.', 'error');
                    return;
                }

                // Validate file type
                const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                if (!allowedTypes.includes(file.type)) {
                    showAlert('Format file harus PDF, DOC, atau DOCX.', 'error');
                    return;
                }

                const formData = new FormData();
                formData.append('id_mutasi', mutasiId);
                formData.append('file', file);
                formData.append('keterangan_puk', keteranganInput.value);

                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // Show loading state
                const uploadSection = document.getElementById(`upload-section-${mutasiId}`);
                const originalContent = uploadSection.innerHTML;
                uploadSection.innerHTML = `
                    <div class="loading-overlay">
                        <div class="spinner"></div>
                    </div>
                `;

                fetch('{{ route("puk.upload-sertijab") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('Dokumen berhasil diunggah!', 'success');
                        // Update upload section to show success state
                        uploadSection.innerHTML = `
                            <div class="upload-section">
                                <div class="alert-custom alert-success">
                                    <i class="bi bi-check-circle"></i>
                                    Dokumen berhasil diunggah
                                </div>
                                <div class="file-preview">
                                    <div class="file-icon">
                                        <i class="bi bi-file-earmark-check"></i>
                                    </div>
                                    <div class="file-info">
                                        <div class="file-name">${file.name}</div>
                                        <div class="file-size">Status: <span class="status-badge status-uploaded">Terupload</span></div>
                                    </div>
                                    <div class="file-actions">
                                        <button type="button" class="btn-sm-custom btn-danger-sm" onclick="deleteSertijab(${data.sertijab.id_sertijab}, ${mutasiId})">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                    } else {
                        showAlert(data.message, 'error');
                        uploadSection.innerHTML = originalContent;
                        initializeUploadFunctionality();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('Terjadi kesalahan saat mengunggah file.', 'error');
                    uploadSection.innerHTML = originalContent;
                    initializeUploadFunctionality();
                });
            };

            // Remove file function (global scope)
            window.removeFile = function(mutasiId) {
                const fileInput = document.getElementById(`file-${mutasiId}`);
                const previewContainer = document.getElementById(`file-preview-${mutasiId}`);
                
                fileInput.value = '';
                previewContainer.innerHTML = '';
            };

            // Delete sertijab function (global scope)
            window.deleteSertijab = function(sertijabId, mutasiId) {
                if (!confirm('Apakah Anda yakin ingin menghapus dokumen ini?')) {
                    return;
                }

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch('{{ route("puk.delete-sertijab") }}', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        id_sertijab: sertijabId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('Dokumen berhasil dihapus!', 'success');
                        // Reset upload section
                        const uploadSection = document.getElementById(`upload-section-${mutasiId}`);
                        uploadSection.innerHTML = createUploadSection(mutasiId);
                        initializeUploadFunctionality();
                    } else {
                        showAlert(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('Terjadi kesalahan saat menghapus file.', 'error');
                });
            };

            // Show alert function
            function showAlert(message, type) {
                const alertHtml = `
                    <div class="alert-custom alert-${type === 'error' ? 'error' : 'success'}">
                        <i class="bi bi-${type === 'error' ? 'exclamation-triangle' : 'check-circle'}"></i>
                        ${message}
                    </div>
                `;

                alertContainer.innerHTML = alertHtml;

                // Auto hide after 5 seconds
                setTimeout(() => {
                    alertContainer.innerHTML = '';
                }, 5000);
            }

            // Format date function
            function formatDate(dateString) {
                const date = new Date(dateString);
                return date.toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
            }
        });
    </script>
</body>
</html>