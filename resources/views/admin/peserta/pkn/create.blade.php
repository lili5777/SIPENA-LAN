@extends('admin.partials.layout')

@section('title', 'Tambah Peserta PKN TK II - Sistem Pelatihan')

@section('content')
    <!-- Hero Section -->
    <section class="form-hero" id="home">
        <div class="container">
            <div class="form-hero-content animate">
                <h1 class="form-hero-title">Tambah Peserta PKN TK II</h1>
                <p class="form-hero-text">Daftarkan peserta baru untuk mengikuti program pelatihan PKN TK II. Isi formulir
                    dengan data yang lengkap dan valid.</p>
                <div class="progress-indicator">
                    <div class="progress-step active" id="step1">
                        <div class="step-number">1</div>
                        <div class="step-label">Pilih Angkatan</div>
                    </div>
                    <div class="progress-step" id="step2">
                        <div class="step-number">2</div>
                        <div class="step-label">Data Peserta</div>
                    </div>
                    <div class="progress-step" id="step3">
                        <div class="step-number">3</div>
                        <div class="step-label">Kepegawaian & Mentor</div>
                    </div>
                    <div class="progress-step" id="step4">
                        <div class="step-number">4</div>
                        <div class="step-label">Dokumen</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Form Section -->
    <section class="form-section" id="form-section">
        <div class="container">
            <div class="form-wrapper animate">
                <form action="" method="POST" enctype="multipart/form-data"
                    id="tambahPesertaForm">
                    @csrf
                    <input type="hidden" name="id_jenis_pelatihan" value="1">

                    <!-- Step 1: Pilih Angkatan -->
                    <div class="form-step active" id="step1-content">
                        <div class="step-header">
                            <h2 class="step-title">Pilih Angkatan Pelatihan</h2>
                            <p class="step-description">Silakan pilih angkatan untuk pelatihan PKN TK II</p>
                            <div class="selected-training">
                                <i class="fas fa-check-circle"></i>
                                <span id="selected-training-name">PKN TK II</span>
                            </div>
                        </div>

                        <div class="angkatan-container">
                            <div class="form-group">
                                <label for="id_angkatan" class="form-label">Angkatan *</label>
                                <select name="id_angkatan" id="id_angkatan"
                                    class="form-select @error('id_angkatan') error @enderror" required>
                                    <option value="">Pilih Angkatan</option>
                                    @foreach($angkatanList as $angkatan)
                                        <option value="{{ $angkatan->id }}" data-nama="{{ $angkatan->nama_angkatan }}"
                                            data-tahun="{{ $angkatan->tahun }}" data-kuota="{{ $angkatan->kuota }}"
                                            data-status="{{ $angkatan->status_angkatan }}" {{ old('id_angkatan') == $angkatan->id ? 'selected' : '' }}>
                                            {{ $angkatan->nama_angkatan }} ({{ $angkatan->tahun }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_angkatan')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="angkatan-info" id="angkatan-info" style="display: none;">
                                <div class="info-card">
                                    <h4><i class="fas fa-info-circle"></i> Informasi Angkatan</h4>
                                    <div class="info-details">
                                        <div class="info-item">
                                            <span class="info-label">Nama Angkatan:</span>
                                            <span class="info-value" id="info-nama-angkatan"></span>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label">Tahun:</span>
                                            <span class="info-value" id="info-tahun-angkatan"></span>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label">Kuota:</span>
                                            <span class="info-value" id="info-kuota-angkatan"></span>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label">Status:</span>
                                            <span class="info-badge" id="info-status-angkatan"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="step-navigation">
                            <a href="{{ route('peserta.pkn-tk2') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                            </a>
                            <button type="button" class="btn btn-primary" id="next-to-step2" disabled>
                                Lanjutkan <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: Data Peserta -->
                    <div class="form-step" id="step2-content">
                        <div class="step-header">
                            <h2 class="step-title">Data Pribadi Peserta</h2>
                            <p class="step-description">Lengkapi data berikut dengan informasi yang valid</p>
                            <div class="selected-info">
                                <div class="info-badge">
                                    <i class="fas fa-book"></i> <span id="current-training-name">PKN TK II</span>
                                </div>
                                <div class="info-badge">
                                    <i class="fas fa-calendar-alt"></i> Angkatan: <span id="current-angkatan-name"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Data Pribadi -->
                        <div class="dynamic-form-container" id="dynamic-form-container">
                            <!-- Data Pribadi -->
                            <div class="form-section-header">
                                <i class="fas fa-user-tie"></i> Data Pribadi
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label required">Nama Lengkap (Berikut Gelar Pendidikan) *</label>
                                    <input type="text" name="nama_lengkap"
                                        class="form-input @error('nama_lengkap') error @enderror"
                                        value="{{ old('nama_lengkap') }}" required
                                        placeholder="Masukkan nama lengkap dengan gelar">
                                    @error('nama_lengkap')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label required">NIP/NRP *</label>
                                    <input type="text" name="nip_nrp" class="form-input @error('nip_nrp') error @enderror"
                                        value="{{ old('nip_nrp') }}" required placeholder="Masukkan NIP/NRP">
                                    @error('nip_nrp')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label required">Jenis Kelamin *</label>
                                    <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') error @enderror"
                                        required>
                                        <option value="">Pilih</option>
                                        <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>
                                            Laki-laki</option>
                                        <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>
                                            Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label required">Tempat Lahir *</label>
                                    <input type="text" name="tempat_lahir"
                                        class="form-input @error('tempat_lahir') error @enderror"
                                        value="{{ old('tempat_lahir') }}" required placeholder="Masukkan tempat lahir">
                                    @error('tempat_lahir')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label required">Tanggal Lahir *</label>
                                    <input type="date" name="tanggal_lahir"
                                        class="form-input @error('tanggal_lahir') error @enderror"
                                        value="{{ old('tanggal_lahir') }}" required>
                                    @error('tanggal_lahir')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label required">Alamat Rumah *</label>
                                <textarea name="alamat_rumah" class="form-textarea @error('alamat_rumah') error @enderror"
                                    required
                                    placeholder="Masukkan alamat lengkap rumah">{{ old('alamat_rumah') }}</textarea>
                                @error('alamat_rumah')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label required">Email Pribadi *</label>
                                    <input type="email" name="email_pribadi"
                                        class="form-input @error('email_pribadi') error @enderror"
                                        value="{{ old('email_pribadi') }}" required placeholder="email@contoh.com">
                                    @error('email_pribadi')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label required">Nomor HP/WhatsApp *</label>
                                    <input type="tel" name="nomor_hp" class="form-input @error('nomor_hp') error @enderror"
                                        value="{{ old('nomor_hp') }}" required placeholder="0812xxxxxxxx">
                                    @error('nomor_hp')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label required">Pendidikan Terakhir *</label>
                                    <select name="pendidikan_terakhir"
                                        class="form-select @error('pendidikan_terakhir') error @enderror" required>
                                        <option value="">Pilih</option>
                                        <option value="D3" {{ old('pendidikan_terakhir') == 'D3' ? 'selected' : '' }}>D3
                                        </option>
                                        <option value="D4" {{ old('pendidikan_terakhir') == 'D4' ? 'selected' : '' }}>D4
                                        </option>
                                        <option value="S1" {{ old('pendidikan_terakhir') == 'S1' ? 'selected' : '' }}>S1
                                        </option>
                                        <option value="S2" {{ old('pendidikan_terakhir') == 'S2' ? 'selected' : '' }}>S2
                                        </option>
                                        <option value="S3" {{ old('pendidikan_terakhir') == 'S3' ? 'selected' : '' }}>S3
                                        </option>
                                    </select>
                                    @error('pendidikan_terakhir')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label required">Bidang Studi Pendidikan Terakhir *</label>
                                    <input type="text" name="bidang_studi"
                                        class="form-input @error('bidang_studi') error @enderror"
                                        value="{{ old('bidang_studi') }}" required placeholder="Contoh: Ilmu Administrasi">
                                    @error('bidang_studi')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label required">Bidang Keahlian *</label>
                                    <input type="text" name="bidang_keahlian"
                                        class="form-input @error('bidang_keahlian') error @enderror"
                                        value="{{ old('bidang_keahlian') }}" required
                                        placeholder="Keahlian atau Kompetensi yang menonjol">
                                    @error('bidang_keahlian')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label required">Agama *</label>
                                    <select name="agama" class="form-select @error('agama') error @enderror" required>
                                        <option value="">Pilih</option>
                                        <option value="Islam" {{ old('agama') == 'Islam' ? 'selected' : '' }}>Islam</option>
                                        <option value="Kristen" {{ old('agama') == 'Kristen' ? 'selected' : '' }}>Kristen
                                        </option>
                                        <option value="Katolik" {{ old('agama') == 'Katolik' ? 'selected' : '' }}>Katolik
                                        </option>
                                        <option value="Hindu" {{ old('agama') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                        <option value="Buddha" {{ old('agama') == 'Buddha' ? 'selected' : '' }}>Buddha
                                        </option>
                                        <option value="Konghucu" {{ old('agama') == 'Konghucu' ? 'selected' : '' }}>Konghucu
                                        </option>
                                    </select>
                                    @error('agama')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label required">Status Perkawinan *</label>
                                    <select name="status_perkawinan"
                                        class="form-select @error('status_perkawinan') error @enderror" required>
                                        <option value="">Pilih</option>
                                        <option value="Belum Menikah" {{ old('status_perkawinan') == 'Belum Menikah' ? 'selected' : '' }}>Belum Menikah</option>
                                        <option value="Menikah" {{ old('status_perkawinan') == 'Menikah' ? 'selected' : '' }}>
                                            Menikah</option>
                                        <option value="Duda" {{ old('status_perkawinan') == 'Duda' ? 'selected' : '' }}>Duda
                                        </option>
                                        <option value="Janda" {{ old('status_perkawinan') == 'Janda' ? 'selected' : '' }}>
                                            Janda</option>
                                    </select>
                                    @error('status_perkawinan')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Nama Istri/Suami</label>
                                    <input type="text" name="nama_pasangan"
                                        class="form-input @error('nama_pasangan') error @enderror"
                                        value="{{ old('nama_pasangan') }}"
                                        placeholder="Masukkan nama pasangan (jika menikah)">
                                    @error('nama_pasangan')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label required">Olahraga Kegemaran *</label>
                                    <input type="text" name="olahraga_hobi"
                                        class="form-input @error('olahraga_hobi') error @enderror"
                                        value="{{ old('olahraga_hobi') }}" required placeholder="Contoh: Sepakbola, Renang">
                                    @error('olahraga_hobi')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label required">Apakah Anda merokok? *</label>
                                    <select name="perokok" class="form-select @error('perokok') error @enderror" required>
                                        <option value="">Pilih</option>
                                        <option value="Ya" {{ old('perokok') == 'Ya' ? 'selected' : '' }}>Ya</option>
                                        <option value="Tidak" {{ old('perokok') == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                                    </select>
                                    @error('perokok')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label required">Ukuran Kaos Olahraga/Celana Training *</label>
                                    <select name="ukuran_kaos" class="form-select @error('ukuran_kaos') error @enderror"
                                        required>
                                        <option value="">Pilih</option>
                                        <option value="S" {{ old('ukuran_kaos') == 'S' ? 'selected' : '' }}>S</option>
                                        <option value="M" {{ old('ukuran_kaos') == 'M' ? 'selected' : '' }}>M</option>
                                        <option value="L" {{ old('ukuran_kaos') == 'L' ? 'selected' : '' }}>L</option>
                                        <option value="XL" {{ old('ukuran_kaos') == 'XL' ? 'selected' : '' }}>XL</option>
                                        <option value="XXL" {{ old('ukuran_kaos') == 'XXL' ? 'selected' : '' }}>XXL</option>
                                        <option value="XXXL" {{ old('ukuran_kaos') == 'XXXL' ? 'selected' : '' }}>XXXL
                                        </option>
                                    </select>
                                    @error('ukuran_kaos')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="step-navigation">
                            <button type="button" class="btn btn-secondary" id="back-to-step1">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </button>
                            <button type="button" class="btn btn-primary" id="next-to-step3">
                                Lanjutkan <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 3: Kepegawaian & Mentor -->
                    <div class="form-step" id="step3-content">
                        <div class="step-header">
                            <h2 class="step-title">Data Kepegawaian & Mentor</h2>
                            <p class="step-description">Lengkapi informasi instansi dan penugasan mentor</p>
                            <div class="selected-info">
                                <div class="info-badge">
                                    <i class="fas fa-book"></i> <span id="current-training-name-2">PKN TK II</span>
                                </div>
                                <div class="info-badge">
                                    <i class="fas fa-calendar-alt"></i> Angkatan: <span id="current-angkatan-name-2"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Data Kepegawaian -->
                        <div class="dynamic-form-container" id="dynamic-form-container-2">
                            <!-- Data Kepegawaian -->
                            <div class="form-section-header">
                                <i class="fas fa-building"></i> Data Kepegawaian
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label required">Asal Instansi *</label>
                                    <input type="text" name="asal_instansi"
                                        class="form-input @error('asal_instansi') error @enderror"
                                        placeholder="Contoh: Lembaga Administrasi Negara" value="{{ old('asal_instansi') }}"
                                        required>
                                    @error('asal_instansi')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label required">Unit Kerja Peserta *</label>
                                    <input type="text" name="unit_kerja"
                                        class="form-input @error('unit_kerja') error @enderror"
                                        placeholder="Contoh: Sekretariat Daerah Kota Makassar"
                                        value="{{ old('unit_kerja') }}" required>
                                    @error('unit_kerja')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label required">Jabatan *</label>
                                    <input type="text" name="jabatan" class="form-input @error('jabatan') error @enderror"
                                        value="{{ old('jabatan') }}" required placeholder="Jabatan saat ini">
                                    @error('jabatan')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label required">Pangkat / Golongan Ruang *</label>
                                    <select name="golongan_ruang"
                                        class="form-select @error('golongan_ruang') error @enderror" required>
                                        <option value="">Pilih</option>
                                        <option value="Pembina Utama, IV/E" {{ old('golongan_ruang') == 'Pembina Utama, IV/E' ? 'selected' : '' }}>Pembina Utama, IV/E</option>
                                        <option value="Pembina Utama Madya, IV/D" {{ old('golongan_ruang') == 'Pembina Utama Madya, IV/D' ? 'selected' : '' }}>Pembina Utama Madya, IV/D</option>
                                        <option value="Pembina Utama Muda, IV/C" {{ old('golongan_ruang') == 'Pembina Utama Muda, IV/C' ? 'selected' : '' }}>Pembina Utama Muda, IV/C</option>
                                        <option value="Pembina Tingkat I, IV/B" {{ old('golongan_ruang') == 'Pembina Tingkat I, IV/B' ? 'selected' : '' }}>Pembina Tingkat I, IV/B</option>
                                        <option value="Pembina, IV/A" {{ old('golongan_ruang') == 'Pembina, IV/A' ? 'selected' : '' }}>Pembina, IV/A</option>
                                        <option value="Penata Tingkat I, III/D" {{ old('golongan_ruang') == 'Penata Tingkat I, III/D' ? 'selected' : '' }}>Penata Tingkat I, III/D</option>
                                        <option value="Penata, III/C" {{ old('golongan_ruang') == 'Penata, III/C' ? 'selected' : '' }}>Penata, III/C</option>
                                        <option value="Penata Muda Tingkat I, III/B" {{ old('golongan_ruang') == 'Penata Muda Tingkat I, III/B' ? 'selected' : '' }}>Penata Muda Tingkat I, III/B</option>
                                        <option value="Penata Muda, III/A" {{ old('golongan_ruang') == 'Penata Muda, III/A' ? 'selected' : '' }}>Penata Muda, III/A</option>
                                    </select>
                                    @error('golongan_ruang')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label required">Eselon *</label>
                                    <select name="eselon" class="form-select @error('eselon') error @enderror" required>
                                        <option value="">Pilih</option>
                                        <option value="II" {{ old('eselon') == 'II' ? 'selected' : '' }}>II</option>
                                        <option value="III/Pejabat Fungsional" {{ old('eselon') == 'III/Pejabat Fungsional' ? 'selected' : '' }}>III/Pejabat Fungsional</option>
                                    </select>
                                    @error('eselon')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label required">Provinsi (Kantor/Tempat Tugas) *</label>
                                    <select name="id_provinsi" id="id_provinsi"
                                        class="form-select @error('id_provinsi') error @enderror" required>
                                        <option value="">Pilih Provinsi</option>
                                        @foreach($provinsiList as $provinsi)
                                            <option value="{{ $provinsi->id }}" {{ old('id_provinsi') == $provinsi->id ? 'selected' : '' }}>
                                                {{ $provinsi->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_provinsi')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label required">Kabupaten (Lokasi Kantor/Tempat Tugas) *</label>
                                    <select name="id_kabupaten_kota" id="id_kabupaten_kota"
                                        class="form-select @error('id_kabupaten_kota') error @enderror" required disabled>
                                        <option value="">Pilih Kabupaten (Pilih Provinsi Dahulu)</option>
                                    </select>
                                    @error('id_kabupaten_kota')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label required">Alamat Kantor *</label>
                                <textarea name="alamat_kantor" class="form-textarea @error('alamat_kantor') error @enderror"
                                    required
                                    placeholder="Masukkan alamat lengkap kantor">{{ old('alamat_kantor') }}</textarea>
                                @error('alamat_kantor')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Nomor Telepon Kantor</label>
                                    <input type="tel" name="nomor_telepon_kantor"
                                        class="form-input @error('nomor_telepon_kantor') error @enderror"
                                        value="{{ old('nomor_telepon_kantor') }}" placeholder="(021) xxxxxx">
                                    @error('nomor_telepon_kantor')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Email Kantor</label>
                                    <input type="email" name="email_kantor"
                                        class="form-input @error('email_kantor') error @enderror"
                                        value="{{ old('email_kantor') }}" placeholder="email@instansi.go.id">
                                    @error('email_kantor')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <!-- Data Mentor -->
                            <div class="form-section-header">
                                <i class="fas fa-user-graduate"></i> Data Mentor
                            </div>

                            <div class="form-group">
                                <label class="form-label required">Apakah sudah ada penunjukan Mentor? *</label>
                                <select name="sudah_ada_mentor" id="sudah_ada_mentor"
                                    class="form-select @error('sudah_ada_mentor') error @enderror" required>
                                    <option value="">Pilih</option>
                                    <option value="Ya" {{ old('sudah_ada_mentor') == 'Ya' ? 'selected' : '' }}>Ya</option>
                                    <option value="Tidak" {{ old('sudah_ada_mentor') == 'Tidak' ? 'selected' : '' }}>Tidak
                                    </option>
                                </select>
                                @error('sudah_ada_mentor')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div id="mentor-container"
                                style="display: {{ old('sudah_ada_mentor') == 'Ya' ? 'block' : 'none' }};">
                                <div class="form-group">
                                    <label class="form-label required">Pilih Mentor atau Tambah Baru *</label>
                                    <select name="mentor_mode" id="mentor_mode"
                                        class="form-select @error('mentor_mode') error @enderror">
                                        <option value="">Pilih Menu</option>
                                        <option value="pilih" {{ old('mentor_mode') == 'pilih' ? 'selected' : '' }}>Daftar
                                            mentor</option>
                                        <option value="tambah" {{ old('mentor_mode') == 'tambah' ? 'selected' : '' }}>Tambah
                                            mentor (Jika tidak ada di daftar mentor)</option>
                                    </select>
                                    @error('mentor_mode')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Form untuk memilih mentor yang sudah ada -->
                                <div id="select-mentor-form"
                                    style="display: {{ old('mentor_mode') == 'pilih' ? 'block' : 'none' }};">
                                    <div class="form-group">
                                        <label class="form-label required">Pilih Mentor *</label>
                                        <select name="id_mentor" id="id_mentor"
                                            class="form-select @error('id_mentor') error @enderror">
                                            <option value="">Pilih Mentor...</option>
                                            @foreach($mentorList as $mentor)
                                                <option value="{{ $mentor->id }}" data-nama="{{ $mentor->nama_mentor }}"
                                                    data-jabatan="{{ $mentor->jabatan_mentor }}"
                                                    data-nomor-rekening="{{ $mentor->nomor_rekening_mentor }}"
                                                    data-npwp="{{ $mentor->npwp_mentor }}" {{ old('id_mentor') == $mentor->id ? 'selected' : '' }}>
                                                    {{ $mentor->nama_mentor }} - {{ $mentor->jabatan_mentor }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_mentor')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group">
                                            <label class="form-label required">Nama Mentor *</label>
                                            <input type="text" name="nama_mentor" id="nama_mentor_select"
                                                class="form-input @error('nama_mentor') error @enderror"
                                                value="{{ old('nama_mentor') }}" readonly>
                                            @error('nama_mentor')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label required">Jabatan Mentor *</label>
                                            <input type="text" name="jabatan_mentor" id="jabatan_mentor_select"
                                                class="form-input @error('jabatan_mentor') error @enderror"
                                                value="{{ old('jabatan_mentor') }}" readonly>
                                            @error('jabatan_mentor')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group">
                                            <label class="form-label">Nomor Rekening Mentor</label>
                                            <input type="text" name="nomor_rekening_mentor"
                                                id="nomor_rekening_mentor_select"
                                                class="form-input @error('nomor_rekening_mentor') error @enderror"
                                                placeholder="Bank Mandiri, 174xxxxxxxxx a.n Nanang Wijaya"
                                                value="{{ old('nomor_rekening_mentor') }}" readonly>
                                            @error('nomor_rekening_mentor')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">NPWP Mentor</label>
                                            <input type="text" name="npwp_mentor" id="npwp_mentor_select"
                                                class="form-input @error('npwp_mentor') error @enderror"
                                                value="{{ old('npwp_mentor') }}" readonly>
                                            @error('npwp_mentor')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Form untuk menambah mentor baru -->
                                <div id="add-mentor-form"
                                    style="display: {{ old('mentor_mode') == 'tambah' ? 'block' : 'none' }};">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i>
                                        Silakan lengkapi data mentor baru
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group">
                                            <label class="form-label required">Nama Mentor *</label>
                                            <input type="text" name="nama_mentor_baru" id="nama_mentor_baru"
                                                class="form-input @error('nama_mentor_baru') error @enderror"
                                                value="{{ old('nama_mentor_baru') }}" placeholder="Masukkan nama mentor">
                                            @error('nama_mentor_baru')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label required">Jabatan Mentor *</label>
                                            <input type="text" name="jabatan_mentor_baru" id="jabatan_mentor_baru"
                                                class="form-input @error('jabatan_mentor_baru') error @enderror"
                                                value="{{ old('jabatan_mentor_baru') }}"
                                                placeholder="Masukkan jabatan mentor">
                                            @error('jabatan_mentor_baru')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group">
                                            <label class="form-label">Nomor Rekening Mentor</label>
                                            <input type="text" name="nomor_rekening_mentor_baru"
                                                id="nomor_rekening_mentor_baru"
                                                class="form-input @error('nomor_rekening_mentor_baru') error @enderror"
                                                placeholder="Bank Mandiri, 174xxxxxxxxx a.n Nanang Wijaya"
                                                value="{{ old('nomor_rekening_mentor_baru') }}">
                                            @error('nomor_rekening_mentor_baru')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">NPWP Mentor</label>
                                            <input type="text" name="npwp_mentor_baru" id="npwp_mentor_baru"
                                                class="form-input @error('npwp_mentor_baru') error @enderror"
                                                value="{{ old('npwp_mentor_baru') }}" placeholder="Masukkan NPWP mentor">
                                            @error('npwp_mentor_baru')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="step-navigation">
                            <button type="button" class="btn btn-secondary" id="back-to-step2">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </button>
                            <button type="button" class="btn btn-primary" id="next-to-step4">
                                Lanjutkan <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 4: Dokumen -->
                    <div class="form-step" id="step4-content">
                        <div class="step-header">
                            <h2 class="step-title">Dokumen Pendaftaran</h2>
                            <p class="step-description">Unggah dokumen yang diperlukan (dapat diunggah nanti)</p>
                            <div class="selected-info">
                                <div class="info-badge">
                                    <i class="fas fa-book"></i> <span id="current-training-name-3">PKN TK II</span>
                                </div>
                                <div class="info-badge">
                                    <i class="fas fa-calendar-alt"></i> Angkatan: <span id="current-angkatan-name-3"></span>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Perhatian:</strong> Dokumen dapat diunggah nanti setelah peserta terdaftar. Namun
                            disarankan untuk mengunggah dokumen utama terlebih dahulu.
                        </div>

                        <!-- Dokumen Pendukung -->
                        <div class="dynamic-form-container" id="dynamic-form-container-3">
                            <div class="form-section-header">
                                <i class="fas fa-file-upload"></i> Dokumen Pendukung
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label required">Unggah Bukti SK Jabatan Terakhir (Definitif)
                                        *</label>
                                    <div class="form-file">
                                        <input type="file" name="file_sk_jabatan" id="file_sk_jabatan"
                                            class="form-file-input @error('file_sk_jabatan') error @enderror" accept=".pdf"
                                            {{ old('file_sk_jabatan') ? '' : 'required' }}>
                                        <label class="form-file-label" for="file_sk_jabatan">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <span>Klik untuk mengunggah file PDF (maks. 5MB)</span>
                                        </label>
                                        <div class="form-file-name" id="fileSkJabatanName">
                                            @if(old('file_sk_jabatan'))
                                                File sudah diupload sebelumnya
                                            @else
                                                Belum ada file dipilih
                                            @endif
                                        </div>
                                    </div>
                                    @error('file_sk_jabatan')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label required">Unggah Bukti SK Pangkat/Golongan Ruang Terakhir
                                        *</label>
                                    <div class="form-file">
                                        <input type="file" name="file_sk_pangkat" id="file_sk_pangkat"
                                            class="form-file-input @error('file_sk_pangkat') error @enderror" accept=".pdf"
                                            {{ old('file_sk_pangkat') ? '' : 'required' }}>
                                        <label class="form-file-label" for="file_sk_pangkat">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <span>Klik untuk mengunggah file PDF (maks. 5MB)</span>
                                        </label>
                                        <div class="form-file-name" id="fileSkPangkatName">
                                            @if(old('file_sk_pangkat'))
                                                File sudah diupload sebelumnya
                                            @else
                                                Belum ada file dipilih
                                            @endif
                                        </div>
                                    </div>
                                    @error('file_sk_pangkat')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Unggah Surat Pernyataan Komitmen</label>
                                <div class="form-hint">jika sudah ada dan di tandatangani pejabat pembuat komitmen, namun
                                    jika belum maka WAJIB disertakan saat registrasi ulang di Puslatbang KMP</div>
                                <div class="form-file">
                                    <input type="file" name="file_surat_komitmen" id="file_surat_komitmen"
                                        class="form-file-input @error('file_surat_komitmen') error @enderror" accept=".pdf">
                                    <label class="form-file-label" for="file_surat_komitmen">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>Klik untuk mengunggah file PDF (maks. 5MB)</span>
                                    </label>
                                    <div class="form-file-name" id="fileSuratKomitmenName">
                                        @if(old('file_surat_komitmen'))
                                            File sudah diupload sebelumnya
                                        @else
                                            Belum ada file dipilih
                                        @endif
                                    </div>
                                </div>
                                @error('file_surat_komitmen')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label required">Unggah Scan Pakta Integritas (Formulir menggunakan Kop
                                    Instansi) *</label>
                                <div class="form-file">
                                    <input type="file" name="file_pakta_integritas" id="file_pakta_integritas"
                                        class="form-file-input @error('file_pakta_integritas') error @enderror"
                                        accept=".pdf" {{ old('file_pakta_integritas') ? '' : 'required' }}>
                                    <label class="form-file-label" for="file_pakta_integritas">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>Klik untuk mengunggah file PDF (maks. 5MB)</span>
                                    </label>
                                    <div class="form-file-name" id="filePaktaIntegritasName">
                                        @if(old('file_pakta_integritas'))
                                            File sudah diupload sebelumnya
                                        @else
                                            Belum ada file dipilih
                                        @endif
                                    </div>
                                </div>
                                @error('file_pakta_integritas')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Unggah Scan Surat Tugas mengikuti pelatihan yang ditandatangani
                                    oleh pejabat yang berwenang</label>
                                <div class="form-hint">jika belum maka WAJIB disertakan saat registrasi ulang di Puslatbang
                                    KMP</div>
                                <div class="form-file">
                                    <input type="file" name="file_surat_tugas" id="file_surat_tugas"
                                        class="form-file-input @error('file_surat_tugas') error @enderror" accept=".pdf">
                                    <label class="form-file-label" for="file_surat_tugas">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>Klik untuk mengunggah file PDF (maks. 5MB)</span>
                                    </label>
                                    <div class="form-file-name" id="fileSuratTugasName">
                                        @if(old('file_surat_tugas'))
                                            File sudah diupload sebelumnya
                                        @else
                                            Belum ada file dipilih
                                        @endif
                                    </div>
                                </div>
                                @error('file_surat_tugas')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Unggah Scan Surat Keterangan Kelulusan/Hasil Seleksi calon peserta
                                    PKN TK.II</label>
                                <div class="form-hint">bagi calon peserta yang masih menduduki jabatan administrator/Eselon
                                    III</div>
                                <div class="form-file">
                                    <input type="file" name="file_surat_kelulusan_seleksi" id="file_surat_kelulusan_seleksi"
                                        class="form-file-input @error('file_surat_kelulusan_seleksi') error @enderror"
                                        accept=".pdf">
                                    <label class="form-file-label" for="file_surat_kelulusan_seleksi">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>Klik untuk mengunggah file PDF (maks. 5MB)</span>
                                    </label>
                                    <div class="form-file-name" id="fileSuratKelulusanName">
                                        @if(old('file_surat_kelulusan_seleksi'))
                                            File sudah diupload sebelumnya
                                        @else
                                            Belum ada file dipilih
                                        @endif
                                    </div>
                                </div>
                                @error('file_surat_kelulusan_seleksi')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label required">Unggah Surat Keterangan Berbadan Sehat *</label>
                                    <div class="form-file">
                                        <input type="file" name="file_surat_sehat" id="file_surat_sehat"
                                            class="form-file-input @error('file_surat_sehat') error @enderror" accept=".pdf"
                                            {{ old('file_surat_sehat') ? '' : 'required' }}>
                                        <label class="form-file-label" for="file_surat_sehat">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <span>Klik untuk mengunggah file PDF (maks. 5MB)</span>
                                        </label>
                                        <div class="form-file-name" id="fileSuratSehatName">
                                            @if(old('file_surat_sehat'))
                                                File sudah diupload sebelumnya
                                            @else
                                                Belum ada file dipilih
                                            @endif
                                        </div>
                                    </div>
                                    @error('file_surat_sehat')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label required">Unggah Surat Keterangan Bebas Narkoba *</label>
                                    <div class="form-file">
                                        <input type="file" name="file_surat_bebas_narkoba" id="file_surat_bebas_narkoba"
                                            class="form-file-input @error('file_surat_bebas_narkoba') error @enderror"
                                            accept=".pdf" {{ old('file_surat_bebas_narkoba') ? '' : 'required' }}>
                                        <label class="form-file-label" for="file_surat_bebas_narkoba">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <span>Klik untuk mengunggah file PDF (maks. 5MB)</span>
                                        </label>
                                        <div class="form-file-name" id="fileSuratBebasNarkobaName">
                                            @if(old('file_surat_bebas_narkoba'))
                                                File sudah diupload sebelumnya
                                            @else
                                                Belum ada file dipilih
                                            @endif
                                        </div>
                                    </div>
                                    @error('file_surat_bebas_narkoba')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label required">Upload Pasfoto peserta berwarna *</label>
                                <div class="form-file">
                                    <input type="file" name="file_pas_foto" id="file_pas_foto"
                                        class="form-file-input @error('file_pas_foto') error @enderror"
                                        accept=".jpg,.jpeg,.png" {{ old('file_pas_foto') ? '' : 'required' }}>
                                    <label class="form-file-label" for="file_pas_foto">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>Klik untuk mengunggah file JPG/PNG (maks. 5MB)</span>
                                    </label>
                                    <div class="form-file-name" id="filePasFotoName">
                                        @if(old('file_pas_foto'))
                                            File sudah diupload sebelumnya
                                        @else
                                            Belum ada file dipilih
                                        @endif
                                    </div>
                                </div>
                                @error('file_pas_foto')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="step-navigation">
                            <button type="button" class="btn btn-secondary" id="back-to-step3">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </button>
                            <button type="submit" class="btn btn-success" id="submit-form">
                                <i class="fas fa-save"></i> Simpan Peserta
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@section('styles')
    <style>
        :root {
            --primary-color: #1a3a6c;
            --secondary-color: #2c5282;
            --accent-color: #4299e1;
            --success-color: #48bb78;
            --warning-color: #ed8936;
            --danger-color: #f56565;
            --light-color: #f7fafc;
            --dark-color: #2d3748;
            --gray-color: #718096;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        /* Hero Section */
        .form-hero {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 40px 0;
            margin-bottom: 40px;
        }

        .form-hero-content {
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
        }

        .form-hero-title {
            font-size: 2rem;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .form-hero-text {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .progress-indicator {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-top: 30px;
        }

        .progress-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            opacity: 0.5;
            transition: var(--transition);
        }

        .progress-step.active {
            opacity: 1;
        }

        .step-number {
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            border: 2px solid transparent;
        }

        .progress-step.active .step-number {
            background-color: var(--accent-color);
            border-color: white;
        }

        .step-label {
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Form Section */
        .form-section {
            padding: 0 0 60px 0;
        }

        .form-wrapper {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .form-step {
            padding: 40px;
            display: none;
        }

        .form-step.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .step-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .step-title {
            font-size: 1.8rem;
            color: var(--primary-color);
            margin-bottom: 10px;
            font-weight: 600;
        }

        .step-description {
            color: var(--gray-color);
            margin-bottom: 20px;
        }

        /* Selected Training */
        .selected-training {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(72, 187, 120, 0.1);
            color: var(--success-color);
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 15px;
            font-weight: 500;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark-color);
        }

        .form-label.required::after {
            content: " *";
            color: var(--danger-color);
        }

        .form-select,
        .form-input,
        .form-textarea {
            width: 100%;
            padding: 10px 12px;
            border: 2px solid #e2e8f0;
            border-radius: 6px;
            font-size: 1rem;
            transition: var(--transition);
            background: white;
        }

        .form-select.error,
        .form-input.error,
        .form-textarea.error {
            border-color: var(--danger-color) !important;
            box-shadow: 0 0 0 3px rgba(245, 101, 101, 0.1) !important;
        }

        .form-select:focus,
        .form-input:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
        }

        .form-textarea {
            min-height: 100px;
            resize: vertical;
        }

        .form-hint {
            font-size: 0.85rem;
            color: var(--gray-color);
            margin-top: 5px;
            font-style: italic;
        }

        .form-file {
            position: relative;
            overflow: hidden;
        }

        .form-file-input {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .form-file-label {
            display: block;
            padding: 20px;
            background: var(--light-color);
            border: 2px dashed #cbd5e0;
            border-radius: 6px;
            text-align: center;
            cursor: pointer;
            transition: var(--transition);
        }

        .form-file-label:hover {
            border-color: var(--accent-color);
            background: rgba(66, 153, 225, 0.05);
        }

        .form-file-name {
            margin-top: 10px;
            font-size: 0.9rem;
            color: var(--gray-color);
        }

        /* Error Styling */
        .text-danger {
            color: var(--danger-color) !important;
            font-size: 0.85rem;
            margin-top: 5px;
            display: block;
        }

        /* Angkatan Info */
        .angkatan-info {
            margin-top: 30px;
        }

        .info-card {
            background: linear-gradient(135deg, #f7fafc, #edf2f7);
            border-radius: 10px;
            padding: 20px;
            border-left: 4px solid var(--accent-color);
        }

        .info-card h4 {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--primary-color);
            margin-bottom: 15px;
            font-size: 1.1rem;
        }

        .info-details {
            display: grid;
            gap: 10px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .info-label {
            font-weight: 500;
            color: var(--dark-color);
        }

        .info-value {
            color: var(--gray-color);
        }

        .info-badge {
            display: inline-block;
            padding: 4px 12px;
            background: var(--success-color);
            color: white;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        /* Selected Info */
        .selected-info {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .selected-info .info-badge {
            background: var(--primary-color);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
        }

        .dynamic-form-container {
            margin-top: 30px;
        }

        .form-section-header {
            font-size: 1.4rem;
            color: var(--primary-color);
            margin: 40px 0 20px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-section-header:first-child {
            margin-top: 0;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .checkbox-group input[type="checkbox"] {
            width: 18px;
            height: 18px;
        }

        .checkbox-group label {
            margin: 0;
            font-weight: normal;
        }

        /* Alert Styling */
        .alert {
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .alert-info {
            background-color: rgba(66, 153, 225, 0.1);
            border-color: var(--accent-color);
            color: var(--primary-color);
        }

        .alert-warning {
            background-color: rgba(237, 137, 54, 0.1);
            border-color: var(--warning-color);
            color: #744210;
        }

        /* Buttons */
        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            background: var(--accent-color);
            color: white;
        }

        .btn-primary:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
        }

        .btn-primary:disabled {
            background: #cbd5e0;
            cursor: not-allowed;
            transform: none;
        }

        .btn-secondary {
            background: #e2e8f0;
            color: var(--dark-color);
        }

        .btn-secondary:hover {
            background: #cbd5e0;
        }

        .btn-success {
            background: var(--success-color);
            color: white;
        }

        .btn-success:hover {
            background: #38a169;
            transform: translateY(-2px);
        }

        .step-navigation {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .form-hero {
                padding: 30px 0;
            }

            .form-hero-title {
                font-size: 1.5rem;
            }

            .progress-indicator {
                gap: 20px;
            }

            .progress-step {
                gap: 5px;
            }

            .step-label {
                font-size: 0.8rem;
            }

            .form-step {
                padding: 20px;
            }

            .step-title {
                font-size: 1.5rem;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .step-navigation {
                flex-direction: column;
                gap: 10px;
            }

            .step-navigation .btn {
                width: 100%;
            }
        }
    </style>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ============================================
            // VARIABLES & ELEMENTS
            // ============================================
            const step1Content = document.getElementById('step1-content');
            const step2Content = document.getElementById('step2-content');
            const step3Content = document.getElementById('step3-content');
            const step4Content = document.getElementById('step4-content');
            const step1Indicator = document.getElementById('step1');
            const step2Indicator = document.getElementById('step2');
            const step3Indicator = document.getElementById('step3');
            const step4Indicator = document.getElementById('step4');
            const angkatanSelect = document.getElementById('id_angkatan');
            const selectedTrainingName = document.getElementById('selected-training-name');
            const currentTrainingName = document.getElementById('current-training-name');
            const currentAngkatanName = document.getElementById('current-angkatan-name');
            const currentTrainingName2 = document.getElementById('current-training-name-2');
            const currentAngkatanName2 = document.getElementById('current-angkatan-name-2');
            const currentTrainingName3 = document.getElementById('current-training-name-3');
            const currentAngkatanName3 = document.getElementById('current-angkatan-name-3');
            const backToStep1Btn = document.getElementById('back-to-step1');
            const backToStep2Btn = document.getElementById('back-to-step2');
            const backToStep3Btn = document.getElementById('back-to-step3');
            const nextToStep2Btn = document.getElementById('next-to-step2');
            const nextToStep3Btn = document.getElementById('next-to-step3');
            const nextToStep4Btn = document.getElementById('next-to-step4');
            const submitFormBtn = document.getElementById('submit-form');
            const angkatanInfo = document.getElementById('angkatan-info');

            // Mentor elements
            const mentorContainer = document.getElementById('mentor-container');
            const sudahAdaMentorSelect = document.getElementById('sudah_ada_mentor');
            const mentorModeSelect = document.getElementById('mentor_mode');
            const selectMentorForm = document.getElementById('select-mentor-form');
            const addMentorForm = document.getElementById('add-mentor-form');
            const mentorSelect = document.getElementById('id_mentor');
            const namaMentorSelect = document.getElementById('nama_mentor_select');
            const jabatanMentorSelect = document.getElementById('jabatan_mentor_select');
            const nomorRekeningMentorSelect = document.getElementById('nomor_rekening_mentor_select');
            const npwpMentorSelect = document.getElementById('npwp_mentor_select');

            let selectedAngkatan = null;

            // ============================================
            // HANDLE OLD VALUES (IF VALIDATION FAILED)
            // ============================================
            window.oldValues = @json(old(), JSON_PRETTY_PRINT);
            const validationFailed = @json($errors->any() ? true : false);

            // Jika ada validation errors, auto-select angkatan yang dipilih sebelumnya
            if (validationFailed && window.oldValues.id_angkatan) {
                setTimeout(() => {
                    if (window.oldValues.id_angkatan) {
                        angkatanSelect.value = window.oldValues.id_angkatan;
                        angkatanSelect.dispatchEvent(new Event('change'));
                    }
                }, 100);
            }

            // ============================================
            // STEP 2: ANGKATAN SELECTION
            // ============================================
            angkatanSelect.addEventListener('change', function () {
                if (!this.value) {
                    nextToStep2Btn.disabled = true;
                    angkatanInfo.style.display = 'none';
                    return;
                }

                const selectedOption = this.options[this.selectedIndex];
                selectedAngkatan = {
                    id: this.value,
                    nama: selectedOption.dataset.nama,
                    tahun: selectedOption.dataset.tahun,
                    kuota: selectedOption.dataset.kuota,
                    status: selectedOption.dataset.status
                };

                // Update UI
                currentAngkatanName.textContent = `${selectedAngkatan.nama} (${selectedAngkatan.tahun})`;
                currentAngkatanName2.textContent = `${selectedAngkatan.nama} (${selectedAngkatan.tahun})`;
                currentAngkatanName3.textContent = `${selectedAngkatan.nama} (${selectedAngkatan.tahun})`;

                // Show angkatan info
                document.getElementById('info-nama-angkatan').textContent = selectedAngkatan.nama;
                document.getElementById('info-tahun-angkatan').textContent = selectedAngkatan.tahun;
                document.getElementById('info-kuota-angkatan').textContent = selectedAngkatan.kuota;

                const statusBadge = document.getElementById('info-status-angkatan');
                statusBadge.textContent = selectedAngkatan.status;
                statusBadge.className = 'info-badge';
                if (selectedAngkatan.status === 'Aktif') {
                    statusBadge.style.background = 'var(--success-color)';
                } else if (selectedAngkatan.status === 'Penuh') {
                    statusBadge.style.background = 'var(--danger-color)';
                } else {
                    statusBadge.style.background = 'var(--warning-color)';
                }

                angkatanInfo.style.display = 'block';
                nextToStep2Btn.disabled = false;
            });

            // ============================================
            // STEP 3: MENTOR HANDLERS
            // ============================================
            if (sudahAdaMentorSelect) {
                sudahAdaMentorSelect.addEventListener('change', function () {
                    if (this.value === 'Ya') {
                        mentorContainer.style.display = 'block';
                    } else {
                        mentorContainer.style.display = 'none';
                        // Reset mentor forms
                        mentorModeSelect.value = '';
                        selectMentorForm.style.display = 'none';
                        addMentorForm.style.display = 'none';
                        mentorSelect.value = '';
                        resetMentorFields();
                    }
                });

                mentorModeSelect.addEventListener('change', function () {
                    if (this.value === 'pilih') {
                        selectMentorForm.style.display = 'block';
                        addMentorForm.style.display = 'none';
                        // Load mentors if not loaded
                        if (mentorSelect.options.length <= 1) {
                            loadMentors();
                        }
                    } else if (this.value === 'tambah') {
                        selectMentorForm.style.display = 'none';
                        addMentorForm.style.display = 'block';
                        resetMentorFields();
                    } else {
                        selectMentorForm.style.display = 'none';
                        addMentorForm.style.display = 'none';
                    }
                });

                if (mentorSelect) {
                    mentorSelect.addEventListener('change', function () {
                        if (this.value) {
                            const selectedOption = this.options[this.selectedIndex];
                            const mentorData = JSON.parse(selectedOption.dataset.mentor || '{}');

                            // Populate fields with mentor data
                            namaMentorSelect.value = selectedOption.dataset.nama || '';
                            jabatanMentorSelect.value = selectedOption.dataset.jabatan || '';
                            nomorRekeningMentorSelect.value = selectedOption.dataset.nomorRekening || '';
                            npwpMentorSelect.value = selectedOption.dataset.npwp || '';
                        } else {
                            resetMentorFields();
                        }
                    });
                }

                // Trigger change if value exists (for old form values)
                if (sudahAdaMentorSelect.value) {
                    sudahAdaMentorSelect.dispatchEvent(new Event('change'));
                }
                if (mentorModeSelect.value) {
                    mentorModeSelect.dispatchEvent(new Event('change'));
                }
                if (mentorSelect && mentorSelect.value) {
                    mentorSelect.dispatchEvent(new Event('change'));
                }
            }

            function resetMentorFields() {
                if (namaMentorSelect) namaMentorSelect.value = '';
                if (jabatanMentorSelect) jabatanMentorSelect.value = '';
                if (nomorRekeningMentorSelect) nomorRekeningMentorSelect.value = '';
                if (npwpMentorSelect) npwpMentorSelect.value = '';
            }

            async function loadMentors() {
                if (!mentorSelect) return;

                mentorSelect.innerHTML = '<option value="">Memuat daftar mentor...</option>';
                mentorSelect.disabled = true;

                try {
                    const response = await fetch('/api/mentors');
                    const data = await response.json();

                    mentorSelect.innerHTML = '<option value="">Pilih Mentor...</option>';
                    mentorSelect.disabled = false;

                    data.forEach(mentor => {
                        const option = document.createElement('option');
                        option.value = mentor.id_mentor || mentor.id;
                        option.textContent = `${mentor.nama_mentor} - ${mentor.jabatan_mentor}`;

                        // Store mentor data in dataset
                        option.dataset.nama = mentor.nama_mentor;
                        option.dataset.jabatan = mentor.jabatan_mentor;
                        option.dataset.nomorRekening = mentor.nomor_rekening_mentor || mentor.nomor_rekening || '';
                        option.dataset.npwp = mentor.npwp_mentor || mentor.npwp || '';
                        option.dataset.mentor = JSON.stringify({
                            nama_mentor: mentor.nama_mentor,
                            jabatan_mentor: mentor.jabatan_mentor,
                            nomor_rekening_mentor: mentor.nomor_rekening_mentor || mentor.nomor_rekening,
                            npwp_mentor: mentor.npwp_mentor || mentor.npwp
                        });

                        mentorSelect.appendChild(option);
                    });

                    // Set old value if exists
                    if (window.oldValues && window.oldValues.id_mentor) {
                        mentorSelect.value = window.oldValues.id_mentor;
                        if (mentorSelect.value) {
                            mentorSelect.dispatchEvent(new Event('change'));
                        }
                    }
                } catch (error) {
                    console.error('Error loading mentors:', error);
                    mentorSelect.innerHTML = '<option value="">Error loading mentors</option>';
                    mentorSelect.disabled = false;
                }
            }

            // ============================================
            // PROVINSI & KABUPATEN HANDLERS
            // ============================================
            const provinsiSelect = document.getElementById('id_provinsi');
            const kabupatenSelect = document.getElementById('id_kabupaten_kota');

            if (provinsiSelect) {
                provinsiSelect.addEventListener('change', async function () {
                    const provinsiId = this.value;

                    if (!provinsiId) {
                        kabupatenSelect.innerHTML = '<option value="">Pilih Kabupaten (Pilih Provinsi Dahulu)</option>';
                        kabupatenSelect.disabled = true;
                        return;
                    }

                    kabupatenSelect.innerHTML = '<option value="">Memuat kabupaten/kota...</option>';
                    kabupatenSelect.disabled = true;

                    try {
                        const response = await fetch(`/proxy/regencies/${provinsiId}`);
                        const result = await response.json();

                        kabupatenSelect.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
                        kabupatenSelect.disabled = false;

                        result.data.forEach(kabupaten => {
                            const option = document.createElement('option');
                            option.value = kabupaten.id || kabupaten.code;
                            option.textContent = kabupaten.name;
                            kabupatenSelect.appendChild(option);
                        });

                        // Set old value if exists
                        if (window.oldValues && window.oldValues.id_kabupaten_kota) {
                            kabupatenSelect.value = window.oldValues.id_kabupaten_kota;
                        }
                    } catch (error) {
                        console.error('Error loading kabupaten:', error);
                        kabupatenSelect.innerHTML = '<option value="">Error loading data</option>';
                        kabupatenSelect.disabled = false;
                    }
                });

                // Trigger change if value exists (for old form values)
                if (provinsiSelect.value) {
                    provinsiSelect.dispatchEvent(new Event('change'));
                }
            }

            // ============================================
            // FILE INPUT HANDLERS
            // ============================================
            document.querySelectorAll('.form-file-input').forEach(input => {
                input.addEventListener('change', function () {
                    const fileName = this.files[0]?.name || 'Belum ada file dipilih';
                    this.parentElement.querySelector('.form-file-name').textContent = fileName;

                    // Remove error class when file is selected
                    this.classList.remove('error');
                });
            });

            // ============================================
            // NAVIGATION FUNCTIONS
            // ============================================
            function moveToStep(step) {
                // Update indicators
                [step1Indicator, step2Indicator, step3Indicator, step4Indicator].forEach(indicator => {
                    indicator.classList.remove('active');
                });
                document.getElementById(`step${step}`).classList.add('active');

                // Update content
                [step1Content, step2Content, step3Content, step4Content].forEach(content => {
                    content.classList.remove('active');
                });
                document.getElementById(`step${step}-content`).classList.add('active');

                // Scroll to top
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }

            // Navigation event listeners
            nextToStep2Btn.addEventListener('click', () => {
                if (selectedAngkatan) {
                    moveToStep(2);
                }
            });

            nextToStep3Btn.addEventListener('click', () => {
                // Validate required fields in step 2
                const requiredFields = document.querySelectorAll('#step2-content [required]');
                let isValid = true;
                let firstInvalidField = null;

                requiredFields.forEach(field => {
                    if (!field.value.trim() && field.type !== 'file') {
                        if (!firstInvalidField) firstInvalidField = field;
                        field.classList.add('error');
                        isValid = false;
                    } else {
                        field.classList.remove('error');
                    }
                });

                if (!isValid) {
                    showNotification('error', 'Silakan lengkapi semua field yang wajib diisi');
                    if (firstInvalidField) {
                        firstInvalidField.focus();
                        firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                    return;
                }

                moveToStep(3);
            });

            nextToStep4Btn.addEventListener('click', () => {
                // Validate required fields in step 3
                const requiredFields = document.querySelectorAll('#step3-content [required]');
                let isValid = true;
                let firstInvalidField = null;

                requiredFields.forEach(field => {
                    if (!field.value.trim() && field.type !== 'file') {
                        if (!firstInvalidField) firstInvalidField = field;
                        field.classList.add('error');
                        isValid = false;
                    } else {
                        field.classList.remove('error');
                    }
                });

                // Validate mentor fields if mentor is selected
                if (sudahAdaMentorSelect && sudahAdaMentorSelect.value === 'Ya') {
                    if (!mentorModeSelect.value) {
                        mentorModeSelect.classList.add('error');
                        isValid = false;
                        if (!firstInvalidField) firstInvalidField = mentorModeSelect;
                    } else {
                        mentorModeSelect.classList.remove('error');
                    }

                    if (mentorModeSelect.value === 'pilih' && !mentorSelect.value) {
                        mentorSelect.classList.add('error');
                        isValid = false;
                        if (!firstInvalidField) firstInvalidField = mentorSelect;
                    } else if (mentorModeSelect.value === 'pilih') {
                        mentorSelect.classList.remove('error');
                    }
                }

                if (!isValid) {
                    showNotification('error', 'Silakan lengkapi semua field yang wajib diisi');
                    if (firstInvalidField) {
                        firstInvalidField.focus();
                        firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                    return;
                }

                moveToStep(4);
            });

            backToStep1Btn.addEventListener('click', () => moveToStep(1));
            backToStep2Btn.addEventListener('click', () => moveToStep(2));
            backToStep3Btn.addEventListener('click', () => moveToStep(3));

            // ============================================
            // FORM SUBMISSION HANDLER
            // ============================================
            document.getElementById('tambahPesertaForm').addEventListener('submit', async function (e) {
                e.preventDefault();

                const submitBtn = document.getElementById('submit-form');
                const originalText = submitBtn.innerHTML;

                // Show loading state
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
                submitBtn.disabled = true;

                // Validasi client-side
                const requiredFields = this.querySelectorAll('[required]');
                let hasEmptyRequired = false;

                // Clear previous client-side errors
                document.querySelectorAll('.client-error').forEach(el => el.remove());

                requiredFields.forEach(field => {
                    if (!field.value && field.type !== 'file') {
                        hasEmptyRequired = true;
                        field.classList.add('error');

                        const formGroup = field.closest('.form-group');
                        if (formGroup) {
                            const errorMsg = document.createElement('small');
                            errorMsg.className = 'text-danger client-error';
                            errorMsg.textContent = 'Field ini wajib diisi';
                            formGroup.appendChild(errorMsg);
                        }
                    }
                });

                if (hasEmptyRequired) {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;

                    // Scroll ke error pertama
                    const firstError = document.querySelector('.error');
                    if (firstError) {
                        // Determine which step has the error
                        if (firstError.closest('#step2-content')) {
                            moveToStep(2);
                        } else if (firstError.closest('#step3-content')) {
                            moveToStep(3);
                        } else if (firstError.closest('#step4-content')) {
                            moveToStep(4);
                        }

                        firstError.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }

                    return false;
                }

                // Collect form data
                const formData = new FormData(this);

                // Add CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                try {
                    const response = await fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        showSuccessMessage('Peserta berhasil ditambahkan!');

                        setTimeout(() => {
                            window.location.href = data.redirect_url || '{{ route("peserta.pkn-tk2") }}';
                        }, 1500);

                    } else {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;

                        // Clear previous errors
                        document.querySelectorAll('.server-error').forEach(el => el.remove());
                        document.querySelectorAll('.error').forEach(el => el.classList.remove('error'));

                        // Clear file label error styles
                        document.querySelectorAll('.form-file-label').forEach(label => {
                            label.style.borderColor = '';
                            label.style.background = '';
                        });

                        // Display new errors
                        if (data.errors) {
                            Object.keys(data.errors).forEach(field => {
                                let input = document.querySelector(`[name="${field}"]`);

                                // Jika tidak ketemu, coba dengan nama field yang berbeda
                                if (!input) {
                                    input = document.querySelector(`[name="${field}[]"]`);
                                }

                                if (!input) {
                                    input = document.querySelector(`#${field}`);
                                }

                                if (input) {
                                    input.classList.add('error');

                                    // Untuk file inputs, juga highlight label
                                    if (input.type === 'file') {
                                        const fileLabel = input.closest('.form-file')?.querySelector('.form-file-label');
                                        if (fileLabel) {
                                            fileLabel.style.borderColor = 'var(--danger-color)';
                                            fileLabel.style.background = 'rgba(245, 101, 101, 0.05)';
                                        }
                                    }

                                    // Cari form group
                                    let formGroup = input.closest('.form-group');
                                    if (!formGroup) {
                                        formGroup = input.closest('.checkbox-group') ||
                                            input.closest('.form-check') ||
                                            input.parentElement;
                                    }

                                    if (formGroup) {
                                        // Hapus error message sebelumnya
                                        const existingError = formGroup.querySelector('.server-error');
                                        if (existingError) existingError.remove();

                                        // Tambahkan error message baru
                                        const errorMsg = document.createElement('small');
                                        errorMsg.className = 'text-danger server-error';
                                        errorMsg.textContent = data.errors[field][0];
                                        formGroup.appendChild(errorMsg);
                                    }
                                }
                            });

                            // Scroll ke error pertama
                            const firstError = document.querySelector('.error');
                            if (firstError) {
                                setTimeout(() => {
                                    firstError.scrollIntoView({
                                        behavior: 'smooth',
                                        block: 'center'
                                    });
                                }, 300);
                            }
                        } else if (data.message) {
                            showErrorMessage(data.message);
                        }
                    }

                } catch (error) {
                    console.error('AJAX Error:', error);
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    showErrorMessage('Terjadi kesalahan jaringan. Silakan coba lagi.');
                }
            });

            // ============================================
            // HELPER FUNCTIONS
            // ============================================
            function showSuccessMessage(message) {
                const notification = document.createElement('div');
                notification.className = 'notification success';
                notification.innerHTML = `
                        <div class="notification-content">
                            <i class="fas fa-check-circle"></i>
                            <span>${message}</span>
                        </div>
                    `;

                document.body.appendChild(notification);

                // Animasi masuk
                setTimeout(() => {
                    notification.classList.add('show');
                }, 10);

                // Hapus setelah 3 detik
                setTimeout(() => {
                    notification.classList.remove('show');
                    setTimeout(() => {
                        notification.remove();
                    }, 300);
                }, 3000);
            }

            function showErrorMessage(message) {
                const notification = document.createElement('div');
                notification.className = 'notification error';
                notification.innerHTML = `
                        <div class="notification-content">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>${message}</span>
                        </div>
                    `;

                document.body.appendChild(notification);

                // Animasi masuk
                setTimeout(() => {
                    notification.classList.add('show');
                }, 10);

                // Hapus setelah 5 detik
                setTimeout(() => {
                    notification.classList.remove('show');
                    setTimeout(() => {
                        notification.remove();
                    }, 300);
                }, 5000);
            }

            function showNotification(type, message) {
                const notification = document.createElement('div');
                notification.className = `notification ${type}`;
                notification.innerHTML = `
                        <div class="notification-content">
                            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                            <span>${message}</span>
                        </div>
                    `;

                document.body.appendChild(notification);

                // Animasi masuk
                setTimeout(() => {
                    notification.classList.add('show');
                }, 10);

                // Hapus setelah 5 detik
                setTimeout(() => {
                    notification.classList.remove('show');
                    setTimeout(() => {
                        notification.remove();
                    }, 300);
                }, 5000);
            }

            // Tambahkan CSS untuk notifikasi
            const notificationStyles = `
                    .notification {
                        position: fixed;
                        top: 20px;
                        right: 20px;
                        z-index: 9999;
                        min-width: 300px;
                        max-width: 400px;
                        background: white;
                        border-radius: 8px;
                        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                        padding: 15px 20px;
                        transform: translateX(400px);
                        transition: transform 0.3s ease;
                    }

                    .notification.show {
                        transform: translateX(0);
                    }

                    .notification.success {
                        border-left: 4px solid var(--success-color);
                    }

                    .notification.error {
                        border-left: 4px solid var(--danger-color);
                    }

                    .notification-content {
                        display: flex;
                        align-items: center;
                        gap: 12px;
                    }

                    .notification-content i {
                        font-size: 1.2rem;
                    }

                    .notification.success .notification-content i {
                        color: var(--success-color);
                    }

                    .notification.error .notification-content i {
                        color: var(--danger-color);
                    }

                    .notification-content span {
                        flex: 1;
                        font-size: 0.95rem;
                    }
                `;

            // Inject styles
            const styleSheet = document.createElement("style");
            styleSheet.textContent = notificationStyles;
            document.head.appendChild(styleSheet);

            // Clear error saat input
            document.addEventListener('input', function (e) {
                if (e.target.matches('input, select, textarea')) {
                    e.target.classList.remove('error');

                    const formGroup = e.target.closest('.form-group');
                    if (formGroup) {
                        const errorMsg = formGroup.querySelector('.server-error, .client-error');
                        if (errorMsg) errorMsg.remove();
                    }

                    // Reset file label styling
                    if (e.target.type === 'file') {
                        const fileLabel = e.target.closest('.form-file')?.querySelector('.form-file-label');
                        if (fileLabel) {
                            fileLabel.style.borderColor = '';
                            fileLabel.style.background = '';
                        }
                    }
                }
            });

            // Clear error saat file change
            document.addEventListener('change', function (e) {
                if (e.target.matches('input[type="file"]')) {
                    e.target.classList.remove('error');

                    const formGroup = e.target.closest('.form-group');
                    if (formGroup) {
                        const errorMsg = formGroup.querySelector('.server-error, .client-error');
                        if (errorMsg) errorMsg.remove();
                    }

                    const fileLabel = e.target.closest('.form-file')?.querySelector('.form-file-label');
                    if (fileLabel) {
                        fileLabel.style.borderColor = '';
                        fileLabel.style.background = '';
                    }
                }
            });

            // Auto-capitalize name fields
            document.querySelectorAll('input[name="nama_lengkap"], input[name="tempat_lahir"]').forEach(field => {
                field.addEventListener('input', function () {
                    this.value = this.value.toUpperCase();
                });
            });

            // Initialize angkatan change if value exists
            if (angkatanSelect.value) {
                angkatanSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
@endsection