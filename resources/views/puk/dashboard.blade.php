{{-- filepath: c:\laragon\www\SertijabPelni\resources\views\puk\dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PUK Dashboard - PELNI Sertijab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
        }
        
        .construction-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .construction-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            padding: 50px 40px;
            text-align: center;
            max-width: 600px;
            width: 100%;
        }
        
        .construction-icon {
            font-size: 4rem;
            color: #10b981;
            margin-bottom: 30px;
            animation: bounce 2s infinite;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }
        
        .construction-title {
            font-family: 'Poppins', sans-serif;
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 20px;
        }
        
        .construction-subtitle {
            color: #6b7280;
            font-size: 1.1rem;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        
        .feature-list {
            text-align: left;
            margin: 30px 0;
            padding: 0;
            list-style: none;
        }
        
        .feature-list li {
            padding: 10px 0;
            color: #4a5568;
            position: relative;
            padding-left: 30px;
        }
        
        .feature-list li::before {
            content: '🚧';
            position: absolute;
            left: 0;
        }
        
        .btn-back {
            background: linear-gradient(135deg, #2A3F8E 0%, #3b82f6 100%);
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            color: white;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(42, 63, 142, 0.4);
            color: white;
        }
    </style>
</head>
<body>
    <div class="construction-container">
        <div class="construction-card">
            <div class="construction-icon">
                <i class="bi bi-tools"></i>
            </div>
            
            <h1 class="construction-title">PUK Dashboard</h1>
            <p class="construction-subtitle">
                Halaman Dashboard PUK sedang dalam tahap pengembangan. 
                Sistem ini akan menyediakan interface khusus untuk Pejabat Urusan Kepegawaian.
            </p>
            
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <h5 style="color: #374151; margin-bottom: 20px;">Fitur yang akan tersedia:</h5>
                    <ul class="feature-list">
                        <li>Dashboard ringkas dengan statistik dokumen</li>
                        <li>Form submit dokumen serah terima jabatan</li>
                        <li>Upload dan manajemen file dokumen</li>
                        <li>Tracking real-time status verifikasi</li>
                        <li>Riwayat semua pengajuan dokumen</li>
                        <li>Notifikasi otomatis status dokumen</li>
                        <li>Export laporan untuk keperluan administrasi</li>
                    </ul>
                </div>
            </div>
            
            <div style="margin-top: 40px;">
                <a href="{{ route('role.selection') }}" class="btn-back">
                    <i class="bi bi-arrow-left"></i>
                    Kembali ke Pilihan Role
                </a>
            </div>
            
            <div style="margin-top: 30px; padding-top: 30px; border-top: 1px solid #e5e7eb;">
                <small style="color: #9ca3af;">
                    <i class="bi bi-info-circle me-1"></i>
                    Untuk sementara, gunakan role Administrator untuk mengakses sistem penuh.
                </small>
            </div>
        </div>
    </div>
</body>
</html>