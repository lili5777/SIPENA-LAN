@php
// Ambil jenis dari route
$jenis = request()->route('jenis');

// Mapping nama dan ID
$jenisMapping = [
    'pkn' => ['nama' => 'PKN TK II', 'id' => 1, 'kode' => 'PKN_TK_II'],
    'latsar' => ['nama' => 'LATSAR', 'id' => 2, 'kode' => 'LATSAR'],
    'pka' => ['nama' => 'PKA', 'id' => 3, 'kode' => 'PKA'],
    'pkp' => ['nama' => 'PKP', 'id' => 4, 'kode' => 'PKP']
];

$jenisData = $jenisMapping[$jenis] ?? ['nama' => 'Pelatihan', 'id' => 1, 'kode' => 'DEFAULT'];
$jenisNama = $jenisData['nama'];
$jenisPelatihanId = $jenisData['id'];
$jenisKode = $jenisData['kode'];

// Data untuk form (dari controller)
$pesertaData = $isEdit ? $pendaftaran->peserta : null;
$kepegawaianData = $isEdit ? ($pendaftaran->peserta->kepegawaianPeserta ?? null) : null;
$mentorData = $isEdit ? ($pendaftaran->pesertaMentor->first() ?? null) : null;

// Tentukan fields yang required berdasarkan jenis
$isCPNS = $jenis === 'cpns';
$isPKA = $jenis === 'pka';
$isPKP = $jenis === 'pkp';
$isPKN = $jenis === 'pkn';
@endphp

@extends('admin.partials.layout')

@section('title', $isEdit ? 'Edit Peserta ' . $jenisNama : 'Tambah Peserta ' . $jenisNama . ' - Sistem Pelatihan')

@section('content')
    <!-- Hero Section -->
    <section class="form-hero" id="home">
        <div class="container">
            <div class="form-hero-content animate">
                <h1 class="form-hero-title">{{ $isEdit ? 'Edit Peserta ' . $jenisNama : 'Tambah Peserta ' . $jenisNama }}
                </h1>
                <p class="form-hero-text">
                    {{ $isEdit ? 'Perbarui data peserta untuk program pelatihan ' . $jenisNama . '.' : 'Daftarkan peserta baru untuk mengikuti program pelatihan ' . $jenisNama . '. Isi formulir dengan data yang lengkap dan valid.' }}
                </p>
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
                <form
                    action="{{ $isEdit ? route('peserta.update', ['jenis' => $jenis, 'id' => $pendaftaran->id]) : route('peserta.store') }}"
                    method="POST" enctype="multipart/form-data" id="pesertaForm">
                    @csrf
                    @if($isEdit)
                        @method('PUT')
                        <input type="hidden" name="id_pendaftaran" value="{{ $pendaftaran->id }}">
                        <input type="hidden" name="id_peserta" value="{{ $pendaftaran->id_peserta }}">
                    @endif
                    <input type="hidden" name="id_jenis_pelatihan" value="{{ $jenisPelatihanId }}">
                    <input type="hidden" name="jenis" value="{{ $jenis }}">

                    <!-- Step 1: Pilih Angkatan -->
                    <div class="form-step active" id="step1-content">
                        <div class="step-header">
                            <h2 class="step-title">Pilih Angkatan Pelatihan</h2>
                            <p class="step-description">Silakan pilih angkatan untuk pelatihan {{ $jenisNama }}</p>
                            <div class="selected-training">
                                <i class="fas fa-check-circle"></i>
                                <span id="selected-training-name">{{ $jenisNama }}</span>
                            </div>
                        </div>

                        <div class="angkatan-container">
                            <div class="form-group">
                                <label for="id_angkatan" class="form-label required">Angkatan </label>
                                <select name="id_angkatan" id="id_angkatan"
                                    class="form-select @error('id_angkatan') error @enderror" required>
                                    <option value="">Pilih Angkatan</option>
                                    @foreach($angkatanList as $angkatan)
                                        <option value="{{ $angkatan->id }}" data-nama="{{ $angkatan->nama_angkatan }}"
                                            data-tahun="{{ $angkatan->tahun }}" data-kuota="{{ $angkatan->kuota }}" data-wilayah="{{ $angkatan->wilayah }}"
                                            data-status="{{ $angkatan->status_angkatan }}" data-kategori="{{ $angkatan->kategori }}" {{ ($isEdit && $pendaftaran->id_angkatan == $angkatan->id) || old('id_angkatan') == $angkatan->id ? 'selected' : '' }}>
                                            {{ $angkatan->nama_angkatan }} {{ $angkatan->tahun }} - {{ $angkatan->kategori }}
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
                                            <span class="info-label">Kategori:</span>
                                            <span class="info-value" id="info-kategori-angkatan"></span>
                                        </div>
                                        <div class="info-item" id="info-wilayah-wrapper" style="display:none;">
                                            <span class="info-label">Wilayah:</span>
                                            <span class="info-value" id="info-wilayah-angkatan"></span>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label">Kuota:</span>
                                            <span class="info-value" id="info-kuota-angkatan"></span>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label">Status:</span>
                                            <span class="info-badge" id="info-status-angkatan"></span>
                                        </div>
                                        <div class="info-item" id="info-pic-wrapper" style="display:none;">
                                            <span class="info-label">PIC:</span>
                                           <span class="info-value" id="info-pic-angkatan"></span>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="step-navigation">
                            <a href="{{ route('peserta.index', ['jenis' => $jenis]) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                            </a>
                            <button type="button" class="btn btn-primary" id="next-to-step2" {{ !$isEdit ? 'disabled' : '' }}>
                                Lanjutkan <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: Data Peserta -->
                    <div class="form-step" id="step2-content">
                        <div class="step-header">
                            <h2 class="step-title">Data Pribadi Peserta</h2>
                            <p class="step-description">Lengkapi data berikut dengan informasi yang valid (kecuali NIP, semua field opsional)</p>
                            <div class="selected-info">
                                <div class="info-badge">
                                    <i class="fas fa-book"></i> <span id="current-training-name">{{ $jenisNama }}</span>
                                </div>
                                <div class="info-badge">
                                    <i class="fas fa-calendar-alt"></i> Angkatan: <span id="current-angkatan-name"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Data Pribadi -->
                        <div class="dynamic-form-container" id="dynamic-form-container">
                            <div class="form-section-header">
                                <i class="fas fa-user-tie"></i> Data Pribadi
                            </div>

                            <!-- NDH Section -->
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Nomor Daftar Hadir (NDH)</label>
                                    <select name="ndh" id="ndh" class="form-select @error('ndh') error @enderror" {{ $isEdit ? '' : 'disabled' }}>
                                        <option value="">Pilih angkatan dulu</option>
                                    </select>
                                    <div class="form-hint" id="ndh-info" style="display:none;">
                                        <i class="fas fa-info-circle"></i> <span id="ndh-stats"></span>
                                    </div>
                                    @error('ndh')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                @if($isEdit && $pesertaData && $pesertaData->ndh)
                                <div class="form-group">
                                    <label class="form-label">Atau Tukar NDH dengan:</label>
                                    <select id="swapNdhSelect" class="form-select">
                                        <option value="">-- Pilih Peserta untuk Tukar NDH --</option>
                                    </select>
                                    <small class="form-hint">
                                        <i class="fas fa-exchange-alt"></i> Pilih peserta lain untuk langsung tukar NDH
                                    </small>
                                </div>
                                @endif
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Nama Lengkap (Berikut Gelar Pendidikan)</label>
                                    <input type="text" name="nama_lengkap"
                                        class="form-input @error('nama_lengkap') error @enderror"
                                        value="{{ $pesertaData ? $pesertaData->nama_lengkap : old('nama_lengkap') }}"
                                        placeholder="Masukkan nama lengkap dengan gelar">
                                    @error('nama_lengkap')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label required">NIP/NRP </label>
                                    <input type="text" name="nip_nrp" class="form-input @error('nip_nrp') error @enderror"
                                        value="{{ $pesertaData ? $pesertaData->nip_nrp : old('nip_nrp') }}" required
                                        placeholder="Masukkan NIP/NRP">
                                    @error('nip_nrp')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Nama Panggilan</label>
                                    <input type="text" name="nama_panggilan" class="form-input @error('nama_panggilan') error @enderror"
                                        value="{{ $pesertaData ? $pesertaData->nama_panggilan : old('nama_panggilan') }}"
                                        placeholder="Masukkan nama panggilan">
                                    @error('nama_panggilan')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') error @enderror">
                                        <option value="">Pilih</option>
                                        <option value="Laki-laki" {{ ($pesertaData && $pesertaData->jenis_kelamin == 'Laki-laki') || old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ ($pesertaData && $pesertaData->jenis_kelamin == 'Perempuan') || old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir"
                                        class="form-input @error('tempat_lahir') error @enderror"
                                        value="{{ $pesertaData ? $pesertaData->tempat_lahir : old('tempat_lahir') }}"
                                        placeholder="Masukkan tempat lahir">
                                    @error('tempat_lahir')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir"
                                        class="form-input @error('tanggal_lahir') error @enderror"
                                        value="{{ $pesertaData ? (is_string($pesertaData->tanggal_lahir) ? \Carbon\Carbon::parse($pesertaData->tanggal_lahir)->format('Y-m-d') : ($pesertaData->tanggal_lahir ? $pesertaData->tanggal_lahir->format('Y-m-d') : '')) : old('tanggal_lahir') }}">
                                    @error('tanggal_lahir')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Agama</label>
                                    <select name="agama" class="form-select @error('agama') error @enderror">
                                        <option value="">Pilih</option>
                                        <option value="Islam" {{ ($pesertaData && $pesertaData->agama == 'Islam') || old('agama') == 'Islam' ? 'selected' : '' }}>Islam</option>
                                        <option value="Kristen" {{ ($pesertaData && $pesertaData->agama == 'Kristen') || old('agama') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                        <option value="Katolik" {{ ($pesertaData && $pesertaData->agama == 'Katolik') || old('agama') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                        <option value="Hindu" {{ ($pesertaData && $pesertaData->agama == 'Hindu') || old('agama') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                        <option value="Buddha" {{ ($pesertaData && $pesertaData->agama == 'Buddha') || old('agama') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                        <option value="Konghucu" {{ ($pesertaData && $pesertaData->agama == 'Konghucu') || old('agama') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                    </select>
                                    @error('agama')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Status Perkawinan</label>
                                    <select name="status_perkawinan" id="status_perkawinan"
                                        class="form-select @error('status_perkawinan') error @enderror">
                                        <option value="">Pilih</option>
                                        <option value="Belum Menikah" {{ ($pesertaData && $pesertaData->status_perkawinan == 'Belum Menikah') || old('status_perkawinan') == 'Belum Menikah' ? 'selected' : '' }}>Belum Menikah</option>
                                        <option value="Menikah" {{ ($pesertaData && $pesertaData->status_perkawinan == 'Menikah') || old('status_perkawinan') == 'Menikah' ? 'selected' : '' }}>Menikah</option>
                                        <option value="Duda" {{ ($pesertaData && $pesertaData->status_perkawinan == 'Duda') || old('status_perkawinan') == 'Duda' ? 'selected' : '' }}>Duda</option>
                                        <option value="Janda" {{ ($pesertaData && $pesertaData->status_perkawinan == 'Janda') || old('status_perkawinan') == 'Janda' ? 'selected' : '' }}>Janda</option>
                                    </select>
                                    @error('status_perkawinan')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Nama Istri/Suami</label>
                                    <input type="text" name="nama_pasangan" id="nama_pasangan"
                                        class="form-input @error('nama_pasangan') error @enderror"
                                        value="{{ $pesertaData ? $pesertaData->nama_pasangan : old('nama_pasangan') }}"
                                        placeholder="Masukkan nama pasangan (jika menikah)"
                                        {{ ($pesertaData && $pesertaData->status_perkawinan != 'Menikah') && !old('nama_pasangan') ? 'disabled' : '' }}>
                                    @error('nama_pasangan')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Email Pribadi</label>
                                    <input type="email" name="email_pribadi"
                                        class="form-input @error('email_pribadi') error @enderror"
                                        value="{{ $pesertaData ? $pesertaData->email_pribadi : old('email_pribadi') }}"
                                        placeholder="email@contoh.com">
                                    @error('email_pribadi')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Nomor HP/WhatsApp</label>
                                    <input type="tel" name="nomor_hp" class="form-input @error('nomor_hp') error @enderror"
                                        value="{{ $pesertaData ? $pesertaData->nomor_hp : old('nomor_hp') }}"
                                        placeholder="0812xxxxxxxx">
                                    @error('nomor_hp')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Alamat Rumah</label>
                                    <textarea name="alamat_rumah" class="form-textarea @error('alamat_rumah') error @enderror"
                                        placeholder="Masukkan alamat lengkap rumah">{{ $pesertaData ? $pesertaData->alamat_rumah : old('alamat_rumah') }}</textarea>
                                    @error('alamat_rumah')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Kondisi Peserta</label>
                                    <textarea name="kondisi_peserta" class="form-textarea @error('kondisi_peserta') error @enderror"
                                        placeholder="Masukkan kondisi peserta">{{ $pesertaData ? $pesertaData->kondisi_peserta : old('kondisi_peserta') }}</textarea>
                                    @error('kondisi_peserta')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Olahraga Kegemaran</label>
                                    <input type="text" name="olahraga_hobi"
                                        class="form-input @error('olahraga_hobi') error @enderror"
                                        value="{{ $pesertaData ? $pesertaData->olahraga_hobi : old('olahraga_hobi') }}"
                                        placeholder="Contoh: Sepakbola, Renang">
                                    @error('olahraga_hobi')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Apakah Anda merokok?</label>
                                    <select name="perokok" class="form-select @error('perokok') error @enderror">
                                        <option value="">Pilih</option>
                                        <option value="Ya" {{ ($pesertaData && $pesertaData->perokok == 'Ya') || old('perokok') == 'Ya' ? 'selected' : '' }}>Ya</option>
                                        <option value="Tidak" {{ ($pesertaData && $pesertaData->perokok == 'Tidak') || old('perokok') == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                                    </select>
                                    @error('perokok')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Pendidikan Terakhir (Sesuai SK CPNS)</label>
                                    <select name="pendidikan_terakhir"
                                        class="form-select @error('pendidikan_terakhir') error @enderror">
                                        <option value="">Pilih</option>
                                        <option value="D3" {{ ($pesertaData && $pesertaData->pendidikan_terakhir == 'D3') || old('pendidikan_terakhir') == 'D3' ? 'selected' : '' }}>D3</option>
                                        <option value="D4" {{ ($pesertaData && $pesertaData->pendidikan_terakhir == 'D4') || old('pendidikan_terakhir') == 'D4' ? 'selected' : '' }}>D4</option>
                                        <option value="S1" {{ ($pesertaData && $pesertaData->pendidikan_terakhir == 'S1') || old('pendidikan_terakhir') == 'S1' ? 'selected' : '' }}>S1</option>
                                        <option value="S2" {{ ($pesertaData && $pesertaData->pendidikan_terakhir == 'S2') || old('pendidikan_terakhir') == 'S2' ? 'selected' : '' }}>S2</option>
                                        <option value="S3" {{ ($pesertaData && $pesertaData->pendidikan_terakhir == 'S3') || old('pendidikan_terakhir') == 'S3' ? 'selected' : '' }}>S3</option>
                                    </select>
                                    @error('pendidikan_terakhir')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Bidang Studi Pendidikan Terakhir</label>
                                    <input type="text" name="bidang_studi"
                                        class="form-input @error('bidang_studi') error @enderror"
                                        value="{{ $pesertaData ? $pesertaData->bidang_studi : old('bidang_studi') }}"
                                        placeholder="Contoh: Ilmu Administrasi">
                                    @error('bidang_studi')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Bidang Keahlian</label>
                                    <input type="text" name="bidang_keahlian"
                                        class="form-input @error('bidang_keahlian') error @enderror"
                                        value="{{ $pesertaData ? $pesertaData->bidang_keahlian : old('bidang_keahlian') }}"
                                        placeholder="Keahlian atau Kompetensi yang menonjol">
                                    @error('bidang_keahlian')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Ukuran Baju Kaos</label>
                                    <select name="ukuran_kaos" class="form-select @error('ukuran_kaos') error @enderror">
                                        <option value="">Pilih</option>
                                        <option value="S" {{ ($pesertaData && $pesertaData->ukuran_kaos == 'S') || old('ukuran_kaos') == 'S' ? 'selected' : '' }}>S</option>
                                        <option value="M" {{ ($pesertaData && $pesertaData->ukuran_kaos == 'M') || old('ukuran_kaos') == 'M' ? 'selected' : '' }}>M</option>
                                        <option value="L" {{ ($pesertaData && $pesertaData->ukuran_kaos == 'L') || old('ukuran_kaos') == 'L' ? 'selected' : '' }}>L</option>
                                        <option value="XL" {{ ($pesertaData && $pesertaData->ukuran_kaos == 'XL') || old('ukuran_kaos') == 'XL' ? 'selected' : '' }}>XL</option>
                                        <option value="XXL" {{ ($pesertaData && $pesertaData->ukuran_kaos == 'XXL') || old('ukuran_kaos') == 'XXL' ? 'selected' : '' }}>XXL</option>
                                        <option value="XXXL" {{ ($pesertaData && $pesertaData->ukuran_kaos == 'XXXL') || old('ukuran_kaos') == 'XXXL' ? 'selected' : '' }}>XXXL</option>
                                    </select>
                                    @error('ukuran_kaos')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Ukuran Baju Taktikal</label>
                                    <select name="ukuran_training" class="form-select @error('ukuran_training') error @enderror">
                                        <option value="">Pilih</option>
                                        <option value="S" {{ ($pesertaData && $pesertaData->ukuran_training == 'S') || old('ukuran_training') == 'S' ? 'selected' : '' }}>S</option>
                                        <option value="M" {{ ($pesertaData && $pesertaData->ukuran_training == 'M') || old('ukuran_training') == 'M' ? 'selected' : '' }}>M</option>
                                        <option value="L" {{ ($pesertaData && $pesertaData->ukuran_training == 'L') || old('ukuran_training') == 'L' ? 'selected' : '' }}>L</option>
                                        <option value="XL" {{ ($pesertaData && $pesertaData->ukuran_training == 'XL') || old('ukuran_training') == 'XL' ? 'selected' : '' }}>XL</option>
                                        <option value="XXL" {{ ($pesertaData && $pesertaData->ukuran_training == 'XXL') || old('ukuran_training') == 'XXL' ? 'selected' : '' }}>XXL</option>
                                        <option value="XXXL" {{ ($pesertaData && $pesertaData->ukuran_training == 'XXXL') || old('ukuran_training') == 'XXXL' ? 'selected' : '' }}>XXXL</option>
                                    </select>
                                    @error('ukuran_training')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Ukuran Celana</label>
                                    <select name="ukuran_celana" class="form-select @error('ukuran_celana') error @enderror">
                                        <option value="">Pilih</option>
                                        <option value="S" {{ ($pesertaData && $pesertaData->ukuran_celana == 'S') || old('ukuran_celana') == 'S' ? 'selected' : '' }}>S</option>
                                        <option value="M" {{ ($pesertaData && $pesertaData->ukuran_celana == 'M') || old('ukuran_celana') == 'M' ? 'selected' : '' }}>M</option>
                                        <option value="L" {{ ($pesertaData && $pesertaData->ukuran_celana == 'L') || old('ukuran_celana') == 'L' ? 'selected' : '' }}>L</option>
                                        <option value="XL" {{ ($pesertaData && $pesertaData->ukuran_celana == 'XL') || old('ukuran_celana') == 'XL' ? 'selected' : '' }}>XL</option>
                                        <option value="XXL" {{ ($pesertaData && $pesertaData->ukuran_celana == 'XXL') || old('ukuran_celana') == 'XXL' ? 'selected' : '' }}>XXL</option>
                                        <option value="XXXL" {{ ($pesertaData && $pesertaData->ukuran_celana == 'XXXL') || old('ukuran_celana') == 'XXXL' ? 'selected' : '' }}>XXXL</option>
                                    </select>
                                    @error('ukuran_celana')
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
                            <p class="step-description">Lengkapi informasi instansi dan penugasan mentor (semua field opsional)</p>
                            <div class="selected-info">
                                <div class="info-badge">
                                    <i class="fas fa-book"></i> <span id="current-training-name-2">{{ $jenisNama }}</span>
                                </div>
                                <div class="info-badge">
                                    <i class="fas fa-calendar-alt"></i> Angkatan: <span id="current-angkatan-name-2"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Data Kepegawaian -->
                        <div class="dynamic-form-container" id="dynamic-form-container-2">
                            <div class="form-section-header">
                                <i class="fas fa-building"></i> Data Kepegawaian
                            </div>

                            <div class="form-row">
                                <!-- ============================================================
                                     ASAL INSTANSI — SEARCHABLE SELECT (641 instansi)
                                     ============================================================ -->
                                <div class="form-group">
                                    <label class="form-label">Asal Instansi</label>

                                    {{-- Hidden input yang dikirim ke server --}}
                                    <input type="hidden" name="asal_instansi" id="asal_instansi_hidden"
                                        value="{{ $kepegawaianData ? $kepegawaianData->asal_instansi : old('asal_instansi') }}">

                                    {{-- Wrapper dengan posisi relative untuk dropdown --}}
                                    <div class="instansi-search-wrapper" style="position:relative;">
                                        {{-- Tombol trigger — tampilkan nama yang dipilih atau placeholder --}}
                                        <div id="instansi_trigger"
                                            class="form-input @error('asal_instansi') error @enderror"
                                            style="cursor:pointer; display:flex; align-items:center; justify-content:space-between; gap:8px; user-select:none; min-height:44px;">
                                            <span id="instansi_trigger_label" style="color:#718096; flex:1; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                                @if($kepegawaianData && $kepegawaianData->asal_instansi)
                                                    {{ $kepegawaianData->asal_instansi }}
                                                @elseif(old('asal_instansi'))
                                                    {{ old('asal_instansi') }}
                                                @else
                                                    Pilih asal instansi...
                                                @endif
                                            </span>
                                            <span style="display:flex; align-items:center; gap:6px; flex-shrink:0;">
                                                <span id="instansi_clear_btn"
                                                    style="display:{{ ($kepegawaianData && $kepegawaianData->asal_instansi) || old('asal_instansi') ? 'flex' : 'none' }};
                                                           align-items:center; color:#e53e3e; cursor:pointer; font-size:1rem; padding:2px 4px;"
                                                    title="Hapus pilihan">
                                                    <i class="fas fa-times-circle"></i>
                                                </span>
                                                <i class="fas fa-chevron-down" id="instansi_chevron" style="color:#718096; font-size:0.85rem; transition:transform 0.2s;"></i>
                                            </span>
                                        </div>

                                        {{-- Panel dropdown (search + list) --}}
                                        <div id="instansi_dropdown"
                                            style="display:none; position:absolute; z-index:9999; width:100%;
                                                   background:white; border:2px solid #4299e1; border-top:none;
                                                   border-radius:0 0 8px 8px;
                                                   box-shadow:0 8px 24px rgba(0,0,0,0.12);">

                                            {{-- Search box di dalam dropdown --}}
                                            <div style="padding:8px 10px; border-bottom:1px solid #e2e8f0; background:#f7fafc; position:sticky; top:0; z-index:1;">
                                                <div style="position:relative;">
                                                    <input type="text" id="asal_instansi_search"
                                                        placeholder="Cari instansi..."
                                                        autocomplete="off"
                                                        style="width:100%; padding:8px 32px 8px 10px; border:1.5px solid #cbd5e0; border-radius:5px; font-size:0.9rem; outline:none; box-sizing:border-box;">
                                                    <i class="fas fa-search" style="position:absolute; right:10px; top:50%; transform:translateY(-50%); color:#a0aec0; font-size:0.85rem; pointer-events:none;"></i>
                                                </div>
                                                <div id="instansi_count" style="font-size:0.75rem; color:#a0aec0; margin-top:4px; padding-left:2px;">
                                                    Menampilkan 641 instansi
                                                </div>
                                            </div>

                                            {{-- List instansi --}}
                                            <div id="instansi_list" style="max-height:240px; overflow-y:auto;">
                                            </div>
                                        </div>
                                    </div>

                                    @error('asal_instansi')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                    <div class="form-hint" style="margin-top:4px;">
                                        <i class="fas fa-info-circle"></i> Klik untuk memilih dari 641 instansi. Gunakan kolom pencarian untuk filter.
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Unit Kerja/ Detail Instansi</label>
                                    <input type="text" name="unit_kerja"
                                        class="form-input @error('unit_kerja') error @enderror"
                                        placeholder="Contoh: Sekretariat Daerah Kota Makassar"
                                        value="{{ $kepegawaianData ? $kepegawaianData->unit_kerja : old('unit_kerja') }}">
                                    @error('unit_kerja')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Golongan Ruang</label>
                                    <select name="golongan_ruang" id="golongan_ruang"
                                        class="form-select @error('golongan_ruang') error @enderror">
                                        <option value="">Pilih</option>
                                        <option value="II/a" {{ ($kepegawaianData && $kepegawaianData->golongan_ruang == 'II/a') || old('golongan_ruang') == 'II/a' ? 'selected' : '' }}>II/a</option>
                                        <option value="II/b" {{ ($kepegawaianData && $kepegawaianData->golongan_ruang == 'II/b') || old('golongan_ruang') == 'II/b' ? 'selected' : '' }}>II/b</option>
                                        <option value="II/c" {{ ($kepegawaianData && $kepegawaianData->golongan_ruang == 'II/c') || old('golongan_ruang') == 'II/c' ? 'selected' : '' }}>II/c</option>
                                        <option value="II/d" {{ ($kepegawaianData && $kepegawaianData->golongan_ruang == 'II/d') || old('golongan_ruang') == 'II/d' ? 'selected' : '' }}>II/d</option>
                                        <option value="III/a" {{ ($kepegawaianData && $kepegawaianData->golongan_ruang == 'III/a') || old('golongan_ruang') == 'III/a' ? 'selected' : '' }}>III/a</option>
                                        <option value="III/b" {{ ($kepegawaianData && $kepegawaianData->golongan_ruang == 'III/b') || old('golongan_ruang') == 'III/b' ? 'selected' : '' }}>III/b</option>
                                        <option value="III/c" {{ ($kepegawaianData && $kepegawaianData->golongan_ruang == 'III/c') || old('golongan_ruang') == 'III/c' ? 'selected' : '' }}>III/c</option>
                                        <option value="III/d" {{ ($kepegawaianData && $kepegawaianData->golongan_ruang == 'III/d') || old('golongan_ruang') == 'III/d' ? 'selected' : '' }}>III/d</option>
                                        <option value="IV/a" {{ ($kepegawaianData && $kepegawaianData->golongan_ruang == 'IV/a') || old('golongan_ruang') == 'IV/a' ? 'selected' : '' }}>IV/a</option>
                                        <option value="IV/b" {{ ($kepegawaianData && $kepegawaianData->golongan_ruang == 'IV/b') || old('golongan_ruang') == 'IV/b' ? 'selected' : '' }}>IV/b</option>
                                        <option value="IV/c" {{ ($kepegawaianData && $kepegawaianData->golongan_ruang == 'IV/c') || old('golongan_ruang') == 'IV/c' ? 'selected' : '' }}>IV/c</option>
                                        <option value="IV/d" {{ ($kepegawaianData && $kepegawaianData->golongan_ruang == 'IV/d') || old('golongan_ruang') == 'IV/d' ? 'selected' : '' }}>IV/d</option>
                                    </select>
                                    @error('golongan_ruang')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Pangkat</label>
                                    <input type="text" name="pangkat" id="pangkat"
                                        class="form-input @error('pangkat') error @enderror"
                                        value="{{ $kepegawaianData ? $kepegawaianData->pangkat : old('pangkat') }}"
                                        placeholder="Pangkat otomatis terisi" readonly>
                                    @error('pangkat')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Jabatan</label>
                                    <input type="text" name="jabatan" class="form-input @error('jabatan') error @enderror"
                                        value="{{ $kepegawaianData ? $kepegawaianData->jabatan : old('jabatan') }}"
                                        placeholder="Jabatan saat ini">
                                    @error('jabatan')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Provinsi (Kantor/Tempat Tugas)</label>
                                    <select name="id_provinsi" id="id_provinsi"
                                        class="form-select @error('id_provinsi') error @enderror">
                                        <option value="">Pilih Provinsi</option>
                                        @foreach($provinsiList as $provinsi)
                                            @php
                                                $isSelected = false;
                                                if ($isEdit && $kepegawaianData && $kepegawaianData->id_provinsi) {
                                                    $isSelected = ($kepegawaianData->id_provinsi == $provinsi->id);
                                                } else {
                                                    $isSelected = (old('id_provinsi') == $provinsi->id);
                                                }
                                            @endphp
                                            <option value="{{ $provinsi->id }}" {{ $isSelected ? 'selected' : '' }}>
                                                {{ $provinsi->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_provinsi')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Kabupaten/Kota (Lokasi Kantor/Tempat Tugas)</label>
                                    <select name="id_kabupaten_kota" id="id_kabupaten_kota"
                                        class="form-select @error('id_kabupaten_kota') error @enderror">
                                        <option value="">Pilih Kabupaten/Kota</option>
                                        @if($isEdit && $kepegawaianData && $kepegawaianData->id_kabupaten_kota)
                                            @php
                                                $currentKabupatenId = $kepegawaianData->id_kabupaten_kota;
                                                $currentKabupaten = $kabupatenList->firstWhere('id', $currentKabupatenId);
                                            @endphp
                                            @if($currentKabupaten)
                                                <option value="{{ $currentKabupatenId }}" selected>
                                                    {{ $currentKabupaten->name }}
                                                </option>
                                            @endif
                                        @endif
                                    </select>
                                    @error('id_kabupaten_kota')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Alamat Kantor</label>
                                <textarea name="alamat_kantor" class="form-textarea @error('alamat_kantor') error @enderror"
                                    placeholder="Masukkan alamat lengkap kantor">{{ $kepegawaianData ? $kepegawaianData->alamat_kantor : old('alamat_kantor') }}</textarea>
                                @error('alamat_kantor')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Nomor Telepon Kantor</label>
                                    <input type="tel" name="nomor_telepon_kantor"
                                        class="form-input @error('nomor_telepon_kantor') error @enderror"
                                        value="{{ $kepegawaianData ? $kepegawaianData->nomor_telepon_kantor : old('nomor_telepon_kantor') }}"
                                        placeholder="(021) xxxxxx">
                                    @error('nomor_telepon_kantor')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Email Kantor</label>
                                    <input type="email" name="email_kantor"
                                        class="form-input @error('email_kantor') error @enderror"
                                        value="{{ $kepegawaianData ? $kepegawaianData->email_kantor : old('email_kantor') }}"
                                        placeholder="email@instansi.go.id">
                                    @error('email_kantor')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Nomor SK CPNS</label>
                                    <input type="text" name="nomor_sk_cpns"
                                        class="form-input @error('nomor_sk_cpns') error @enderror"
                                        value="{{ $kepegawaianData ? $kepegawaianData->nomor_sk_cpns : old('nomor_sk_cpns') }}"
                                        placeholder="Masukkan nomor SK CPNS">
                                    @error('nomor_sk_cpns')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Tanggal SK CPNS</label>
                                    <input type="date" name="tanggal_sk_cpns"
                                        class="form-input @error('tanggal_sk_cpns') error @enderror"
                                        value="{{ $kepegawaianData ? (is_string($kepegawaianData->tanggal_sk_cpns) ? \Carbon\Carbon::parse($kepegawaianData->tanggal_sk_cpns)->format('Y-m-d') : ($kepegawaianData->tanggal_sk_cpns ? $kepegawaianData->tanggal_sk_cpns->format('Y-m-d') : '')) : old('tanggal_sk_cpns') }}">
                                    @error('tanggal_sk_cpns')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            @if ($kunci_judul == true)
                                <div class="form-section-header">
                                    <i class="fas fa-lightbulb"></i>Aktualisasi
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Judul</label>
                                    <input type="text" name="judul"
                                        class="form-input @error('judul') error @enderror"
                                        value="{{ old('judul', optional($aksiPerubahan)->judul) }}"
                                        placeholder="Masukkan judul aktualisasi">
                                    @error('judul')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            @endif

                            <!-- Data Mentor -->
                            <div class="form-section-header">
                                <i class="fas fa-user-graduate"></i> Data Mentor
                            </div>

                            @php
                            $hasMentor = $mentorData ? true : false;
                            $selectedMentorId = $mentorData ? $mentorData->id_mentor : null;
                            $selectedMentorMode = $mentorData ? ($mentorData->mentor ? 'pilih' : 'tambah') : '';
                            @endphp

                            <div class="form-group">
                                <label class="form-label">Apakah sudah ada penunjukan Mentor?</label>
                                <select name="sudah_ada_mentor" id="sudah_ada_mentor"
                                    class="form-select @error('sudah_ada_mentor') error @enderror">
                                    <option value="">Pilih</option>
                                    <option value="Ya" {{ $hasMentor || old('sudah_ada_mentor') == 'Ya' ? 'selected' : '' }}>Ya</option>
                                    <option value="Tidak" {{ !$hasMentor && old('sudah_ada_mentor') == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                                </select>
                                @error('sudah_ada_mentor')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div id="mentor-container"
                                style="display: {{ $hasMentor || old('sudah_ada_mentor') == 'Ya' ? 'block' : 'none' }};">
                                <div class="form-group">
                                    <label class="form-label">Pilih Mentor atau Tambah Baru</label>
                                    <select name="mentor_mode" id="mentor_mode"
                                        class="form-select @error('mentor_mode') error @enderror">
                                        <option value="">Pilih Menu</option>
                                        <option value="pilih" {{ $selectedMentorMode == 'pilih' || old('mentor_mode') == 'pilih' ? 'selected' : '' }}>Daftar mentor</option>
                                        <option value="tambah" {{ $selectedMentorMode == 'tambah' || old('mentor_mode') == 'tambah' ? 'selected' : '' }}>Tambah mentor (Jika tidak ada di daftar mentor)</option>
                                    </select>
                                    @error('mentor_mode')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Form untuk memilih mentor yang sudah ada -->
                                <div id="select-mentor-form"
                                    style="display: {{ $selectedMentorMode == 'pilih' || old('mentor_mode') == 'pilih' ? 'block' : 'none' }};">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="fas fa-search"></i> Cari Mentor
                                        </label>
                                        <input type="text"
                                            id="mentor-search"
                                            class="form-input"
                                            placeholder="Cari berdasarkan nama atau NIP mentor..."
                                            autocomplete="off">
                                        <div class="form-hint" id="mentor-search-info" style="display:none;">
                                            <i class="fas fa-info-circle"></i> <span id="mentor-search-stats"></span>
                                        </div>
                                    </div>

                                    <div id="mentor-loading" style="display:none; padding: 10px; background: #fff3cd; border-radius: 6px; margin-bottom: 15px;">
                                        <i class="fas fa-spinner fa-spin"></i> Mencari mentor...
                                    </div>

                                    <div id="mentor-not-found" style="display:none; padding: 10px; background: #f8d7da; border-radius: 6px; margin-bottom: 15px; color: #721c24;">
                                        <i class="fas fa-exclamation-circle"></i> Tidak ada mentor yang sesuai dengan pencarian
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Pilih Mentor</label>
                                        <select name="id_mentor" id="id_mentor"
                                            class="form-select @error('id_mentor') error @enderror">
                                            <option value="">Pilih Mentor...</option>
                                            @foreach($mentorList as $mentor)
                                                <option value="{{ $mentor->id }}"
                                                    data-nama="{{ $mentor->nama_mentor }}"
                                                    data-nip="{{ $mentor->nip_mentor }}"
                                                    data-jabatan="{{ $mentor->jabatan_mentor }}"
                                                    data-golongan="{{ $mentor->golongan ?? '' }}"
                                                    data-pangkat="{{ $mentor->pangkat ?? '' }}"
                                                    data-nomor-rekening="{{ $mentor->nomor_rekening }}"
                                                    data-npwp="{{ $mentor->npwp_mentor }}"
                                                    data-nomor-hp="{{ $mentor->nomor_hp_mentor ?? '' }}"
                                                    {{ ($selectedMentorId == $mentor->id) || old('id_mentor') == $mentor->id ? 'selected' : '' }}>
                                                    {{ $mentor->nama_mentor }} - {{ $mentor->nip_mentor ?? 'Tanpa NIP' }} - {{ $mentor->jabatan_mentor }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_mentor')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group">
                                            <label class="form-label">Nama Mentor</label>
                                            <input type="text" name="nama_mentor" id="nama_mentor_select"
                                                class="form-input @error('nama_mentor') error @enderror"
                                                value="{{ $mentorData && $mentorData->mentor ? $mentorData->mentor->nama_mentor : old('nama_mentor') }}"
                                                readonly>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">NIP Mentor</label>
                                            <input type="text" name="nip_mentor" id="nip_mentor_select"
                                                class="form-input @error('nip_mentor') error @enderror"
                                                value="{{ $mentorData && $mentorData->mentor ? $mentorData->mentor->nip_mentor : old('nip_mentor') }}"
                                                readonly>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Jabatan Mentor</label>
                                            <input type="text" name="jabatan_mentor" id="jabatan_mentor_select"
                                                class="form-input @error('jabatan_mentor') error @enderror"
                                                value="{{ $mentorData && $mentorData->mentor ? $mentorData->mentor->jabatan_mentor : old('jabatan_mentor') }}"
                                                readonly>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group">
                                            <label class="form-label">Golongan Mentor</label>
                                            <input type="text" id="golongan_mentor_select" class="form-input" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Pangkat Mentor</label>
                                            <input type="text" id="pangkat_mentor_select" class="form-input" readonly>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group">
                                            <label class="form-label">Nama Bank dan Nomor Rekening Mentor</label>
                                            <input type="text" name="nomor_rekening_mentor" id="nomor_rekening_mentor_select"
                                                class="form-input @error('nomor_rekening_mentor') error @enderror"
                                                placeholder="Bank Mandiri, 174xxxxxxxxx a.n Nanang Wijaya"
                                                value="{{ $mentorData && $mentorData->mentor ? $mentorData->mentor->nomor_rekening : old('nomor_rekening_mentor') }}"
                                                readonly>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">NPWP Mentor</label>
                                            <input type="text" name="npwp_mentor" id="npwp_mentor_select"
                                                class="form-input @error('npwp_mentor') error @enderror"
                                                value="{{ $mentorData && $mentorData->mentor ? $mentorData->mentor->npwp_mentor : old('npwp_mentor') }}"
                                                readonly>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Nomor HP Mentor</label>
                                            <input type="text" name="nomor_hp_mentor" id="nomor_hp_mentor_select"
                                                class="form-input @error('nomor_hp_mentor') error @enderror"
                                                value="{{ $mentorData && $mentorData->mentor ? $mentorData->mentor->nomor_hp_mentor : old('nomor_hp_mentor') }}"
                                                readonly>
                                        </div>
                                    </div>
                                </div>

                                <!-- Form untuk menambah mentor baru -->
                                <div id="add-mentor-form"
                                    style="display: {{ $selectedMentorMode == 'tambah' || old('mentor_mode') == 'tambah' ? 'block' : 'none' }};">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i>
                                        Silakan lengkapi data mentor baru
                                    </div>

                                    @php
                                    $mentorBaruNama = $mentorData && !$mentorData->mentor ? $mentorData->nama_mentor_custom : old('nama_mentor_baru');
                                    $mentorBaruJabatan = $mentorData && !$mentorData->mentor ? $mentorData->jabatan_mentor_custom : old('jabatan_mentor_baru');
                                    @endphp

                                    <div class="form-row">
                                        <div class="form-group">
                                            <label class="form-label">Nama Mentor</label>
                                            <input type="text" name="nama_mentor_baru" id="nama_mentor_baru"
                                                class="form-input @error('nama_mentor_baru') error @enderror"
                                                value="{{ $mentorBaruNama }}" placeholder="Masukkan nama mentor">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">NIP Mentor</label>
                                            <input type="text" name="nip_mentor_baru" id="nip_mentor_baru"
                                                class="form-input @error('nip_mentor_baru') error @enderror"
                                                value="{{ old('nip_mentor_baru') }}" placeholder="Masukkan NIP mentor">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Jabatan Mentor</label>
                                            <input type="text" name="jabatan_mentor_baru" id="jabatan_mentor_baru"
                                                class="form-input @error('jabatan_mentor_baru') error @enderror"
                                                value="{{ $mentorBaruJabatan }}" placeholder="Masukkan jabatan mentor">
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group">
                                            <label class="form-label">Golongan Ruang Mentor</label>
                                            <select name="golongan_mentor_baru" id="golongan_mentor_baru"
                                                class="form-select @error('golongan_mentor_baru') error @enderror">
                                                <option value="">Pilih</option>
                                                <option value="II/a">II/a</option>
                                                <option value="II/b">II/b</option>
                                                <option value="II/c">II/c</option>
                                                <option value="II/d">II/d</option>
                                                <option value="III/a">III/a</option>
                                                <option value="III/b">III/b</option>
                                                <option value="III/c">III/c</option>
                                                <option value="III/d">III/d</option>
                                                <option value="IV/a">IV/a</option>
                                                <option value="IV/b">IV/b</option>
                                                <option value="IV/c">IV/c</option>
                                                <option value="IV/d">IV/d</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Pangkat Mentor</label>
                                            <input type="text" name="pangkat_mentor_baru" id="pangkat_mentor_baru"
                                                class="form-input" placeholder="Otomatis terisi" readonly>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group">
                                            <label class="form-label">Nomor HP/WhatsApp Mentor</label>
                                            <input type="tel" name="nomor_hp_mentor_baru" id="nomor_hp_mentor_baru"
                                                class="form-input @error('nomor_hp_mentor_baru') error @enderror"
                                                value="{{ old('nomor_hp_mentor_baru') }}" placeholder="0812xxxxxxxx">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Nama Bank dan Nomor Rekening Mentor</label>
                                            <input type="text" name="nomor_rekening_mentor_baru" id="nomor_rekening_mentor_baru"
                                                class="form-input @error('nomor_rekening_mentor_baru') error @enderror"
                                                placeholder="Bank Mandiri, 174xxxxxxxxx a.n Nanang Wijaya"
                                                value="{{ old('nomor_rekening_mentor_baru') }}">
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group">
                                            <label class="form-label">NPWP Mentor</label>
                                            <input type="text" name="npwp_mentor_baru" id="npwp_mentor_baru"
                                                class="form-input @error('npwp_mentor_baru') error @enderror"
                                                value="{{ old('npwp_mentor_baru') }}" placeholder="Masukkan NPWP mentor">
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
                            <p class="step-description">Unggah dokumen yang diperlukan (semua dokumen opsional)</p>
                            <div class="selected-info">
                                <div class="info-badge">
                                    <i class="fas fa-book"></i> <span id="current-training-name-3">{{ $jenisNama }}</span>
                                </div>
                                <div class="info-badge">
                                    <i class="fas fa-calendar-alt"></i> Angkatan: <span id="current-angkatan-name-3"></span>
                                </div>
                            </div>
                        </div>

                        @if(!$isEdit)
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                <strong>Informasi:</strong> Semua dokumen bersifat opsional dan dapat diunggah nanti setelah peserta terdaftar.
                            </div>
                        @endif

                        <div class="dynamic-form-container" id="dynamic-form-container-3">
                            <div class="form-section-header">
                                <i class="fas fa-file-upload"></i> Dokumen Pendukung
                            </div>

                            <!-- KTP -->
                            <div class="form-group">
                                <label class="form-label">Upload KTP</label>
                                <div class="form-hint">Format PDF/JPG/PNG, maksimal 1MB</div>
                                <div class="form-file">
                                    <input type="file" name="file_ktp" id="file_ktp"
                                        class="form-file-input @error('file_ktp') error @enderror"
                                        accept=".pdf,.jpg,.jpeg,.png">
                                    <label class="form-file-label" for="file_ktp">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>{{ $isEdit && $pesertaData && $pesertaData->file_ktp ? 'Ganti KTP' : 'Klik untuk mengunggah file KTP' }}</span>
                                    </label>
                                    <div class="form-file-name" id="fileKtpName">
                                        @if($isEdit && $pesertaData && $pesertaData->file_ktp)
                                            File sudah ada: {{ basename($pesertaData->file_ktp) }}
                                        @else
                                            Belum ada file dipilih
                                        @endif
                                    </div>
                                </div>
                                @error('file_ktp')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Pas Foto -->
                            <div class="form-group">
                                <label class="form-label">Upload Pasfoto peserta berwarna</label>
                                <div class="form-hint">Format JPG/PNG, maksimal 1MB.</div>
                                <div style="display: flex; gap: 20px; align-items: flex-start; flex-wrap: wrap; margin-bottom: 15px;">
                                    <div class="form-file" id="pasFotoUploadContainer" style="flex: 1; min-width: 300px;">
                                        <input type="file" name="file_pas_foto" id="file_pas_foto"
                                            class="form-file-input @error('file_pas_foto') error @enderror"
                                            accept=".jpg,.jpeg,.png" data-cropper="true">
                                        <label class="form-file-label" for="file_pas_foto">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <span>{{ $isEdit && $pesertaData && $pesertaData->file_pas_foto ? 'Ganti Pasfoto' : 'Klik untuk mengunggah file JPG/PNG (maks. 1MB)' }}</span>
                                        </label>
                                        <div class="form-file-name" id="filePasFotoName">
                                            @if($isEdit && $pesertaData && $pesertaData->file_pas_foto)
                                                File sudah ada: {{ basename($pesertaData->file_pas_foto) }}
                                            @else
                                                Belum ada file dipilih
                                            @endif
                                        </div>
                                    </div>
                                    <div style="text-align: center;">
                                        <p style="margin: 0 0 8px 0; font-size: 0.9em; color: #666;"><strong>Contoh Foto :</strong></p>
                                        <div style="width: 90px; height: 120px; border: 2px solid #ddd; overflow: hidden; border-radius: 4px;">
                                            <img src="{{ asset('gambar/contohfoto2.jpeg') }}" alt="Contoh Foto"
                                                style="width: 100%; height: 100%; object-fit: cover;"
                                                onerror="this.src='https://via.placeholder.com/90x120?text=Contoh+Foto'">
                                        </div>
                                    </div>
                                </div>
                                <div class="cropper-preview-container" id="cropperPreviewContainer" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="cropper-wrapper">
                                                <img id="imagePreview" src="" alt="Preview Image" style="max-width: 100%;">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="preview-wrapper">
                                                <div class="preview-title">Preview Pasfoto</div>
                                                <div class="preview-image-container">
                                                    <div id="preview" style="width: 150px; height: 200px; overflow: hidden; margin: 0 auto;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cropper-controls mt-3">
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="rotateLeft"><i class="fas fa-undo"></i> Putar Kiri</button>
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="rotateRight"><i class="fas fa-redo"></i> Putar Kanan</button>
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="zoomIn"><i class="fas fa-search-plus"></i> Zoom In</button>
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="zoomOut"><i class="fas fa-search-minus"></i> Zoom Out</button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" id="cancelCrop"><i class="fas fa-times"></i> Batal</button>
                                        <button type="button" class="btn btn-sm btn-success" id="cropImage"><i class="fas fa-crop"></i> Potong & Simpan</button>
                                    </div>
                                </div>
                                <canvas id="croppedCanvas" style="display: none;"></canvas>
                                <input type="hidden" name="cropped_image_data" id="croppedImageData">
                                @error('file_pas_foto')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- SK CPNS -->
                            <div class="form-group">
                                <label class="form-label">Unggah SK CPNS</label>
                                <div class="form-file">
                                    <input type="file" name="file_sk_cpns" id="file_sk_cpns"
                                        class="form-file-input @error('file_sk_cpns') error @enderror" accept=".pdf">
                                    <label class="form-file-label" for="file_sk_cpns">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>{{ $isEdit && $kepegawaianData && $kepegawaianData->file_sk_cpns ? 'Ganti SK CPNS' : 'Klik untuk mengunggah file PDF (maks. 1MB)' }}</span>
                                    </label>
                                    <div class="form-file-name" id="fileSkCpnsName">
                                        @if($isEdit && $kepegawaianData && $kepegawaianData->file_sk_cpns)
                                            File sudah ada: {{ basename($kepegawaianData->file_sk_cpns) }}
                                        @else
                                            Belum ada file dipilih
                                        @endif
                                    </div>
                                </div>
                                @error('file_sk_cpns')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- SPMT -->
                            <div class="form-group">
                                <label class="form-label">Unggah Surat Pernyataan Melaksanaan Tugas (SPMT)</label>
                                <div class="form-file">
                                    <input type="file" name="file_spmt" id="file_spmt"
                                        class="form-file-input @error('file_spmt') error @enderror" accept=".pdf">
                                    <label class="form-file-label" for="file_spmt">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>{{ $isEdit && $kepegawaianData && $kepegawaianData->file_spmt ? 'Ganti SPMT' : 'Klik untuk mengunggah file PDF (maks. 1MB)' }}</span>
                                    </label>
                                    <div class="form-file-name" id="fileSpmtName">
                                        @if($isEdit && $kepegawaianData && $kepegawaianData->file_spmt)
                                            File sudah ada: {{ basename($kepegawaianData->file_spmt) }}
                                        @else
                                            Belum ada file dipilih
                                        @endif
                                    </div>
                                </div>
                                @error('file_spmt')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- SKP -->
                            <div class="form-group">
                                <label class="form-label">Unggah SKP (Sasaran Kinerja Pegawai)</label>
                                <div class="form-file">
                                    <input type="file" name="file_skp" id="file_skp"
                                        class="form-file-input @error('file_skp') error @enderror" accept=".pdf">
                                    <label class="form-file-label" for="file_skp">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>{{ $isEdit && $kepegawaianData && $kepegawaianData->file_skp ? 'Ganti SKP' : 'Klik untuk mengunggah file PDF (maks. 1MB)' }}</span>
                                    </label>
                                    <div class="form-file-name" id="fileSkpName">
                                        @if($isEdit && $kepegawaianData && $kepegawaianData->file_skp)
                                            File sudah ada: {{ basename($kepegawaianData->file_skp) }}
                                        @else
                                            Belum ada file dipilih
                                        @endif
                                    </div>
                                </div>
                                @error('file_skp')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Surat Tugas -->
                            <div class="form-group">
                                <label class="form-label">Unggah Scan Surat Tugas mengikuti pelatihan yang ditandatangani oleh pejabat yang berwenang</label>
                                <div class="form-hint">jika belum maka WAJIB disertakan saat registrasi ulang di Puslatbang KMP</div>
                                <div class="form-file">
                                    <input type="file" name="file_surat_tugas" id="file_surat_tugas"
                                        class="form-file-input @error('file_surat_tugas') error @enderror" accept=".pdf">
                                    <label class="form-file-label" for="file_surat_tugas">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>{{ $isEdit && $pendaftaran->file_surat_tugas ? 'Ganti Surat Tugas' : 'Klik untuk mengunggah file PDF (maks. 1MB)' }}</span>
                                    </label>
                                    <div class="form-file-name" id="fileSuratTugasName">
                                        @if($isEdit && $pendaftaran->file_surat_tugas)
                                            File sudah ada: {{ basename($pendaftaran->file_surat_tugas) }}
                                        @else
                                            Belum ada file dipilih
                                        @endif
                                    </div>
                                </div>
                                @error('file_surat_tugas')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Surat Kesediaan -->
                            <div class="form-group">
                                <label class="form-label">Unggah Scan Formulir Kesediaan (file dapat diunduh di <a href="https://bit.ly/3VtcljN" target="_blank">Disini</a>)</label>
                                <div class="form-file">
                                    <input type="file" name="file_surat_kesediaan" id="file_surat_kesediaan"
                                        class="form-file-input @error('file_surat_kesediaan') error @enderror" accept=".pdf">
                                    <label class="form-file-label" for="file_surat_kesediaan">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>{{ $isEdit && $pendaftaran->file_surat_kesediaan ? 'Ganti Surat Kesediaan' : 'Klik untuk mengunggah file PDF (maks. 1MB)' }}</span>
                                    </label>
                                    <div class="form-file-name" id="fileSuratKesediaanName">
                                        @if($isEdit && $pendaftaran->file_surat_kesediaan)
                                            File sudah ada: {{ basename($pendaftaran->file_surat_kesediaan) }}
                                        @else
                                            Belum ada file dipilih
                                        @endif
                                    </div>
                                </div>
                                @error('file_surat_kesediaan')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Surat Sehat -->
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Unggah Surat Keterangan Berbadan Sehat</label>
                                    <div class="form-hint">Format PDF, maksimal 1MB</div>
                                    <div class="form-file">
                                        <input type="file" name="file_surat_sehat" id="file_surat_sehat"
                                            class="form-file-input @error('file_surat_sehat') error @enderror" accept=".pdf">
                                        <label class="form-file-label" for="file_surat_sehat">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <span>{{ $isEdit && $pendaftaran->file_surat_sehat ? 'Ganti Surat Sehat' : 'Klik untuk mengunggah file PDF (maks. 1MB)' }}</span>
                                        </label>
                                        <div class="form-file-name" id="fileSuratSehatName">
                                            @if($isEdit && $pendaftaran->file_surat_sehat)
                                                File sudah ada: {{ basename($pendaftaran->file_surat_sehat) }}
                                            @else
                                                Belum ada file dipilih
                                            @endif
                                        </div>
                                    </div>
                                    @error('file_surat_sehat')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <div class="step-navigation">
                            <button type="button" class="btn btn-secondary" id="back-to-step3">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </button>
                            <button type="submit" class="btn btn-success" id="submit-form">
                                <i class="fas fa-save"></i> {{ $isEdit ? 'Update Peserta' : 'Simpan Peserta' }}
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
    .form-hero { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; padding: 40px 0; margin-bottom: 40px; }
    .form-hero-content { text-align: center; max-width: 800px; margin: 0 auto; }
    .form-hero-title { font-size: 2rem; margin-bottom: 15px; font-weight: 700; }
    .form-hero-text { font-size: 1.1rem; opacity: 0.9; margin-bottom: 30px; line-height: 1.6; }
    .progress-indicator { display: flex; justify-content: center; gap: 40px; margin-top: 30px; }
    .progress-step { display: flex; flex-direction: column; align-items: center; gap: 10px; opacity: 0.5; transition: var(--transition); }
    .progress-step.active { opacity: 1; }
    .step-number { width: 40px; height: 40px; background-color: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; border: 2px solid transparent; }
    .progress-step.active .step-number { background-color: var(--accent-color); border-color: white; }
    .step-label { font-size: 0.9rem; font-weight: 500; }

    /* Form Section */
    .form-section { padding: 0 0 60px 0; }
    .form-wrapper { max-width: 1000px; margin: 0 auto; background: white; border-radius: 12px; box-shadow: var(--shadow); overflow: hidden; }
    .form-step { padding: 40px; display: none; }
    .form-step.active { display: block; animation: fadeIn 0.5s ease; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .step-header { text-align: center; margin-bottom: 40px; }
    .step-title { font-size: 1.8rem; color: var(--primary-color); margin-bottom: 10px; font-weight: 600; }
    .step-description { color: var(--gray-color); margin-bottom: 20px; }
    .selected-training { display: inline-flex; align-items: center; gap: 10px; background: rgba(72,187,120,0.1); color: var(--success-color); padding: 10px 20px; border-radius: 5px; margin-top: 15px; font-weight: 500; }

    /* Form Elements */
    .form-group { margin-bottom: 20px; }
    .form-label { display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark-color); }
    .form-label.required::after { content: " *"; color: var(--danger-color); }
    .form-select, .form-input, .form-textarea { width: 100%; padding: 10px 12px; border: 2px solid #e2e8f0; border-radius: 6px; font-size: 1rem; transition: var(--transition); background: white; }
    .form-select.error, .form-input.error, .form-textarea.error { border-color: var(--danger-color) !important; box-shadow: 0 0 0 3px rgba(245,101,101,0.1) !important; }
    .form-select:focus, .form-input:focus, .form-textarea:focus { outline: none; border-color: var(--accent-color); box-shadow: 0 0 0 3px rgba(66,153,225,0.1); }
    .form-textarea { min-height: 100px; resize: vertical; }
    .form-hint { font-size: 0.85rem; color: var(--gray-color); margin-top: 5px; font-style: italic; }
    .form-file { position: relative; overflow: hidden; }
    .form-file-input { position: absolute; width: 100%; height: 100%; opacity: 0; cursor: pointer; }
    .form-file-label { display: block; padding: 20px; background: var(--light-color); border: 2px dashed #cbd5e0; border-radius: 6px; text-align: center; cursor: pointer; transition: var(--transition); }
    .form-file-label:hover { border-color: var(--accent-color); background: rgba(66,153,225,0.05); }
    .form-file-name { margin-top: 10px; font-size: 0.9rem; color: var(--gray-color); }
    .text-danger { color: var(--danger-color) !important; font-size: 0.85rem; margin-top: 5px; display: block; }

    /* Angkatan Info */
    .angkatan-info { margin-top: 30px; }
    .info-card { background: linear-gradient(135deg, #f7fafc, #edf2f7); border-radius: 10px; padding: 20px; border-left: 4px solid var(--accent-color); }
    .info-card h4 { display: flex; align-items: center; gap: 10px; color: var(--primary-color); margin-bottom: 15px; font-size: 1.1rem; }
    .info-details { display: grid; gap: 10px; }
    .info-item { display: flex; justify-content: space-between; align-items: center; }
    .info-label { font-weight: 500; color: var(--dark-color); }
    .info-value { color: var(--gray-color); }
    .info-badge { display: inline-block; padding: 4px 12px; background: var(--success-color); color: white; border-radius: 20px; font-size: 0.85rem; font-weight: 500; }
    .selected-info { display: flex; gap: 15px; justify-content: center; margin-top: 20px; flex-wrap: wrap; }
    .selected-info .info-badge { background: var(--primary-color); display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; }
    .dynamic-form-container { margin-top: 30px; }
    .form-section-header { font-size: 1.4rem; color: var(--primary-color); margin: 40px 0 20px 0; padding-bottom: 10px; border-bottom: 2px solid #e2e8f0; font-weight: 600; display: flex; align-items: center; gap: 10px; }
    .form-section-header:first-child { margin-top: 0; }
    .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 20px; }
    .alert { padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; border: 1px solid transparent; display: flex; align-items: flex-start; gap: 10px; }
    .alert-info { background-color: rgba(66,153,225,0.1); border-color: var(--accent-color); color: var(--primary-color); }

    /* Buttons */
    .btn { padding: 12px 25px; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; transition: var(--transition); display: inline-flex; align-items: center; justify-content: center; gap: 8px; }
    .btn-primary { background: var(--accent-color); color: white; }
    .btn-primary:hover { background: var(--secondary-color); transform: translateY(-2px); }
    .btn-primary:disabled { background: #cbd5e0; cursor: not-allowed; transform: none; }
    .btn-secondary { background: #e2e8f0; color: var(--dark-color); }
    .btn-secondary:hover { background: #cbd5e0; }
    .btn-success { background: var(--success-color); color: white; }
    .btn-success:hover { background: #38a169; transform: translateY(-2px); }
    .step-navigation { display: flex; justify-content: space-between; margin-top: 40px; padding-top: 20px; border-top: 2px solid #e2e8f0; }
    .form-input[readonly], .form-select[disabled] { background-color: #e9ecef; opacity: 0.8; cursor: not-allowed; }

    /* ============================================================
       INSTANSI SEARCH — Custom Styles
       ============================================================ */
    .instansi-search-wrapper .form-input:focus {
        border-radius: 6px 6px 0 0;
    }
    #instansi_dropdown::-webkit-scrollbar { width: 6px; }
    #instansi_dropdown::-webkit-scrollbar-track { background: #f1f1f1; }
    #instansi_dropdown::-webkit-scrollbar-thumb { background: #cbd5e0; border-radius: 3px; }
    #instansi_dropdown::-webkit-scrollbar-thumb:hover { background: #a0aec0; }
    .instansi-item.active { background: #bee3f8 !important; }

    /* PIC Info */
    #info-pic-angkatan { display: flex; flex-direction: column; gap: 5px; }
    #info-pic-angkatan strong { color: var(--primary-color); font-size: 1rem; }
    #info-pic-angkatan small { color: var(--gray-color); font-size: 0.85rem; }
    #info-pic-angkatan small i { margin-right: 5px; color: var(--accent-color); }

    /* Responsive */
    @media (max-width: 768px) {
        .form-hero { padding: 30px 0; }
        .form-hero-title { font-size: 1.5rem; }
        .progress-indicator { gap: 20px; }
        .step-label { font-size: 0.8rem; }
        .form-step { padding: 20px; }
        .step-title { font-size: 1.5rem; }
        .form-row { grid-template-columns: 1fr; }
        .step-navigation { flex-direction: column; gap: 10px; }
        .step-navigation .btn { width: 100%; }
    }
</style>
@endsection
@section('scripts')
<script>
// ============================================================
// DATA INSTANSI — 641 instansi, embedded langsung
// ============================================================
const DAFTAR_INSTANSI = ["Kementerian Luar Negeri", "Kementerian Dalam Negeri", "Kementerian Pertahanan", "Kementerian Hukum dan Hak Asasi Manusia", "Kementerian Keuangan", "Kementerian Energi dan Sumber Daya Mineral", "Kementerian Perindustrian", "Kementerian Pertanian", "Kementerian Lingkungan Hidup dan Kehutanan", "Kementerian Perhubungan", "Kementerian Kelautan dan Perikanan", "Kementerian Pekerjaan Umum dan Perumahan Rakyat", "Kementerian Kesehatan", "Kementerian Pendidikan dan Kebudayaan", "Kementerian Sosial", "Kementerian Agama", "Kementerian Ketenagakerjaan", "Kementerian Sekretariat Negara", "Kejaksaan RI", "Markas Besar Tentara Nasional Indonesia (Mabes TNI)", "Kepolisian Negara Republik Indonesia (POLRI)", "Lembaga Administrasi Negara", "Arsip Nasional Republik Indonesia ", "Badan Kepegawaian Negara", "Perpustakaan Nasional RI", "Badan Pusat Statistik ", "Badan Standardisasi Nasional ", "Badan Pengawas Tenaga Nuklir ", "Badan Tenaga Nuklir Nasional ", "Badan Intelijen Negara", "Badan Siber dan Sandi Negara", "Badan Urusan Logistik", "Lembaga Penerbangan dan Antariksa Nasional", "Badan Informasi Geospasial", "Badan Pengawasan Keuangan dan Pembangunan", "Lembaga Ilmu Pengetahuan Indonesia", "Badan Pengkajian dan Penerapan Teknologi", "Badan Koordinasi Penanaman Modal", "Kementerian Agraria dan Tata Ruang/Badan Pertanahan Nasional", "Badan Pengawas Obat dan Makanan ", "Lembaga Informasi Nasional", "Lembaga Ketahanan Nasional RI", "Badan Pengembangan Kebudayaan dan Pariwisata", "Mahkamah Agung RI", "Pemerintah Provinsi Bali", "Pemerintah Provinsi Kep. Bangka Belitung", "Pemerintah Provinsi Banten", "Pemerintah Provinsi Bengkulu", "Pemerintah Provinsi Daerah Istimewa Yogyakarta", "Pemerintah Provinsi Daerah Khusus Ibukota Jakarta", "Pemerintah Provinsi Gorontalo", "Pemerintah Provinsi Jambi", "Pemerintah Provinsi Jawa Barat", "Pemerintah Provinsi Jawa Tengah", "Pemerintah Provinsi Jawa Timur", "Pemerintah Provinsi Kalimantan Barat", "Pemerintah Provinsi Kalimantan Selatan", "Pemerintah Provinsi Kalimantan Tengah", "Pemerintah Provinsi Kalimantan Timur", "Pemerintah Provinsi Lampung", "Pemerintah Provinsi Maluku", "Pemerintah Provinsi Maluku Utara", "Pemerintah Provinsi Aceh", "Pemerintah Provinsi NTB", "Pemerintah Provinsi NTT", "Pemerintah Provinsi Papua", "Pemerintah Provinsi Riau", "Pemerintah Provinsi Sulawesi Selatan", "Pemerintah Provinsi Sulawesi Tengah", "Pemerintah Provinsi Sulawesi Tenggara", "Pemerintah Provinsi Sumatera Barat", "Pemerintah Provinsi Sumatera Selatan", "Pemerintah Provinsi Sumatera Utara", "Pemerintah Provinsi Sulawesi Utara", "Badan Pemeriksa Keuangan ", "Radio Republik Indonesia", "Kementerian Perdagangan", "Badan Meteorologi Klimatologi dan Geofisika", "Kementerian Pemberdayaan Perempuan dan Perlindungan Anak", "Sekretariat Kabinet ", "Kementerian Komunikasi dan Informatika", "Setjen Komisi Nasional Hak Asasi Manusia", "Badan Narkotika Nasional", "Kementerian Pemuda dan Olahraga", "Kementerian Pariwisata", "Kementerian Koordinator Bidang Kemaritiman", "Pemerintah Provinsi Kepulauan Riau", "Pemerintah Provinsi Sulawesi Barat", "Pemerintah Provinsi Papua Barat", "Kementerian Pendayagunaan Aparatur Negara dan Reformasi Birokrasi", "Badan Pengawas Pasar modal dan Lembaga Keuangan", "Badan Pengkajian Ekonomi, Keuangan dan Kerjasama Internasional", "Badan Nasional Penanggulangan Bencana", "Televisi Republik Indonesia", "Sekretariat Jenderal Majelis Permusyawaratan Rakyat (MPR RI)", "Sekretariat Jenderal Dewan Perwakilan Rakyat (DPR RI)", "Kementerian Koordinator Bidang Politik Hukum dan Keamanan", "Kementerian Koordinator Bidang Perekonomian", "Kementerian Badan Usaha Milik Negara", "Kementerian Riset dan Teknologi / Badan Riset dan Inovasi", "Kementerian Koperasi dan Usaha Kecil dan Menengah", "Dewan Ketahanan Nasional", "Kementerian Perencanaan Pembangunan Nasional/Bappenas", "Badan Kependudukan dan Keluarga Berencana Nasional", "Setjen Komisi Pemilihan Umum", "Mahkamah Konstitusi", "Setjen Komisi Pemberantasan Korupsi", "Komisi Yudisial RI", "Badan Pelindungan Pekerja Migran Indonesia", "Lembaga Kebijakan Pengadaan Barang/Jasa Pemerintah", "Badan SAR Nasional", "Setjen Komisi Pengawas Persaingan Usaha", "Badan Pengawas Pemilihan Umum", "Markas Besar TNI Angkatan Laut", "Markas Besar TNI Anngkatan Darat", "Markas Besar TNI Angkatan Udara", "Pemerintah Provinsi Kalimantan Utara", "Kementerian Koordinator Bidang Pembangunan Manusia dan Kebudayaan", "Kementerian Desa, Pembangunan Daerah Tertinggal dan Transmigrasi", "Setjen Dewan Ketahanan Nasional (WANTANNAS)", "Badan Pengusahaan Kawasan Perdagangan Bebas dan Pelabuhan Bebas Batam", "Kepaniteraan dan Sekretariat Jenderal Mahkamah Konstitusi RI", "Sekretariat Jenderal Komisi Yudisial", "Setjen Dewan Perwakilan Daerah", "Badan Keamanan Laut RI", "Pusat Pelaporan dan Analisis Transaksi Keuangan", "Ombudsman Republik Indonesia", "Badan Nasional Penanggulangan Terorisme", "Komisi Aparatur Sipil Negara", "Badan Ekonomi Kreatif", "Pemerintah Kab. Aceh Besar", "Pemerintah Kab. Pidie", "Pemerintah Kab. Aceh Utara", "Pemerintah Kab. Aceh Timur", "Pemerintah Kab. Aceh Selatan", "Pemerintah Kab. Aceh Barat", "Pemerintah Kab. Aceh Tengah", "Pemerintah Kab. Aceh Tenggara", "Pemerintah Kab. Simeulue", "Pemerintah Kab. Bireuen", "Pemerintah Kab. Aceh Singkil", "Pemerintah Kab. Aceh Barat Daya", "Pemerintah Kab. Gayo Lues", "Pemerintah Kab. Aceh Tamiang", "Pemerintah Kab. Nagan Raya", "Pemerintah Kab. Aceh Jaya", "Pemerintah Kab. Bener Meriah", "Pemerintah Kab. Pidie Jaya", "Pemerintah Kota Sabang", "Pemerintah Kota Banda Aceh", "Pemerintah Kota Langsa", "Pemerintah Kota Lhokseumawe", "Pemerintah Kota Subulussalam", "Pemerintah Kab. Deli Serdang", "Pemerintah Kab. Karo", "Pemerintah Kab. Langkat", "Pemerintah Kab. Tapanuli Tengah", "Pemerintah Kab. Simalungun", "Pemerintah Kab. Labuhanbatu", "Pemerintah Kab. Dairi", "Pemerintah Kab. Tapanuli Utara", "Pemerintah Kab. Tapanuli Selatan", "Pemerintah Kab. Asahan", "Pemerintah Kab. Nias", "Pemerintah Kab. Toba Samosir", "Pemerintah Kab. Mandailing Natal", "Pemerintah Kab. Nias Selatan", "Pemerintah Kab. Humbang Hasundutan", "Pemerintah Kab. Pakpak Bharat", "Pemerintah Kab. Samosir", "Pemerintah Kab. Serdang Bedagai", "Pemerintah Kab. Padang Lawas", "Pemerintah Kab. Padang Lawas Utara", "Pemerintah Kab. Batubara", "Pemerintah Kab. Labuhanbatu Selatan", "Pemerintah Kab. Labuhanbatu Utara", "Pemerintah Kab. Nias Barat", "Pemerintah Kab. Nias Utara", "Pemerintah Kota Medan", "Pemerintah Kota Tebing Tinggi", "Pemerintah Kota Binjai", "Pemerintah Kota Pematangsiantar", "Pemerintah Kota Tanjungbalai", "Pemerintah Kota Sibolga", "Pemerintah Kota Padangsidimpuan", "Pemerintah Kota Gunung Sitoli", "Pemerintah Kab. Kampar", "Pemerintah Kab. Bengkalis", "Pemerintah Kab. Indragiri Hulu", "Pemerintah Kab. Indragiri Hilir", "Pemerintah Kab. Pelalawan", "Pemerintah Kab. Rokan Hulu", "Pemerintah Kab. Rokan Hilir", "Pemerintah Kab. Siak", "Pemerintah Kab. Kuantan Singingi", "Pemerintah Kab. Kepulauan Meranti", "Pemerintah Kota Pekanbaru", "Pemerintah Kota Dumai", "Pemerintah Kab. Agam", "Pemerintah Kab. Pasaman", "Pemerintah Kab. Limapuluh Kota", "Pemerintah Kab. Solok", "Pemerintah Kab. Padang Pariaman", "Pemerintah Kab. Pesisir Selatan", "Pemerintah Kab. Tanah Datar", "Pemerintah Kab. Sijunjung", "Pemerintah Kab. Kep. Mentawai", "Pemerintah Kab. Solok Selatan", "Pemerintah Kab. Dharmasraya", "Pemerintah Kab. Pasaman Barat", "Pemerintah Kota Bukittinggi", "Pemerintah Kota Padang Panjang", "Pemerintah Kota Sawahlunto", "Pemerintah Kota Solok", "Pemerintah Kota Padang", "Pemerintah Kota Payakumbuh", "Pemerintah Kota Pariaman", "Pemerintah Kab. Batang Hari", "Pemerintah Kab. Tanjung Jabung Barat", "Pemerintah Kab. Bungo", "Pemerintah Kab. Merangin", "Pemerintah Kab. Kerinci", "Pemerintah Kab. Sarolangun", "Pemerintah Kab. Tebo", "Pemerintah Kab. Muaro Jambi", "Pemerintah Kab. Tanjung Jabung Timur", "Pemerintah Kota Jambi", "Pemerintah Kota Sungai Penuh", "Pemerintah Kab. Musi Banyuasin", "Pemerintah Kab. Ogan Komering Ulu", "Pemerintah Kab. Muara Enim", "Pemerintah Kab. Lahat", "Pemerintah Kab. Musi Rawas", "Pemerintah Kab. Ogan Komering Ilir", "Pemerintah Kab. Banyuasin", "Pemerintah Kab. Ogan Komering Ulu Timur", "Pemerintah Kab. Ogan Komering Ulu Sel.", "Pemerintah Kab. Ogan Ilir", "Pemerintah Kab. Empat Lawang", "Pemerintah Kab. Musi Rawas Utara", "Pemerintah Kota Palembang", "Pemerintah Kota Pagar Alam", "Pemerintah Kota Lubuk Linggau", "Pemerintah Kota Prabumulih", "Pemerintah Kab. Penukal Abab Lematang Ilir", "Pemerintah Kab. Bangka", "Pemerintah Kab. Belitung", "Pemerintah Kab. Bangka Barat", "Pemerintah Kab. Bangka Tengah", "Pemerintah Kab. Bangka Selatan", "Pemerintah Kab. Belitung Timur", "Pemerintah Kota Pangkal Pinang", "Pemerintah Kab. Bengkulu Utara", "Pemerintah Kab. Bengkulu Selatan", "Pemerintah Kab. Rejang Lebong", "Pemerintah Kab. Kaur", "Pemerintah Kab. Seluma", "Pemerintah Kab. Mukomuko", "Pemerintah Kab. Kepahiang", "Pemerintah Kab. Lebong", "Pemerintah Kab. Bengkulu Tengah", "Pemerintah Kota Bengkulu", "Pemerintah Kab. Lampung Selatan", "Pemerintah Kab. Lampung Tengah", "Pemerintah Kab. Lampung Utara", "Pemerintah Kab. Lampung Barat", "Pemerintah Kab. Tulang Bawang", "Pemerintah Kab. Tanggamus", "Pemerintah Kab. Way Kanan", "Pemerintah Kab. Lampung Timur", "Pemerintah Kab. Pesawaran", "Pemerintah Kab. Tulang Bawang Barat", "Pemerintah Kab. Pringsewu", "Pemerintah Kab. Mesuji", "Pemerintah Kab. Pesisir Barat", "Pemerintah Kota Metro", "Pemerintah Kota Bandar Lampung", "Pemerintah Kab. Bogor", "Pemerintah Kab. Sukabumi", "Pemerintah Kab. Cianjur", "Pemerintah Kab. Bekasi", "Pemerintah Kab. Karawang", "Pemerintah Kab. Purwakarta", "Pemerintah Kab. Subang", "Pemerintah Kab. Bandung", "Pemerintah Kab. Sumedang", "Pemerintah Kab. Garut", "Pemerintah Kab. Tasikmalaya", "Pemerintah Kab. Ciamis", "Pemerintah Kab. Cirebon", "Pemerintah Kab. Kuningan", "Pemerintah Kab. Indramayu", "Pemerintah Kab. Majalengka", "Pemerintah Kab. Bandung Barat", "Pemerintah Kab. Pangandaran", "Pemerintah Kota Bandung", "Pemerintah Kota Bogor", "Pemerintah Kota Sukabumi", "Pemerintah Kota Cirebon", "Pemerintah Kota Bekasi", "Pemerintah Kota Depok", "Pemerintah Kota Cimahi", "Pemerintah Kota Tasikmalaya", "Pemerintah Kota Banjar", "Pemerintah Kab. Serang", "Pemerintah Kab. Pandeglang", "Pemerintah Kab. Lebak", "Pemerintah Kab. Tangerang", "Pemerintah Kota Tangerang", "Pemerintah Kota Cilegon", "Pemerintah Kota Serang", "Pemerintah Kota Tangerang Selatan", "Pemerintah Kab. Bantul", "Pemerintah Kab. Sleman", "Pemerintah Kab. Gunung Kidul", "Pemerintah Kab. Kulon Progo", "Pemerintah Kota Yogyakarta", "Pemerintah Kab. Semarang", "Pemerintah Kab. Kendal", "Pemerintah Kab. Demak", "Pemerintah Kab. Grobogan", "Pemerintah Kab. Pekalongan", "Pemerintah Kab. Batang", "Pemerintah Kab. Tegal", "Pemerintah Kab. Brebes", "Pemerintah Kab. Pati", "Pemerintah Kab. Kudus", "Pemerintah Kab. Pemalang", "Pemerintah Kab. Jepara", "Pemerintah Kab. Rembang", "Pemerintah Kab. Blora", "Pemerintah Kab. Banyumas", "Pemerintah Kab. Cilacap", "Pemerintah Kab. Purbalingga", "Pemerintah Kab. Banjarnegara", "Pemerintah Kab. Magelang", "Pemerintah Kab. Temanggung", "Pemerintah Kab. Wonosobo", "Pemerintah Kab. Purworejo", "Pemerintah Kab. Kebumen", "Pemerintah Kab. Klaten", "Pemerintah Kab. Boyolali", "Pemerintah Kab. Sragen", "Pemerintah Kab. Sukoharjo", "Pemerintah Kab. Karanganyar", "Pemerintah Kab. Wonogiri", "Pemerintah Kota Semarang", "Pemerintah Kota Salatiga", "Pemerintah Kota Pekalongan", "Pemerintah Kota Tegal", "Pemerintah Kota Magelang", "Pemerintah Kota Surakarta", "Pemerintah Kab. Gresik", "Pemerintah Kab. Mojokerto", "Pemerintah Kab. Sidoarjo", "Pemerintah Kab. Jombang", "Pemerintah Kab. Sampang", "Pemerintah Kab. Pamekasan", "Pemerintah Kab. Sumenep", "Pemerintah Kab. Bangkalan", "Pemerintah Kab. Bondowoso", "Pemerintah Kab. Situbondo", "Pemerintah Kab. Banyuwangi", "Pemerintah Kab. Jember", "Pemerintah Kab. Malang", "Pemerintah Kab. Pasuruan", "Pemerintah Kab. Probolinggo", "Pemerintah Kab. Lumajang", "Pemerintah Kab. Kediri", "Pemerintah Kab. Tulungagung", "Pemerintah Kab. Nganjuk", "Pemerintah Kab. Trenggalek", "Pemerintah Kab. Blitar", "Pemerintah Kab. Madiun", "Pemerintah Kab. Ngawi", "Pemerintah Kab. Magetan", "Pemerintah Kab. Ponorogo", "Pemerintah Kab. Pacitan", "Pemerintah Kab. Bojonegoro", "Pemerintah Kab. Tuban", "Pemerintah Kab. Lamongan", "Pemerintah Kota Surabaya", "Pemerintah Kota Mojokerto", "Pemerintah Kota Malang", "Pemerintah Kota Pasuruan", "Pemerintah Kota Probolinggo", "Pemerintah Kota Blitar", "Pemerintah Kota Kediri", "Pemerintah Kota Madiun", "Pemerintah Kota Batu", "Pemerintah Kab. Sambas", "Pemerintah Kab. Sanggau", "Pemerintah Kab. Sintang", "Pemerintah Kab. Mempawah", "Pemerintah Kab. Kapuas Hulu", "Pemerintah Kab. Ketapang", "Pemerintah Kab. Bengkayang", "Pemerintah Kab. Landak", "Pemerintah Kab. Melawi", "Pemerintah Kab. Sekadau", "Pemerintah Kab. Kubu Raya", "Pemerintah Kab. Kayong Utara", "Pemerintah Kota Pontianak", "Pemerintah Kota Singkawang", "Pemerintah Kab. Kapuas", "Pemerintah Kab. Barito Utara", "Pemerintah Kab. Barito Selatan", "Pemerintah Kab. Kotawaringin Timur", "Pemerintah Kab. Kotawaringin Barat", "Pemerintah Kab. Pulang Pisau", "Pemerintah Kab. Gunung Mas", "Pemerintah Kab. Lamandau", "Pemerintah Kab. Sukamara", "Pemerintah Kab. Murung Raya", "Pemerintah Kab. Katingan", "Pemerintah Kab. Seruyan", "Pemerintah Kab. Barito Timur", "Pemerintah Kota Palangka Raya", "Pemerintah Kab. Banjar", "Pemerintah Kab. Tanah Laut", "Pemerintah Kab. Tapin", "Pemerintah Kab. Hulu Sungai Selatan", "Pemerintah Kab. Hulu Sungai Tengah", "Pemerintah Kab. Barito Kuala", "Pemerintah Kab. Tabalong", "Pemerintah Kab. Kotabaru", "Pemerintah Kab. Hulu Sungai Utara", "Pemerintah Kab. Tanah Bumbu", "Pemerintah Kab. Balangan", "Pemerintah Kota Banjarmasin", "Pemerintah Kota Banjarbaru", "Pemerintah Kab. Kutai Kartanegara", "Pemerintah Kab. Paser", "Pemerintah Kab. Berau", "Pemerintah Kab. Kutai Barat", "Pemerintah Kab. Kutai Timur", "Pemerintah Kab. Penajam Paser Utara", "Pemerintah Kab. Mahakam Ulu", "Pemerintah Kota Samarinda", "Pemerintah Kota Balikpapan", "Pemerintah Kota Bontang", "Pemerintah Kab. Minahasa", "Pemerintah Kab. Bolaang Mongondow", "Pemerintah Kab. Kepulauan Sangihe", "Pemerintah Kab. Minahasa Selatan", "Pemerintah Kab. Kepulauan Talaud", "Pemerintah Kab. Minahasa Utara", "Pemerintah Kab. Bolaang Mongondow Utara", "Pemerintah Kab. Siau Tagulandang Biaro", "Pemerintah Kab. Minahasa Tenggara", "Pemerintah Kab. Bolaang Mongondow Selatan", "Pemerintah Kab. Bolaang Mongondow Timur", "Pemerintah Kota Manado", "Pemerintah Kota Bitung", "Pemerintah Kota Tomohon", "Pemerintah Kota KotaMobagu", "Pemerintah Kab. Gorontalo", "Pemerintah Kab. Boalemo", "Pemerintah Kab. Pohuwato", "Pemerintah Kab. Bone Bolango", "Pemerintah Kab. Gorontalo Utara", "Pemerintah Kota Gorontalo", "Pemerintah Kab. Poso", "Pemerintah Kab. Donggala", "Pemerintah Kab. Tolitoli", "Pemerintah Kab. Banggai", "Pemerintah Kab. Buol", "Pemerintah Kab. Morowali", "Pemerintah Kab. Banggai Kepulauan", "Pemerintah Kab. Parigi Moutong", "Pemerintah Kab. Tojo Una Una", "Pemerintah Kab. Sigi", "Pemerintah Kab. Banggai Laut", "Pemerintah Kab. Morowali Utara", "Pemerintah Kota Palu", "Pemerintah Kab. Pinrang", "Pemerintah Kab. Gowa", "Pemerintah Kab. Wajo", "Pemerintah Kab. Bone", "Pemerintah Kab. Tana Toraja", "Pemerintah Kab. Maros", "Pemerintah Kab. Luwu", "Pemerintah Kab. Sinjai", "Pemerintah Kab. Bulukumba", "Pemerintah Kab. Bantaeng", "Pemerintah Kab. Jeneponto", "Pemerintah Kab. Kepulauan Selayar", "Pemerintah Kab. Takalar", "Pemerintah Kab. Barru", "Pemerintah Kab. Sidenreng Rappang", "Pemerintah Kab. Pangkajene dan Kepulauan", "Pemerintah Kab. Soppeng", "Pemerintah Kab. Enrekang", "Pemerintah Kab. Luwu Utara", "Pemerintah Kab. Luwu Timur", "Pemerintah Kab. Toraja Utara", "Pemerintah Kota Makassar", "Pemerintah Kota Parepare", "Pemerintah Kota Palopo", "Pemerintah Kab. Konawe", "Pemerintah Kab. Buton", "Pemerintah Kab. Muna", "Pemerintah Kab. Kolaka", "Pemerintah Kab. Konawe Selatan", "Pemerintah Kab. Kolaka Utara", "Pemerintah Kab. Bombana", "Pemerintah Kab. Wakatobi", "Pemerintah Kab. Buton Utara", "Pemerintah Kab. Konawe Utara", "Pemerintah Kab. Kolaka Timur", "Pemerintah Kab. Konawe Kepulauan", "Pemerintah Kab. Buton Selatan", "Pemerintah Kab. Buton Tengah", "Pemerintah Kab. Muna Barat", "Pemerintah Kota Kendari", "Pemerintah Kota Baubau", "Pemerintah Kab. Buleleng", "Pemerintah Kab. Jembrana", "Pemerintah Kab. Klungkung", "Pemerintah Kab. Gianyar", "Pemerintah Kab. Karangasem", "Pemerintah Kab. Bangli", "Pemerintah Kab. Badung", "Pemerintah Kab. Tabanan", "Pemerintah Kota Denpasar", "Pemerintah Kab. Lombok Barat", "Pemerintah Kab. Lombok Tengah", "Pemerintah Kab. Lombok Timur", "Pemerintah Kab. Bima", "Pemerintah Kab. Sumbawa", "Pemerintah Kab. Dompu", "Pemerintah Kab. Sumbawa Barat", "Pemerintah Kab. Lombok Utara", "Pemerintah Kota Mataram", "Pemerintah Kota Bima", "Pemerintah Kab. Kupang", "Pemerintah Kab. Belu", "Pemerintah Kab. Timor Tengah Utara", "Pemerintah Kab. Timor Tengah Selatan", "Pemerintah Kab. Alor", "Pemerintah Kab. Sikka", "Pemerintah Kab. Flores Timur", "Pemerintah Kab. Ende", "Pemerintah Kab. Ngada", "Pemerintah Kab. Manggarai", "Pemerintah Kab. Sumba Timur", "Pemerintah Kab. Sumba Barat", "Pemerintah Kab. Lembata", "Pemerintah Kab. Rote Ndao", "Pemerintah Kab. Manggarai Barat", "Pemerintah Kab. Manggarai Timur", "Pemerintah Kab. Sumba Barat Daya", "Pemerintah Kab. Nagekeo", "Pemerintah Kab. Sumba Tengah", "Pemerintah Kab. Sabu Raijua", "Pemerintah Kab. Malaka", "Pemerintah Kota Kupang", "Pemerintah Kab. Maluku Tengah", "Pemerintah Kab. Maluku Tenggara", "Pemerintah Kab. Buru", "Pemerintah Kab. Maluku Tenggara Barat", "Pemerintah Kab. Kepulauan Aru", "Pemerintah Kab. Seram Bagian Barat", "Pemerintah Kab. Seram Bagian Timur", "Pemerintah Kab. Buru Selatan", "Pemerintah Kab. Maluku Barat Daya", "Pemerintah Kota Ambon", "Pemerintah Kota Tual", "Pemerintah Kab. Halmahera Barat", "Pemerintah Kab. Halmahera Tengah", "Pemerintah Kab. Kepulauan Sula", "Pemerintah Kab. Halmahera Selatan", "Pemerintah Kab. Halmahera Utara", "Pemerintah Kab. Halmahera Timur", "Pemerintah Kab. Pulau Morotai", "Pemerintah Kab. Pulau Taliabu", "Pemerintah Kota Ternate", "Pemerintah Kota Tidore Kepulauan", "Pemerintah Kab. Jayapura", "Pemerintah Kab. Biak Numfor", "Pemerintah Kab. Kepulauan Yapen ", "Pemerintah Kab. Merauke", "Pemerintah Kab. Jayawijaya", "Pemerintah Kab. Nabire", "Pemerintah Kab. Puncak Jaya", "Pemerintah Kab. Paniai", "Pemerintah Kab. Mimika", "Pemerintah Kab. Boven Digoel", "Pemerintah Kab. Mappi", "Pemerintah Kab. Asmat", "Pemerintah Kab. Yahukimo", "Pemerintah Kab. Pegunungan Bintang", "Pemerintah Kab. Tolikara", "Pemerintah Kab. Sarmi", "Pemerintah Kab. Keerom", "Pemerintah Kab. Waropen", "Pemerintah Kab. Supiori", "Pemerintah Kab. Mamberamo Raya", "Pemerintah Kab. Mamberamo Tengah", "Pemerintah Kab. Lanny Jaya", "Pemerintah Kab. Yalimo", "Pemerintah Kab. Nduga", "Pemerintah Kab. Dogiyai", "Pemerintah Kab. Puncak", "Pemerintah Kab. Deiyai", "Pemerintah Kab. Intan Jaya", "Pemerintah Kota Jayapura", "Pemerintah Kab. Bintan", "Pemerintah Kab. Karimun", "Pemerintah Kab. Natuna", "Pemerintah Kab. Lingga", "Pemerintah Kab. Kepulauan Anambas", "Pemerintah Kota Batam", "Pemerintah Kota Tanjungpinang", "Pemerintah Kab. Sorong", "Pemerintah Kab. Sorong Selatan", "Pemerintah Kab. Raja Ampat", "Pemerintah Kab. Manokwari", "Pemerintah Kab. Teluk Bintuni", "Pemerintah Kab. Teluk Wondama", "Pemerintah Kab. Fak-Fak", "Pemerintah Kab. Kaimana", "Pemerintah Kab. Tambrauw", "Pemerintah Kab. Maybrat", "Pemerintah Kab. Pegunungan Arfak", "Pemerintah Kab. Manokwari Selatan", "Pemerintah Kota Sorong", "Pemerintah Kab. Mamuju Utara", "Pemerintah Kab. Mamuju", "Pemerintah Kab. Mamasa", "Pemerintah Kab. Polewali Mandar", "Pemerintah Kab. Majene", "Pemerintah Kab. Mamuju Tengah", "Pemerintah Kab. Bulungan", "Pemerintah Kab. Malinau", "Pemerintah Kab. Nunukan", "Pemerintah Kab. Tana Tidung", "Pemerintah Kota Tarakan", "Lembaga Perlindungan Saksi dan Korban", "Badan Pembinaan Ideologi Pancasila", "Markas Besar TNI"];

// ============================================================
// INSTANSI SEARCHABLE SELECT — tampil semua, filter real-time
// ============================================================
(function () {
    const trigger      = document.getElementById('instansi_trigger');
    const triggerLabel = document.getElementById('instansi_trigger_label');
    const hiddenInput  = document.getElementById('asal_instansi_hidden');
    const dropdown     = document.getElementById('instansi_dropdown');
    const searchInput  = document.getElementById('asal_instansi_search');
    const listEl       = document.getElementById('instansi_list');
    const countEl      = document.getElementById('instansi_count');
    const clearBtn     = document.getElementById('instansi_clear_btn');
    const chevron      = document.getElementById('instansi_chevron');

    if (!trigger || !hiddenInput || !dropdown || !listEl) return;

    let selectedValue = hiddenInput.value || '';
    let isOpen = false;
    let debounceTimer = null;

    // ---- INIT: set label warna jika sudah ada nilai ----
    if (selectedValue) {
        setTriggerSelected(selectedValue);
    }

    // ---- BUKA / TUTUP dropdown saat klik trigger ----
    trigger.addEventListener('click', function (e) {
        if (e.target.closest('#instansi_clear_btn')) return; // jangan buka jika klik clear
        toggleDropdown();
    });

    function toggleDropdown() {
        if (isOpen) closeDropdown();
        else openDropdown();
    }

    function openDropdown() {
        isOpen = true;
        dropdown.style.display = 'block';
        trigger.style.borderRadius = '6px 6px 0 0';
        trigger.style.borderColor = '#4299e1';
        if (chevron) chevron.style.transform = 'rotate(180deg)';
        // Render semua instansi saat pertama buka
        renderList(DAFTAR_INSTANSI, '');
        // Fokus ke search box
        setTimeout(() => { if (searchInput) searchInput.focus(); }, 50);
    }

    function closeDropdown() {
        isOpen = false;
        dropdown.style.display = 'none';
        trigger.style.borderRadius = '6px';
        trigger.style.borderColor = selectedValue ? '#4299e1' : '';
        if (chevron) chevron.style.transform = 'rotate(0deg)';
        if (searchInput) searchInput.value = '';
        if (countEl) countEl.textContent = `Menampilkan ${DAFTAR_INSTANSI.length} instansi`;
    }

    // ---- FILTER saat mengetik di search ----
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                const q = this.value.trim();
                const filtered = q
                    ? DAFTAR_INSTANSI.filter(i => i.toLowerCase().includes(q.toLowerCase()))
                    : DAFTAR_INSTANSI;
                renderList(filtered, q);
            }, 150);
        });

        // Keyboard nav di search input
        searchInput.addEventListener('keydown', function (e) {
            const items = listEl.querySelectorAll('.instansi-item');
            const active = listEl.querySelector('.instansi-item.iactive');
            let idx = active ? [...items].indexOf(active) : -1;
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                idx = Math.min(idx + 1, items.length - 1);
                setActive(items, idx);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                idx = Math.max(idx - 1, 0);
                setActive(items, idx);
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (active) selectInstansi(active.dataset.value);
            } else if (e.key === 'Escape') {
                closeDropdown();
            }
        });

        // Cegah klik di dalam search menutup dropdown
        searchInput.addEventListener('click', function (e) { e.stopPropagation(); });
    }

    function setActive(items, idx) {
        items.forEach(el => { el.classList.remove('iactive'); el.style.background = ''; });
        if (items[idx]) {
            items[idx].classList.add('iactive');
            items[idx].style.background = '#bee3f8';
            items[idx].scrollIntoView({ block: 'nearest' });
        }
    }

    // ---- RENDER LIST ----
    function renderList(data, q) {
        if (!listEl) return;

        if (data.length === 0) {
            listEl.innerHTML = `<div style="padding:16px;text-align:center;color:#718096;font-size:0.9rem;">
                <i class="fas fa-search" style="font-size:1.5rem;margin-bottom:8px;display:block;"></i>
                Tidak ditemukan instansi untuk "<strong>${escapeHtml(q)}</strong>"
            </div>`;
            if (countEl) countEl.textContent = '0 instansi ditemukan';
            return;
        }

        const regex = q ? new RegExp(`(${escapeRegex(q)})`, 'gi') : null;

        // Render semua sekaligus — 641 item ringan untuk DOM modern
        listEl.innerHTML = data.map((item, i) => {
            const hl = regex
                ? item.replace(regex, '<mark style="background:#fefcbf;padding:0;border-radius:2px;">$1</mark>')
                : escapeHtml(item);
            const isSelected = item === selectedValue;
            return `<div class="instansi-item${isSelected ? ' iactive' : ''}" data-value="${escapeHtml(item)}"
                style="padding:9px 14px;cursor:pointer;font-size:0.88rem;border-bottom:1px solid #f7fafc;
                       display:flex;align-items:center;gap:8px;
                       background:${isSelected ? '#ebf8ff' : ''};
                       transition:background 0.1s;"
                onmouseover="this.style.background='#ebf8ff';"
                onmouseout="this.style.background='${isSelected ? '#ebf8ff' : ''}';">
                <i class="fas fa-${isSelected ? 'check-circle' : 'building'}"
                   style="color:${isSelected ? '#48bb78' : '#4299e1'};font-size:0.8rem;flex-shrink:0;"></i>
                <span>${hl}</span>
            </div>`;
        }).join('');

        if (countEl) {
            countEl.textContent = q
                ? `${data.length} dari ${DAFTAR_INSTANSI.length} instansi`
                : `Menampilkan ${data.length} instansi`;
        }

        // Scroll ke item yang dipilih
        const activeEl = listEl.querySelector('.iactive');
        if (activeEl) setTimeout(() => activeEl.scrollIntoView({ block: 'nearest' }), 10);

        // Attach click events
        listEl.querySelectorAll('.instansi-item').forEach(el => {
            el.addEventListener('click', () => selectInstansi(el.dataset.value));
        });
    }

    // ---- SELECT ----
    function selectInstansi(value) {
        selectedValue     = value;
        hiddenInput.value = value;
        setTriggerSelected(value);
        closeDropdown();
        trigger.classList.remove('error');
    }

    function setTriggerSelected(value) {
        if (!triggerLabel) return;
        triggerLabel.textContent = value;
        triggerLabel.style.color = '#2d3748';
        if (clearBtn) clearBtn.style.display = 'flex';
        if (trigger) trigger.style.borderColor = '#4299e1';
    }

    // ---- CLEAR ----
    if (clearBtn) {
        clearBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            selectedValue = '';
            hiddenInput.value = '';
            triggerLabel.textContent = 'Pilih asal instansi...';
            triggerLabel.style.color = '#718096';
            clearBtn.style.display = 'none';
            trigger.style.borderColor = '';
            closeDropdown();
        });
    }

    // ---- TUTUP jika klik di luar ----
    document.addEventListener('click', function (e) {
        if (!trigger.contains(e.target) && !dropdown.contains(e.target)) {
            if (isOpen) closeDropdown();
        }
    });

    function escapeHtml(s) { return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }
    function escapeRegex(s) { return s.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); }
})();

// ============================================================
// SWAP NDH
// ============================================================
@if($isEdit && $pesertaData && $pesertaData->ndh)
(function() {
    const swapSelect    = document.getElementById('swapNdhSelect');
    const angkatanSel   = document.getElementById('id_angkatan');
    const currentPesertaId = @json($pesertaData->id);
    const currentNdh       = @json($pesertaData->ndh);
    const currentNama      = @json($pesertaData->nama_lengkap);

    if (angkatanSel) {
        angkatanSel.addEventListener('change', loadSwapOptions);
        if (angkatanSel.value) setTimeout(loadSwapOptions, 500);
    }

    async function loadSwapOptions() {
        const angkatanId = angkatanSel.value;
        if (!angkatanId || !swapSelect) return;
        swapSelect.innerHTML = '<option value="">Memuat...</option>';
        swapSelect.disabled = true;
        try {
            const jenis = @json($jenis);
            const res = await fetch(`/admin/peserta/${jenis}/get-peserta-angkatan?angkatan_id=${angkatanId}&exclude_peserta_id=${currentPesertaId}`);
            const data = await res.json();
            if (data.success) {
                swapSelect.innerHTML = '<option value="">-- Pilih Peserta untuk Tukar NDH --</option>';
                data.data.forEach(p => {
                    const o = document.createElement('option');
                    o.value = p.peserta_id;
                    o.textContent = `${p.nama} - NDH ${p.ndh || 'Kosong'} (${p.nip_nrp})`;
                    o.dataset.nama = p.nama; o.dataset.ndh = p.ndh || '';
                    swapSelect.appendChild(o);
                });
                swapSelect.disabled = false;
            }
        } catch(e) { swapSelect.innerHTML = '<option value="">Error memuat data</option>'; }
    }

    if (swapSelect) {
        swapSelect.addEventListener('change', async function () {
            if (!this.value) return;
            const opt = this.options[this.selectedIndex];
            const targetNama = opt.dataset.nama, targetNdh = opt.dataset.ndh, targetId = this.value;
            const msg = `TUKAR NDH?\n\n${currentNama}: NDH ${currentNdh} → NDH ${targetNdh||'Kosong'}\n${targetNama}: NDH ${targetNdh||'Kosong'} → NDH ${currentNdh}\n\nLanjutkan?`;
            if (!confirm(msg)) { this.value=''; return; }
            this.disabled = true;
            const orig = this.innerHTML;
            this.innerHTML = '<option value="">Memproses...</option>';
            try {
                const jenis = @json($jenis);
                const res = await fetch(`/admin/peserta/${jenis}/swap-ndh`, {
                    method:'POST',
                    headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json'},
                    body: JSON.stringify({current_peserta_id:currentPesertaId, target_peserta_id:targetId, current_ndh:currentNdh, target_ndh:targetNdh})
                });
                const data = await res.json();
                if (data.success) { alert('✅ NDH berhasil ditukar! Halaman akan dimuat ulang.'); window.location.reload(); }
                else throw new Error(data.message);
            } catch(e) { alert('❌ Gagal: '+e.message); this.innerHTML=orig; this.disabled=false; this.value=''; }
        });
    }
})();
@endif

// ============================================================
// LOAD AVAILABLE NDH
// ============================================================
async function loadAvailableNdh(idAngkatan, idJenisPelatihan, currentNdh = null) {
    const ndhSelect = document.getElementById('ndh');
    if (!ndhSelect) return;
    ndhSelect.innerHTML = '<option value="">Loading...</option>';
    ndhSelect.disabled = true;
    try {
        const res = await fetch(`/api/get-available-ndh?id_jenis_pelatihan=${idJenisPelatihan}&id_angkatan=${idAngkatan}`);
        const result = await res.json();
        if (result.success) {
            ndhSelect.innerHTML = '<option value="">Pilih NDH</option>';
            result.data.forEach(ndh => {
                const opt = document.createElement('option');
                opt.value = ndh; opt.textContent = `NDH ${ndh}`;
                if (currentNdh && parseInt(currentNdh) === ndh) opt.selected = true;
                ndhSelect.appendChild(opt);
            });
            if (currentNdh && !result.data.includes(parseInt(currentNdh))) {
                const opt = document.createElement('option');
                opt.value = currentNdh; opt.textContent = `NDH ${currentNdh} (Saat ini)`; opt.selected = true;
                ndhSelect.insertBefore(opt, ndhSelect.children[1]);
            }
            ndhSelect.disabled = false;
            document.getElementById('ndh-info').style.display = 'block';
            document.getElementById('ndh-stats').textContent = `Tersedia: ${result.tersedia}/${result.kuota}`;
        }
    } catch(err) { ndhSelect.innerHTML = '<option value="">Error! Refresh halaman</option>'; }
}

// ============================================================
// AUTO CAPITALIZATION
// ============================================================
function setupRealtimeAutoCapitalization() {
    const capFields = ['nama_panggilan','tempat_lahir','nama_pasangan','unit_kerja','jabatan','bidang_studi','bidang_keahlian','olahraga_hobi','nama_mentor_baru','jabatan_mentor_baru'];
    capFields.forEach(name => {
        const inp = document.querySelector(`[name="${name}"]`);
        if (!inp) return;
        inp.addEventListener('input', function () {
            const start = this.selectionStart, end = this.selectionEnd;
            let nv = '', cap = true;
            for (let c of this.value) {
                if (cap && c.match(/[a-zA-Z]/)) { nv += c.toUpperCase(); cap = false; } else { nv += c; }
                if ([' ', '-', "'"].includes(c)) cap = true;
            }
            if (this.value !== nv) { this.value = nv; this.setSelectionRange(start, end); }
        });
    });
    ['email_pribadi','email_kantor'].forEach(name => {
        const inp = document.querySelector(`[name="${name}"]`);
        if (!inp) return;
        inp.addEventListener('input', function () {
            const s = this.selectionStart, e = this.selectionEnd;
            const lc = this.value.toLowerCase();
            if (this.value !== lc) { this.value = lc; this.setSelectionRange(s, e); }
        });
    });
}

// ============================================================
// CROPPER
// ============================================================
function initCropperFunctionality() {
    let cropper = null, croppedBlob = null;
    const fileInput   = document.getElementById('file_pas_foto');
    const cropperCont = document.getElementById('cropperPreviewContainer');
    const imgPreview  = document.getElementById('imagePreview');
    const previewBox  = document.getElementById('preview');
    const fileDisplay = document.getElementById('filePasFotoName');
    const cropData    = document.getElementById('croppedImageData');
    const RATIO = 3/4;

    const existingPhoto = @json($isEdit && $pesertaData && $pesertaData->file_pas_foto ? asset($pesertaData->file_pas_foto) : null);

    if (fileInput) fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0]; if (!file) return;
        if (!file.type.match('image/(jpeg|png)')) { alert('Hanya file JPG/PNG yang diperbolehkan'); this.value=''; return; }
        if (file.size > 1024*1024) { alert('Ukuran file maksimal 1MB'); this.value=''; return; }
        const reader = new FileReader();
        reader.onload = ev => { cropperCont.style.display='block'; imgPreview.src=ev.target.result; setTimeout(()=>initC(), 100); };
        reader.readAsDataURL(file);
    });

    function initC() {
        if (cropper) cropper.destroy();
        cropper = new Cropper(imgPreview, { aspectRatio:RATIO, viewMode:2, preview:previewBox, autoCropArea:0.8, responsive:true, guides:true, center:true, highlight:false });
    }
    const rL=document.getElementById('rotateLeft'), rR=document.getElementById('rotateRight');
    const zI=document.getElementById('zoomIn'), zO=document.getElementById('zoomOut');
    const cC=document.getElementById('cancelCrop'), cI=document.getElementById('cropImage');
    if(rL) rL.addEventListener('click',()=>cropper&&cropper.rotate(-90));
    if(rR) rR.addEventListener('click',()=>cropper&&cropper.rotate(90));
    if(zI) zI.addEventListener('click',()=>cropper&&cropper.zoom(0.1));
    if(zO) zO.addEventListener('click',()=>cropper&&cropper.zoom(-0.1));
    if(cC) cC.addEventListener('click',()=>{
        if(cropper){cropper.destroy();cropper=null;}
        cropperCont.style.display='none';
        document.getElementById('pasFotoUploadContainer').style.display='block';
        if(!existingPhoto&&fileInput) fileInput.value='';
        croppedBlob=null; if(cropData) cropData.value='';
        fileDisplay.textContent = existingPhoto ? 'File sudah ada: '+existingPhoto.split('/').pop() : 'Belum ada file dipilih';
    });
    if(cI) cI.addEventListener('click',()=>{
        if(!cropper){alert('Silakan pilih gambar terlebih dahulu');return;}
        const canvas = cropper.getCroppedCanvas({width:354,height:472,fillColor:'#fff',imageSmoothingEnabled:true,imageSmoothingQuality:'high'});
        canvas.toBlob(blob=>{
            if(!blob){alert('Gagal memproses gambar');return;}
            croppedBlob=blob;
            const reader=new FileReader();
            reader.onloadend=function(){
                if(cropData) cropData.value=reader.result;
                fileDisplay.textContent='Pasfoto siap diupload ('+Math.round(blob.size/1024)+'KB)';
                cropperCont.style.display='none';
                document.getElementById('pasFotoUploadContainer').style.display='block';
                if(cropper){cropper.destroy();cropper=null;}
            };
            reader.readAsDataURL(blob);
        },'image/jpeg',0.9);
    });
    return { getCroppedBlob:()=>croppedBlob, hasCroppedImage:()=>!!croppedBlob };
}

// ============================================================
// MENTOR SEARCH
// ============================================================
function setupMentorSearch() {
    const searchInput = document.getElementById('mentor-search');
    const mentorSelect = document.getElementById('id_mentor');
    const loadingEl = document.getElementById('mentor-loading');
    const notFoundEl = document.getElementById('mentor-not-found');
    const searchInfo = document.getElementById('mentor-search-info');
    const searchStats = document.getElementById('mentor-search-stats');
    const jenis = @json($jenis);
    let timer;
    if (!searchInput || !mentorSelect) return;
    searchInput.addEventListener('input', function () {
        clearTimeout(timer);
        const q = this.value.trim();
        notFoundEl.style.display='none'; searchInfo.style.display='none';
        if (!q) { loadMentorsRemote(''); return; }
        loadingEl.style.display='block';
        timer = setTimeout(()=>loadMentorsRemote(q), 500);
    });
    async function loadMentorsRemote(q='') {
        try {
            let url = `/admin/peserta/${jenis}/get-mentors`+(q?`?search=${encodeURIComponent(q)}`:'');
            const res = await fetch(url,{headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}});
            const result = await res.json();
            loadingEl.style.display='none';
            if (result.success) {
                mentorSelect.innerHTML='<option value="">Pilih Mentor...</option>';
                if (result.data.length>0) {
                    result.data.forEach(m=>{
                        const o=document.createElement('option'); o.value=m.id;
                        o.textContent=`${m.nama_mentor} - ${m.nip_mentor||'Tanpa NIP'} - ${m.jabatan_mentor}`;
                        o.dataset.nama=m.nama_mentor||''; o.dataset.nip=m.nip_mentor||''; o.dataset.jabatan=m.jabatan_mentor||'';
                        o.dataset.nomorRekening=m.nomor_rekening||''; o.dataset.npwp=m.npwp_mentor||''; o.dataset.nomorHp=m.nomor_hp_mentor||'';
                        mentorSelect.appendChild(o);
                    });
                    if(q){searchInfo.style.display='block'; searchStats.textContent=`Ditemukan ${result.total} mentor untuk "${q}"`;}
                    notFoundEl.style.display='none';
                } else if(q) { notFoundEl.style.display='block'; notFoundEl.innerHTML=`<i class="fas fa-exclamation-circle"></i> Tidak ada mentor yang sesuai dengan "${q}"`; }
            }
        } catch(e) { loadingEl.style.display='none'; }
    }
}

// ============================================================
// MAIN DOMContentLoaded
// ============================================================
document.addEventListener('DOMContentLoaded', function () {
    const isEdit = @json($isEdit);
    const jenis  = @json($jenis);
    const picDataByAngkatan = @json($picDataByAngkatan ?? []);

    const cropperFns = initCropperFunctionality();
    setupRealtimeAutoCapitalization();

    // Elements
    const angkatanSelect = document.getElementById('id_angkatan');
    const nextToStep2Btn = document.getElementById('next-to-step2');
    const nextToStep3Btn = document.getElementById('next-to-step3');
    const nextToStep4Btn = document.getElementById('next-to-step4');
    const backToStep1Btn = document.getElementById('back-to-step1');
    const backToStep2Btn = document.getElementById('back-to-step2');
    const backToStep3Btn = document.getElementById('back-to-step3');
    const angkatanInfo   = document.getElementById('angkatan-info');
    const wilayahWrapper = document.getElementById('info-wilayah-wrapper');
    const statusPerkawinanSelect = document.getElementById('status_perkawinan');
    const namaPasanganInput      = document.getElementById('nama_pasangan');
    const golonganRuangSelect    = document.getElementById('golongan_ruang');
    const pangkatInput           = document.getElementById('pangkat');
    const provinsiSelect         = document.getElementById('id_provinsi');
    const kabupatenSelect        = document.getElementById('id_kabupaten_kota');
    const sudahAdaMentorSelect   = document.getElementById('sudah_ada_mentor');
    const mentorModeSelect       = document.getElementById('mentor_mode');
    const selectMentorForm       = document.getElementById('select-mentor-form');
    const addMentorForm          = document.getElementById('add-mentor-form');
    const mentorSelect           = document.getElementById('id_mentor');
    const namaMentorSel  = document.getElementById('nama_mentor_select');
    const nipMentorSel   = document.getElementById('nip_mentor_select');
    const jabatanMentorSel       = document.getElementById('jabatan_mentor_select');
    const rekeningSel    = document.getElementById('nomor_rekening_mentor_select');
    const npwpSel        = document.getElementById('npwp_mentor_select');
    const hpSel          = document.getElementById('nomor_hp_mentor_select');
    const mentorContainer = document.getElementById('mentor-container');

    window.oldValues = @json(old(), JSON_PRETTY_PRINT);
    const validationFailed = @json($errors->any() ? true : false);

    const pangkatMapping = {
        'II/a':'Pengatur Muda','II/b':'Pengatur Muda Tingkat I','II/c':'Pengatur','II/d':'Pengatur Tingkat I',
        'III/a':'Penata Muda','III/b':'Penata Muda Tingkat I','III/c':'Penata','III/d':'Penata Tingkat I',
        'IV/a':'Pembina','IV/b':'Pembina Tingkat I','IV/c':'Pembina Muda','IV/d':'Pembina Madya'
    };

    // File inputs
    [
        {input:'file_ktp',display:'fileKtpName'},{input:'file_sk_cpns',display:'fileSkCpnsName'},
        {input:'file_spmt',display:'fileSpmtName'},{input:'file_skp',display:'fileSkpName'},
        {input:'file_surat_tugas',display:'fileSuratTugasName'},{input:'file_surat_kesediaan',display:'fileSuratKesediaanName'},
        {input:'file_surat_sehat',display:'fileSuratSehatName'}
    ].forEach(({input,display})=>{
        const el=document.getElementById(input), dl=document.getElementById(display);
        if(el&&dl) el.addEventListener('change',function(){dl.textContent=this.files[0]?.name||'Belum ada file dipilih';this.classList.remove('error');});
    });

    // ---- STEP NAVIGATION ----
    function moveToStep(n) {
        [1,2,3,4].forEach(i=>{
            document.getElementById(`step${i}`).classList.toggle('active', i===n);
            document.getElementById(`step${i}-content`).classList.toggle('active', i===n);
        });
        window.scrollTo({top:0,behavior:'smooth'});
    }

    if(nextToStep2Btn) nextToStep2Btn.addEventListener('click',()=>{ if(angkatanSelect.value) moveToStep(2); });
    if(nextToStep3Btn) nextToStep3Btn.addEventListener('click',()=>{
        const nip = document.querySelector('input[name="nip_nrp"]');
        if(nip && !nip.value.trim()){ nip.classList.add('error'); nip.focus(); nip.scrollIntoView({behavior:'smooth',block:'center'}); showMsg('error','NIP/NRP wajib diisi'); return; }
        moveToStep(3);
    });
    if(nextToStep4Btn) nextToStep4Btn.addEventListener('click',()=>moveToStep(4));
    if(backToStep1Btn) backToStep1Btn.addEventListener('click',()=>moveToStep(1));
    if(backToStep2Btn) backToStep2Btn.addEventListener('click',()=>moveToStep(2));
    if(backToStep3Btn) backToStep3Btn.addEventListener('click',()=>moveToStep(3));

    // ---- INIT EDIT ----
    if(isEdit) {
        if(nextToStep2Btn) nextToStep2Btn.disabled=false;
        document.querySelectorAll('input[type="file"]').forEach(i=>i.removeAttribute('required'));
        setTimeout(()=>{ if(provinsiSelect&&provinsiSelect.value) provinsiSelect.dispatchEvent(new Event('change')); setTimeout(()=>moveToStep(1),800); },500);
    }

    // ---- ANGKATAN CHANGE ----
    if(angkatanSelect) angkatanSelect.addEventListener('change', function(){
        if(!this.value){ if(nextToStep2Btn) nextToStep2Btn.disabled=true; angkatanInfo.style.display='none'; return; }
        const opt = this.options[this.selectedIndex];
        const ang = {id:this.value, nama:opt.dataset.nama, tahun:opt.dataset.tahun, kuota:opt.dataset.kuota, kategori:opt.dataset.kategori, wilayah:opt.dataset.wilayah, status:opt.dataset.status};
        ['current-angkatan-name','current-angkatan-name-2','current-angkatan-name-3'].forEach(id=>{
            const el=document.getElementById(id); if(el) el.textContent=`${ang.nama} (${ang.tahun})`;
        });
        document.getElementById('info-nama-angkatan').textContent=ang.nama;
        document.getElementById('info-tahun-angkatan').textContent=ang.tahun;
        document.getElementById('info-kategori-angkatan').textContent=ang.kategori;
        document.getElementById('info-kuota-angkatan').textContent=ang.kuota;
        if(ang.wilayah){ document.getElementById('info-wilayah-angkatan').textContent=ang.wilayah; wilayahWrapper.style.display='flex'; } else { wilayahWrapper.style.display='none'; }
        const badge=document.getElementById('info-status-angkatan');
        badge.textContent=ang.status; badge.className='info-badge';
        badge.style.background = ang.status==='Aktif'||ang.status==='Dibuka'?'var(--success-color)':ang.status==='Penuh'?'var(--danger-color)':'var(--warning-color)';
        const picW=document.getElementById('info-pic-wrapper'), picI=document.getElementById('info-pic-angkatan');
        if(picDataByAngkatan[ang.id]){ const p=picDataByAngkatan[ang.id]; picI.innerHTML=`<strong>${p.nama} (${p.no_telp})</strong>`; picW.style.display='flex'; }
        else { picI.innerHTML='<em style="color:var(--gray-color);">PIC belum ditentukan</em>'; picW.style.display='flex'; }
        angkatanInfo.style.display='block';
        if(nextToStep2Btn) nextToStep2Btn.disabled=false;
        const jenisPelatihanId = @json($jenisPelatihanId);
        const currentNdh = @json($isEdit && $pesertaData ? $pesertaData->ndh : null);
        loadAvailableNdh(ang.id, jenisPelatihanId, currentNdh);
    });

    // ---- STATUS PERKAWINAN ----
    if(statusPerkawinanSelect&&namaPasanganInput){
        statusPerkawinanSelect.addEventListener('change',function(){
            namaPasanganInput.disabled = this.value!=='Menikah';
            if(this.value!=='Menikah') namaPasanganInput.value='';
        });
        if(statusPerkawinanSelect.value) statusPerkawinanSelect.dispatchEvent(new Event('change'));
    }

    // ---- GOLONGAN -> PANGKAT ----
    if(golonganRuangSelect&&pangkatInput){
        golonganRuangSelect.addEventListener('change',function(){ pangkatInput.value = pangkatMapping[this.value]||''; });
        if(golonganRuangSelect.value) golonganRuangSelect.dispatchEvent(new Event('change'));
    }
    const gmb = document.getElementById('golongan_mentor_baru'), pmb = document.getElementById('pangkat_mentor_baru');
    if(gmb&&pmb) gmb.addEventListener('change',function(){ pmb.value = pangkatMapping[this.value]||''; });

    // ---- MENTOR ----
    if(sudahAdaMentorSelect){
        sudahAdaMentorSelect.addEventListener('change',function(){
            mentorContainer.style.display = this.value==='Ya' ? 'block' : 'none';
            if(this.value!=='Ya'){ mentorModeSelect.value=''; selectMentorForm.style.display='none'; addMentorForm.style.display='none'; if(mentorSelect) mentorSelect.value=''; resetMentorFields(); }
        });
        mentorModeSelect.addEventListener('change',function(){
            selectMentorForm.style.display = this.value==='pilih'?'block':'none';
            addMentorForm.style.display    = this.value==='tambah'?'block':'none';
            if(this.value==='pilih') setTimeout(setupMentorSearch, 100);
        });
        if(mentorSelect) mentorSelect.addEventListener('change',function(){
            if(!this.value){ resetMentorFields(); return; }
            const o = this.options[this.selectedIndex];
            if(namaMentorSel) namaMentorSel.value = o.dataset.nama||'';
            if(nipMentorSel)  nipMentorSel.value  = o.dataset.nip||'';
            if(jabatanMentorSel) jabatanMentorSel.value = o.dataset.jabatan||'';
            if(rekeningSel)  rekeningSel.value  = o.dataset.nomorRekening||'';
            if(npwpSel)      npwpSel.value      = o.dataset.npwp||'';
            if(hpSel)        hpSel.value        = o.dataset.nomorHp||'';
            const gEl=document.getElementById('golongan_mentor_select'), pEl=document.getElementById('pangkat_mentor_select');
            if(gEl) gEl.value = o.dataset.golongan||'';
            if(pEl) pEl.value = pangkatMapping[o.dataset.golongan]||'';
        });
        if(sudahAdaMentorSelect.value) sudahAdaMentorSelect.dispatchEvent(new Event('change'));
        if(mentorModeSelect.value){ mentorModeSelect.dispatchEvent(new Event('change')); if(mentorModeSelect.value==='pilih') setTimeout(setupMentorSearch,200); }
        if(mentorSelect&&mentorSelect.value) mentorSelect.dispatchEvent(new Event('change'));
    }
    function resetMentorFields(){
        [namaMentorSel,nipMentorSel,jabatanMentorSel,rekeningSel,npwpSel,hpSel].forEach(el=>{ if(el) el.value=''; });
        const gEl=document.getElementById('golongan_mentor_select'), pEl=document.getElementById('pangkat_mentor_select');
        if(gEl) gEl.value=''; if(pEl) pEl.value='';
    }

    // ---- PROVINSI -> KABUPATEN ----
    if(provinsiSelect){
        provinsiSelect.addEventListener('change',function(){
            if(!this.value){ kabupatenSelect.innerHTML='<option value="">Pilih Kabupaten/Kota (Pilih Provinsi Dahulu)</option>'; kabupatenSelect.disabled=true; return; }
            kabupatenSelect.innerHTML='<option value="">Memuat...</option>'; kabupatenSelect.disabled=true;
            const all = @json($kabupatenList);
            const filtered = all.filter(k=>k.province_id==this.value);
            kabupatenSelect.innerHTML='<option value="">Pilih Kabupaten/Kota</option>';
            kabupatenSelect.disabled=false;
            filtered.forEach(k=>{ const o=document.createElement('option'); o.value=k.id; o.textContent=k.name; kabupatenSelect.appendChild(o); });
            const curKab = @json($isEdit && $kepegawaianData ? $kepegawaianData->id_kabupaten_kota : null);
            if(curKab) kabupatenSelect.value=curKab;
            if(window.oldValues&&window.oldValues.id_kabupaten_kota) kabupatenSelect.value=window.oldValues.id_kabupaten_kota;
        });
        if(provinsiSelect.value) setTimeout(()=>provinsiSelect.dispatchEvent(new Event('change')),300);
    }

    // ---- FORM SUBMIT ----
    const pesertaForm = document.getElementById('pesertaForm');
    if(pesertaForm) pesertaForm.addEventListener('submit', async function(e){
        e.preventDefault();
        const submitBtn = document.getElementById('submit-form');
        const origTxt = submitBtn.innerHTML;
        submitBtn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> ${isEdit?'Mengupdate...':'Menyimpan...'}`;
        submitBtn.disabled=true;
        document.querySelectorAll('.client-error').forEach(el=>el.remove());

        // Validasi NIP
        const nipField = document.querySelector('input[name="nip_nrp"]');
        const angFld   = document.querySelector('select[name="id_angkatan"]');
        let hasErr=false;
        if(nipField&&!nipField.value.trim()){ nipField.classList.add('error'); addErrMsg(nipField,'NIP/NRP wajib diisi','client-error'); hasErr=true; }
        if(angFld&&!angFld.value){ angFld.classList.add('error'); addErrMsg(angFld,'Angkatan wajib dipilih','client-error'); hasErr=true; }
        if(hasErr){
            submitBtn.innerHTML=origTxt; submitBtn.disabled=false;
            const fe=document.querySelector('.error');
            if(fe){ if(fe.closest('#step1-content')) moveToStep(1); else if(fe.closest('#step2-content')) moveToStep(2); fe.scrollIntoView({behavior:'smooth',block:'center'}); }
            return;
        }

        // Cropped image
        const fInput = document.getElementById('file_pas_foto');
        if(cropperFns.hasCroppedImage()){
            const blob = cropperFns.getCroppedBlob();
            const file = new File([blob],'pasfoto_cropped.jpg',{type:'image/jpeg',lastModified:Date.now()});
            const dt = new DataTransfer(); dt.items.add(file); fInput.files=dt.files;
        }

        const formData = new FormData(this);
        try {
            const res = await fetch(this.action,{method:this.method,body:formData,headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').getAttribute('content'),'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}});
            const data = await res.json();
            if(data.success){
                showMsg('success', isEdit?'Peserta berhasil diperbarui!':'Peserta berhasil ditambahkan!');
                setTimeout(()=>{ window.location.href = data.redirect_url||`/peserta/${jenis}`; },1500);
            } else {
                submitBtn.innerHTML=origTxt; submitBtn.disabled=false;
                document.querySelectorAll('.server-error').forEach(el=>el.remove());
                document.querySelectorAll('.error').forEach(el=>el.classList.remove('error'));
                document.querySelectorAll('.form-file-label').forEach(l=>{ l.style.borderColor=''; l.style.background=''; });
                if(data.errors){
                    if(data.errors.id_angkatan){ moveToStep(1); const af=document.querySelector('[name="id_angkatan"]'); if(af){ af.classList.add('error'); addErrMsg(af,data.errors.id_angkatan[0],'server-error'); setTimeout(()=>af.scrollIntoView({behavior:'smooth',block:'center'}),300); } showMsg('error',data.errors.id_angkatan[0]); return; }
                    Object.keys(data.errors).forEach(f=>{
                        let inp = document.querySelector(`[name="${f}"]`)||document.querySelector(`#${f}`);
                        if(inp){ inp.classList.add('error'); if(inp.type==='file'){ const lbl=inp.closest('.form-file')?.querySelector('.form-file-label'); if(lbl){lbl.style.borderColor='var(--danger-color)';lbl.style.background='rgba(245,101,101,0.05)';} } addErrMsg(inp,data.errors[f][0],'server-error'); }
                    });
                    const fe=document.querySelector('.error'); if(fe) setTimeout(()=>fe.scrollIntoView({behavior:'smooth',block:'center'}),300);
                } else if(data.message){ showMsg('error',data.message); }
            }
        } catch(err){ console.error(err); submitBtn.innerHTML=origTxt; submitBtn.disabled=false; showMsg('error','Terjadi kesalahan jaringan. Silakan coba lagi.'); }
    });

    function addErrMsg(inp,msg,cls){
        const fg=inp.closest('.form-group'); if(!fg) return;
        const existing=fg.querySelector(`.${cls}`); if(existing) existing.remove();
        const s=document.createElement('small'); s.className=`text-danger ${cls}`; s.textContent=msg; fg.appendChild(s);
    }

    function showMsg(type,message){
        const n=document.createElement('div'); n.className=`notification ${type}`;
        n.innerHTML=`<div class="notification-content"><i class="fas fa-${type==='success'?'check-circle':'exclamation-circle'}"></i><span>${message}</span></div>`;
        document.body.appendChild(n); setTimeout(()=>n.classList.add('show'),10);
        setTimeout(()=>{ n.classList.remove('show'); setTimeout(()=>n.remove(),300); }, type==='success'?3000:5000);
    }

    // Notification CSS
    const style=document.createElement('style');
    style.textContent=`.notification{position:fixed;top:20px;right:20px;z-index:9999;min-width:300px;max-width:400px;background:white;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,.15);padding:15px 20px;transform:translateX(420px);transition:transform .3s ease}.notification.show{transform:translateX(0)}.notification.success{border-left:4px solid #48bb78}.notification.error{border-left:4px solid #f56565}.notification-content{display:flex;align-items:center;gap:12px}.notification.success .notification-content i{color:#48bb78}.notification.error .notification-content i{color:#f56565}.notification-content span{flex:1;font-size:.95rem}`;
    document.head.appendChild(style);

    // Clear errors on input
    document.addEventListener('input',function(e){ if(e.target.matches('input,select,textarea')){ e.target.classList.remove('error'); const fg=e.target.closest('.form-group'); if(fg){ const er=fg.querySelector('.server-error,.client-error'); if(er) er.remove(); } } });
    document.addEventListener('change',function(e){ if(e.target.matches('input[type="file"]')){ e.target.classList.remove('error'); const lbl=e.target.closest('.form-file')?.querySelector('.form-file-label'); if(lbl){lbl.style.borderColor='';lbl.style.background='';} } });

    // Init
    if(angkatanSelect&&angkatanSelect.value) angkatanSelect.dispatchEvent(new Event('change'));
    if(validationFailed&&window.oldValues?.id_angkatan){ angkatanSelect.value=window.oldValues.id_angkatan; angkatanSelect.dispatchEvent(new Event('change')); }
    if(validationFailed&&window.oldValues?.status_perkawinan&&statusPerkawinanSelect){ statusPerkawinanSelect.value=window.oldValues.status_perkawinan; statusPerkawinanSelect.dispatchEvent(new Event('change')); }
    if(validationFailed&&window.oldValues?.golongan_ruang&&golonganRuangSelect){ golonganRuangSelect.value=window.oldValues.golongan_ruang; golonganRuangSelect.dispatchEvent(new Event('change')); }
});
</script>
@endsection