@extends('admin.partials.layout')

@section('title', 'Import Data Peserta - LAN Pusjar SKMP')
@section('page-title', 'Import Data Peserta')

@section('styles')
    <style>
        :root {
            --primary-color: #1a3a6c;
            --secondary-color: #2c5282;
            --dark-color: #1e293b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #3b82f6;
        }

        .import-section {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 2rem;
            margin-bottom: 2rem;
            animation: fadeIn 0.8s ease-out;
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e2e8f0;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-color);
        }

        .section-title i {
            color: var(--primary-color);
            background: rgba(26, 58, 108, 0.1);
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .import-form {
            max-width: 500px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--dark-color);
            font-size: 0.95rem;
        }

        .form-label i {
            color: var(--primary-color);
            margin-right: 0.5rem;
        }

        .file-upload-wrapper {
            position: relative;
            border: 2px dashed #cbd5e1;
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            background: #f8fafc;
        }

        .file-upload-wrapper:hover {
            border-color: var(--primary-color);
            background: rgba(26, 58, 108, 0.02);
        }

        .file-upload-wrapper.dragover {
            border-color: var(--success-color);
            background: rgba(16, 185, 129, 0.05);
        }

        .file-upload-icon {
            font-size: 3rem;
            color: #94a3b8;
            margin-bottom: 1rem;
            transition: color 0.3s ease;
        }

        .file-upload-wrapper:hover .file-upload-icon {
            color: var(--primary-color);
        }

        .file-upload-text {
            margin-bottom: 1rem;
            color: #475569;
        }

        .file-upload-text strong {
            color: var(--primary-color);
        }

        .file-input {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            opacity: 0;
            cursor: pointer;
        }

        .file-name {
            margin-top: 1rem;
            font-size: 0.9rem;
            color: var(--success-color);
            font-weight: 500;
        }

        .btn-import {
            background: linear-gradient(135deg, var(--info-color), #60a5fa);
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
            width: 100%;
            margin-top: 1rem;
        }

        .btn-import:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
            background: linear-gradient(135deg, #2563eb, #3b82f6);
        }

        .btn-import:active {
            transform: translateY(0);
        }

        .btn-import:disabled {
            background: linear-gradient(135deg, #94a3b8, #cbd5e1);
            cursor: not-allowed;
            transform: none;
        }

        .import-info {
            margin-top: 2rem;
            padding: 1.5rem;
            background: #f0f9ff;
            border-radius: 8px;
            border-left: 4px solid var(--primary-color);
        }

        .import-info-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .import-info-header i {
            color: var(--primary-color);
            font-size: 1.25rem;
        }

        .import-info-header h4 {
            margin: 0;
            color: var(--dark-color);
            font-size: 1.1rem;
        }

        .import-info-content {
            color: #475569;
            font-size: 0.95rem;
        }

        .import-info-content ul {
            margin: 0.5rem 0 1rem 1.5rem;
            padding: 0;
        }

        .import-info-content li {
            margin-bottom: 0.5rem;
        }

        .download-template {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, var(--success-color), #34d399);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .download-template:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
        }

        .template-preview {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1.5rem;
            overflow-x: auto;
        }

        .template-preview table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
        }

        .template-preview th {
            background: #f1f5f9;
            color: var(--dark-color);
            font-weight: 600;
            padding: 0.75rem;
            border: 1px solid #e2e8f0;
            text-align: left;
        }

        .template-preview td {
            padding: 0.75rem;
            border: 1px solid #e2e8f0;
            color: #64748b;
        }

        .template-preview .required {
            color: var(--danger-color);
            font-weight: 600;
        }

        .template-preview .optional {
            color: var(--warning-color);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .import-section {
                padding: 1.5rem;
            }

            .section-title {
                font-size: 1.25rem;
            }

            .section-title i {
                width: 35px;
                height: 35px;
            }

            .template-preview {
                overflow-x: scroll;
            }
        }

        @media (max-width: 576px) {
            .import-section {
                padding: 1.25rem 1rem;
            }

            .file-upload-wrapper {
                padding: 1.5rem 1rem;
            }

            .template-preview {
                font-size: 0.8rem;
            }
        }

        .alert {
            border-radius: 8px;
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
            animation: fadeIn 0.5s ease-out;
        }

        .alert-success {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #065f46;
            border-left: 4px solid #10b981;
        }

        .alert-danger {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #7f1d1d;
            border-left: 4px solid #ef4444;
        }

        .alert-warning {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #92400e;
            border-left: 4px solid #f59e0b;
        }

        .alert-info {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #1e40af;
            border-left: 4px solid #3b82f6;
        }

        .alert ul {
            margin-bottom: 0;
        }

        .alert li {
            margin-bottom: 0.25rem;
        }

        .badge {
            padding: 0.4em 0.8em;
            font-weight: 500;
        }
    </style>
@endsection

@section('content')
    {{-- Alert untuk pesan sukses/error --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {!! session('success') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Tampilkan error messages --}}
    @if(session('error_messages'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-times-circle me-2"></i>
            <strong>Detail Error:</strong>
            <ul class="mb-0 mt-2">
                @foreach(session('error_messages') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Tampilkan statistik --}}
    @if(session('stats'))
        @php $stats = session('stats'); @endphp
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-chart-bar me-2"></i>
            <strong>Statistik Import:</strong>
            <div class="mt-2">
                <span class="badge bg-success me-2">✅ Sukses: {{ $stats['success'] ?? 0 }}</span>
                <span class="badge bg-warning me-2">⚠️ Duplikat: {{ $stats['duplicate'] ?? 0 }}</span>
                <span class="badge bg-danger">❌ Gagal: {{ $stats['failed'] ?? 0 }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="import-section">
        <div class="section-header">
            <div class="section-title">
                <i class="fas fa-file-import"></i>
                <span>Import Data Peserta</span>
            </div>
        </div>

        <form action="{{ route('admin.import.peserta.process') }}" method="POST" enctype="multipart/form-data"
            class="import-form" id="importForm">
            @csrf

            <div class="form-group">
                <div class="file-upload-wrapper" id="fileUploadWrapper">
                    <div class="file-upload-icon">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <div class="file-upload-text">
                        <strong>Klik untuk memilih file</strong> atau drag & drop file Excel di sini
                    </div>
                    <div class="file-upload-text" style="font-size: 0.9rem; color: #64748b;">
                        Format yang didukung: .xlsx, .xls
                        <br>
                        Maksimal ukuran file: 10MB
                    </div>
                    <input type="file" name="file" id="fileInput" class="file-input" accept=".xlsx,.xls" required>
                    <div id="fileName" class="file-name"></div>
                </div>
            </div>

            <button type="submit" class="btn-import" id="importBtn">
                <i class="fas fa-upload"></i>
                Import Data
            </button>
        </form>

        <div class="import-info">
            <div class="import-info-header">
                <i class="fas fa-info-circle"></i>
                <h4>Panduan Format Excel</h4>
            </div>
            <div class="import-info-content">
                <p><strong>Perhatian:</strong> Pastikan file Excel mengikuti format berikut agar proses import berhasil:</p>

                <ul>
                    <li><span class="required">Kolom wajib</span> harus diisi sesuai format</li>
                    <li><span class="optional">Kolom opsional</span> boleh dikosongkan</li>
                    <li>Header kolom harus berada di baris pertama</li>
                    <li>Format tanggal: YYYY-MM-DD (contoh: 2024-01-15)</li>
                    <li>Gunakan <strong>Template Excel</strong> yang telah disediakan untuk memastikan format yang benar
                    </li>
                    <li>NIP/NRP akan diformat secara otomatis sebagai text dalam sistem</li>
                </ul>

                <a href="{{ route('admin.import.peserta.template') }}" class="download-template">
                    <i class="fas fa-download"></i>
                    Download Template Excel
                </a>

                <div class="template-preview">
                    <table>
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>KOLOM</th>
                                <th>FORMAT</th>
                                <th>CONTOH</th>
                                <th>STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>JENIS_PELATIHAN</td>
                                <td>Text (LATSAR, PKA, PKN TK II, PKP, dll)</td>
                                <td>LATSAR</td>
                                <td><span class="required">Wajib</span></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>ANGKATAN</td>
                                <td>Text</td>
                                <td>Angkatan I</td>
                                <td><span class="required">Wajib</span></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>TAHUN_ANGKATAN</td>
                                <td>Angka</td>
                                <td>2026</td>
                                <td><span class="required">Wajib</span></td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>NIP_NRP</td>
                                <td>Angka/Teks (18-21 digit)</td>
                                <td>198001012011011001</td>
                                <td><span class="required">Wajib</span></td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>NAMA_LENGKAP</td>
                                <td>Text</td>
                                <td>Budi Santoso, S.Kom</td>
                                <td><span class="required">Wajib</span></td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>JENIS_KELAMIN</td>
                                <td>Text (Laki-laki/Perempuan)</td>
                                <td>Laki-laki</td>
                                <td><span class="required">Wajib</span></td>
                            </tr>
                            <tr>
                                <td>7</td>
                                <td>NAMA_PANGGILAN</td>
                                <td>Text</td>
                                <td>Budi</td>
                                <td><span class="optional">Opsional</span></td>
                            </tr>
                            <tr>
                                <td>8</td>
                                <td>AGAMA</td>
                                <td>Text (Islam/Kristen/Kristen Protestan/Katolik/Hindu/Buddha/Konghucu)</td>
                                <td>Islam</td>
                                <td><span class="optional">Opsional</span></td>
                            </tr>
                            <tr>
                                <td>9</td>
                                <td>TEMPAT_LAHIR</td>
                                <td>Text</td>
                                <td>Jakarta</td>
                                <td><span class="optional">Opsional</span></td>
                            </tr>
                            <tr>
                                <td>10</td>
                                <td>TANGGAL_LAHIR</td>
                                <td>Date (DD-MM-YYYY)</td>
                                <td>01-01-1980</td>
                                <td><span class="optional">Opsional</span></td>
                            </tr>
                            <tr>
                                <td>11</td>
                                <td>ALAMAT_RUMAH</td>
                                <td>Text</td>
                                <td>Jl. Merdeka No. 123</td>
                                <td><span class="optional">Opsional</span></td>
                            </tr>
                            <tr>
                                <td>12</td>
                                <td>EMAIL_PRIBADI</td>
                                <td>Email</td>
                                <td>budi@email.com</td>
                                <td><span class="optional">Opsional</span></td>
                            </tr>
                            <tr>
                                <td>13</td>
                                <td>NOMOR_HP</td>
                                <td>Angka/Teks (10-15 digit)</td>
                                <td>081234567890</td>
                                <td><span class="optional">Opsional</span></td>
                            </tr>
                            <tr>
                                <td>14</td>
                                <td>PENDIDIKAN_TERAKHIR</td>
                                <td>Text (S1/S2/S3/D4/D3/D2/D1/SMA)</td>
                                <td>S1</td>
                                <td><span class="optional">Opsional</span></td>
                            </tr>
                            <tr>
                                <td>15</td>
                                <td>BIDANG_STUDI</td>
                                <td>Text</td>
                                <td>Teknik Informatika</td>
                                <td><span class="optional">Opsional</span></td>
                            </tr>
                            <tr>
                                <td>16</td>
                                <td>BIDANG_KEAHLIAN</td>
                                <td>Text</td>
                                <td>Programmer</td>
                                <td><span class="optional">Opsional</span></td>
                            </tr>
                            <tr>
                                <td>17</td>
                                <td>STATUS_PERKAWINAN</td>
                                <td>Text (Menikah/Belum Menikah)</td>
                                <td>Menikah</td>
                                <td><span class="optional">Opsional</span></td>
                            </tr>
                            <tr>
                                <td>18</td>
                                <td>NAMA_PASANGAN</td>
                                <td>Text</td>
                                <td>Siti Nurhaliza</td>
                                <td><span class="optional">Opsional</span></td>
                            </tr>
                            <tr>
                                <td>19</td>
                                <td>OLAHRAGA_HOBI</td>
                                <td>Text</td>
                                <td>Sepak Bola</td>
                                <td><span class="optional">Opsional</span></td>
                            </tr>
                            <tr>
                                <td>20</td>
                                <td>PEROKOK</td>
                                <td>Text (Ya/Tidak)</td>
                                <td>Tidak</td>
                                <td><span class="optional">Opsional</span></td>
                            </tr>
                            <tr>
                                <td>21</td>
                                <td>UKURAN_KAOS</td>
                                <td>Text (XS/S/M/L/XL/XXL)</td>
                                <td>L</td>
                                <td><span class="optional">Opsional</span></td>
                            </tr>
                            <tr>
                                <td>22</td>
                                <td>UKURAN_CELANA</td>
                                <td>Text (XS/S/M/L/XL/XXL)</td>
                                <td>L</td>
                                <td><span class="optional">Opsional</span></td>
                            </tr>
                            <tr>
                                <td>23</td>
                                <td>UKURAN_TRAINING</td>
                                <td>Text (XS/S/M/L/XL/XXL)</td>
                                <td>L</td>
                                <td><span class="optional">Opsional</span></td>
                            </tr>
                            <tr>
                                <td>24</td>
                                <td>KONDISI_PESERTA</td>
                                <td>Text (Sehat/Sakit khusus)</td>
                                <td>Sehat</td>
                                <td><span class="optional">Opsional</span></td>
                            </tr>
                            <tr>
                                <td>25</td>
                                <td>ASAL_INSTANSI</td>
                                <td>Text</td>
                                <td>Kementerian Dalam Negeri</td>
                                <td><span class="optional">Opsional</span></td>
                            </tr>
                            <tr>
                                <td>26</td>
                                <td>UNIT_KERJA</td>
                                <td>Text</td>
                                <td>Biro IT</td>
                                <td><span class="optional">Opsional</span></td>
                            </tr>
                            <tr>
                                <td>27</td>
                                <td>PROVINSI</td>
                                <td>Text</td>
                                <td>DKI Jakarta</td>
                                <td><span class="optional">Opsional</span></td>
                            </tr>
                            <tr>
                                <td>28</td>
                                <td>KABUPATEN_KOTA</td>
                                <td>Text</td>
                                <td>Jakarta Pusat</td>
                                <td><span class="optional">Opsional</span></td>
                            </tr>
                            <tr>
                                <td>29</td>
                                <td>ALAMAT_KANTOR</td>
                                <td>Text</td>
                                <td>Jl. Medan Merdeka No. 5</td>
                                <td><span class="optional">Opsional</span></td>
                            </tr>
                            <tr>
                                <td>30</td>
                                <td>NOMOR_TELEPON_KANTOR</td>
                                <td>Text</td>
                                <td>021-12345678</td>
                                <td><span class="optional">Opsional</span></td>
                            </tr>
                            <tr>
                                <td>31</td>
                                <td>EMAIL_KANTOR</td>
                                <td>Email</td>
                                <td>budi.kantor@kemendagri.go.id</td>
                                <td><span class="optional">Opsional</span></td>
                            </tr>
                            <tr>
                                <td>32</td>
                                <td>JABATAN</td>
                                <td>Text</td>
                                <td>Kepala Bidang IT</td>
                                <td><span class="optional">Opsional</span></td>
                            </tr>
                            <tr>
                                <td>33</td>
                                <td>PANGKAT</td>
                                <td>Text</td>
                                <td>Pembina</td>
                                <td><span class="optional">Opsional</span></td>
                            </tr>
                            <tr>
                                <td>34</td>
                                <td>GOLONGAN_RUANG</td>
                                <td>Text</td>
                                <td>III/a</td>
                                <td><span class="optional">Opsional</span></td>
                            </tr>
                            <tr>
                                <td>35</td>
                                <td>ESELON</td>
                                <td>Text</td>
                                <td>III.a</td>
                                <td><span class="optional">Opsional</span></td>
                            </tr>
                            <tr>
                                <td>36</td>
                                <td>TANGGAL_SK_JABATAN</td>
                                <td>Date (DD-MM-YYYY)</td>
                                <td>01-05-2024</td>
                                <td><span class="optional">Opsional</span></td>
                            </tr>
                            <tr>
                                <td>37</td>
                                <td>NOMOR_SK_CPNS</td>
                                <td>Text</td>
                                <td>123/CPNS/2011</td>
                                <td><span class="optional">Opsional</span></td>
                            </tr>
                            <tr>
                                <td>38</td>
                                <td>NOMOR_SK_TERAKHIR</td>
                                <td>Text</td>
                                <td>456/SK/2024</td>
                                <td><span class="optional">Opsional</span></td>
                            </tr>
                            <tr>
                                <td>39</td>
                                <td>TANGGAL_SK_CPNS</td>
                                <td>Date Date (DD-MM-YYYY)</td>
                                <td>01-06-2011</td>
                                <td><span class="optional">Opsional</span></td>
                            </tr>
                            <tr>
                                <td>40</td>
                                <td>TAHUN_LULUS_PKP_PIM_IV</td>
                                <td>Angka</td>
                                <td>2023</td>
                                <td><span class="optional">Opsional</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const fileInput = document.getElementById('fileInput');
            const fileName = document.getElementById('fileName');
            const fileUploadWrapper = document.getElementById('fileUploadWrapper');
            const importBtn = document.getElementById('importBtn');
            const importForm = document.getElementById('importForm');

            // Validasi ukuran file sebelum upload
            fileInput.addEventListener('change', function (e) {
                if (e.target.files.length > 0) {
                    const file = e.target.files[0];
                    const fileSize = file.size / 1024 / 1024; // dalam MB

                    // Validasi ukuran
                    if (fileSize > 10) {
                        alert('Ukuran file terlalu besar! Maksimal 10MB');
                        e.target.value = '';
                        fileName.textContent = '';
                        return;
                    }

                    // Validasi ekstensi
                    const allowedExtensions = ['xlsx', 'xls'];
                    const fileExtension = file.name.split('.').pop().toLowerCase();

                    if (!allowedExtensions.includes(fileExtension)) {
                        alert('Format file tidak didukung! Gunakan .xlsx atau .xls');
                        e.target.value = '';
                        fileName.textContent = '';
                        return;
                    }

                    fileName.textContent = `File terpilih: ${file.name} (${fileSize.toFixed(2)} MB)`;
                    fileName.style.color = '#10b981';
                }
            });

            // Handle file selection
            fileInput.addEventListener('change', function (e) {
                if (e.target.files.length > 0) {
                    const file = e.target.files[0];
                    fileName.textContent = `File terpilih: ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
                }
            });

            // Handle drag and drop
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                fileUploadWrapper.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                fileUploadWrapper.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                fileUploadWrapper.addEventListener(eventName, unhighlight, false);
            });

            function highlight() {
                fileUploadWrapper.classList.add('dragover');
            }

            function unhighlight() {
                fileUploadWrapper.classList.remove('dragover');
            }

            // Handle drop
            fileUploadWrapper.addEventListener('drop', function (e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                fileInput.files = files;

                if (files.length > 0) {
                    const file = files[0];
                    fileName.textContent = `File terpilih: ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
                }
            });

            // Handle form submission
            if (importForm) {
                importForm.addEventListener('submit', function () {
                    importBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
                    importBtn.disabled = true;

                    // Re-enable after 10 seconds if submission fails
                    setTimeout(() => {
                        importBtn.innerHTML = '<i class="fas fa-upload"></i> Import Data';
                        importBtn.disabled = false;
                    }, 10000);
                });
            }
        });
    </script>
@endsection