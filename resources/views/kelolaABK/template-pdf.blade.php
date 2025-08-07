<!DOCTYPE html>
<html>
<head>
    <title>Template Import {{ $type === 'abk_mutasi_template' ? 'ABK & Mutasi' : 'ABK' }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; }
        .section { margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f5f5f5; font-weight: bold; }
        .note { background-color: #fff3cd; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Template Import Data {{ $type === 'abk_mutasi_template' ? 'ABK & Mutasi' : 'ABK' }}</h2>
        <p>PELNI - Sistem Serah Terima Jabatan</p>
    </div>

    @if($type === 'abk_mutasi_template')
    <div class="section">
        <h3>Format Template ABK</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama ABK</th>
                    <th>Jabatan Tetap</th>
                    <th>Status ABK</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>12345</td>
                    <td>John Doe</td>
                    <td>Nahkoda</td>
                    <td>Organik</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <h3>Format Template Mutasi</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>nama_kapal</th>
                    <th>id_abk_naik</th>
                    <th>id_jabatan_mutasi</th>
                    <th>nama_mutasi</th>
                    <th>jenis_mutasi</th>
                    <th>TMT</th>
                    <th>TAT</th>
                    <th>catatan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>KM SABUK NUSANTARA 35</td>
                    <td>12345</td>
                    <td>1</td>
                    <td>Nahkoda</td>
                    <td>Sementara</td>
                    <td>09/11/2025</td>
                    <td>09/05/2026</td>
                    <td>Mutasi rutin</td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif

    <div class="section">
        <div class="note">
            <strong>Catatan Penting:</strong>
            <ul>
                <li>Download template Excel untuk format yang lebih lengkap</li>
                <li>Format tanggal: dd/mm/yyyy (contoh: 09/11/2025)</li>
                <li>NRP harus unik dan berisi angka saja</li>
                <li>Pastikan data kapal dan jabatan sudah ada di sistem</li>
                <li>id_abk_naik dan id_abk_turun harus sesuai dengan data ABK di database</li>
                <li>id_jabatan_mutasi mengacu pada ID jabatan di database</li>
                <li>TMT wajib diisi, TAT opsional untuk mutasi definitif</li>
                <li>Pastikan ABK sudah terdaftar sebelum import mutasi</li>
            </ul>
        </div>
    </div>

    <div class="section">
        <h3>Langkah-Langkah Import:</h3>
        <ol>
            <li>Download template Excel dari sistem</li>
            <li>Isi data sesuai format yang telah ditentukan</li>
            <li>Simpan file dalam format Excel (.xlsx atau .xls)</li>
            <li>Upload melalui halaman Export & Import</li>
            <li>Pilih jenis import (ABK atau Mutasi)</li>
            <li>Klik tombol Import Data</li>
        </ol>
    </div>
</body>
</html>