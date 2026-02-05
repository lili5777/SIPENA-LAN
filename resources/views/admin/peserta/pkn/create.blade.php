@php
// Ambil jenis dari route
$jenis = request()->route('jenis');

// Mapping nama dan ID
$jenisMapping = [
    'pkn' => ['nama' => 'PKN TK II', 'id' => 1, 'kode' => 'PKN_TK_II'],
    'cpns' => ['nama' => 'PD CPNS', 'id' => 2, 'kode' => 'PD_CPNS'],
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
                                <label for="id_angkatan" class="form-label required">Angkatan *</label>
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
                                                      <!-- NDH -->
<div class="form-row">
    <div class="form-group">
        <label class="form-label ">Nomor Daftar Hadir (NDH)</label>
        <select name="ndh" id="ndh" class="form-select @error('ndh') error @enderror"  disabled>
            <option value="">Pilih angkatan dulu</option>
        </select>
        <div class="form-hint" id="ndh-info" style="display:none;">
            <i class="fas fa-info-circle"></i> <span id="ndh-stats"></span>
        </div>
        @error('ndh')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
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
                                    <label class="form-label required">NIP/NRP *</label>
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
                                        <option value="Laki-laki" {{ ($pesertaData && $pesertaData->jenis_kelamin == 'Laki-laki') || old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>
                                            Laki-laki
                                        </option>
                                        <option value="Perempuan" {{ ($pesertaData && $pesertaData->jenis_kelamin == 'Perempuan') || old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>
                                            Perempuan
                                        </option>
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
                                        {{-- <option value="Kristen Protestan" {{ ($pesertaData && $pesertaData->agama == 'Kristen Protestan') || old('agama') == 'Kristen Protestan' ? 'selected' : '' }}>Kristen Protestan</option> --}}
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
                                        <option value="Belum Menikah" {{ ($pesertaData && $pesertaData->status_perkawinan == 'Belum Menikah') || old('status_perkawinan') == 'Belum Menikah' ? 'selected' : '' }}>
                                            Belum Menikah
                                        </option>
                                        <option value="Menikah" {{ ($pesertaData && $pesertaData->status_perkawinan == 'Menikah') || old('status_perkawinan') == 'Menikah' ? 'selected' : '' }}>
                                            Menikah
                                        </option>
                                        <option value="Duda" {{ ($pesertaData && $pesertaData->status_perkawinan == 'Duda') || old('status_perkawinan') == 'Duda' ? 'selected' : '' }}>
                                            Duda
                                        </option>
                                        <option value="Janda" {{ ($pesertaData && $pesertaData->status_perkawinan == 'Janda') || old('status_perkawinan') == 'Janda' ? 'selected' : '' }}>
                                            Janda
                                        </option>
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
                                    <label class="form-label">Pendidikan Terakhir (Sesuai SK)</label>
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
                                    <label class="form-label">Ukuran Baju Taktikal</label>
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
                                    <label class="form-label">Ukuran Kaos Olahraga</label>
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
                                <div class="form-group">
                                    <label class="form-label">Asal Instansi</label>
                                    <input type="text" name="asal_instansi"
                                        class="form-input @error('asal_instansi') error @enderror"
                                        placeholder="Contoh: Lembaga Administrasi Negara"
                                        value="{{ $kepegawaianData ? $kepegawaianData->asal_instansi : old('asal_instansi') }}">
                                    @error('asal_instansi')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
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
                                    <label class="form-label">Eselon</label>
                                    <select name="eselon" class="form-select @error('eselon') error @enderror">
                                        <option value="">Pilih</option>
                                        <option value="II" {{ ($kepegawaianData && $kepegawaianData->eselon == 'II') || old('eselon') == 'II' ? 'selected' : '' }}>II</option>
                                        <option value="III/Pejabat Fungsional" {{ ($kepegawaianData && $kepegawaianData->eselon == 'III/Pejabat Fungsional') || old('eselon') == 'III/Pejabat Fungsional' ? 'selected' : '' }}>III/Pejabat Fungsional</option>
                                    </select>
                                    @error('eselon')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
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
                                    <option value="Ya" {{ $hasMentor || old('sudah_ada_mentor') == 'Ya' ? 'selected' : '' }}>
                                        Ya</option>
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
                                        <label class="form-label">Pilih Mentor</label>
                                        <select name="id_mentor" id="id_mentor"
                                            class="form-select @error('id_mentor') error @enderror">
                                            <option value="">Pilih Mentor...</option>
                                            @foreach($mentorList as $mentor)
                                                <option value="{{ $mentor->id }}" data-nama="{{ $mentor->nama_mentor }}"
                                                    data-nip="{{ $mentor->nip_mentor }}"
                                                    data-jabatan="{{ $mentor->jabatan_mentor }}"
                                                    data-nomor-rekening="{{ $mentor->nomor_rekening }}"
                                                    data-npwp="{{ $mentor->npwp_mentor }}" {{ ($selectedMentorId == $mentor->id) || old('id_mentor') == $mentor->id ? 'selected' : '' }}>
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
                                            <label class="form-label">Nama Mentor</label>
                                            <input type="text" name="nama_mentor" id="nama_mentor_select"
                                                class="form-input @error('nama_mentor') error @enderror"
                                                value="{{ $mentorData && $mentorData->mentor ? $mentorData->mentor->nama_mentor : old('nama_mentor') }}"
                                                readonly>
                                            @error('nama_mentor')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">NIP Mentor</label>
                                            <input type="text" name="nip_mentor" id="nip_mentor_select"
                                                class="form-input @error('nip_mentor') error @enderror"
                                                value="{{ $mentorData && $mentorData->mentor ? $mentorData->mentor->nip_mentor : old('nip_mentor') }}"
                                                readonly>
                                            @error('nip_mentor')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Jabatan Mentor</label>
                                            <input type="text" name="jabatan_mentor" id="jabatan_mentor_select"
                                                class="form-input @error('jabatan_mentor') error @enderror"
                                                value="{{ $mentorData && $mentorData->mentor ? $mentorData->mentor->jabatan_mentor : old('jabatan_mentor') }}"
                                                readonly>
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
                                                value="{{ $mentorData && $mentorData->mentor ? $mentorData->mentor->nomor_rekening : old('nomor_rekening_mentor') }}"
                                                readonly>
                                            @error('nomor_rekening_mentor')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">NPWP Mentor</label>
                                            <input type="text" name="npwp_mentor" id="npwp_mentor_select"
                                                class="form-input @error('npwp_mentor') error @enderror"
                                                value="{{ $mentorData && $mentorData->mentor ? $mentorData->mentor->npwp_mentor : old('npwp_mentor') }}"
                                                readonly>
                                            @error('npwp_mentor')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
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
                                            @error('nama_mentor_baru')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">NIP Mentor</label>
                                            <input type="text" name="nip_mentor_baru" id="nip_mentor_baru"
                                                class="form-input @error('nip_mentor_baru') error @enderror"
                                                value="{{ old('nip_mentor_baru') }}" placeholder="Masukkan NIP mentor">
                                            @error('nip_mentor_baru')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Jabatan Mentor</label>
                                            <input type="text" name="jabatan_mentor_baru" id="jabatan_mentor_baru"
                                                class="form-input @error('jabatan_mentor_baru') error @enderror"
                                                value="{{ $mentorBaruJabatan }}" placeholder="Masukkan jabatan mentor">
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

                            @if ($kunci_judul == true)
                                <!-- Data Projek -->
                                <div class="form-section-header">
                                    <i class="fas fa-lightbulb"></i>Projek Aksi Perubahan
                                </div>

                                <!-- Judul -->
                                <div class="form-group">
                                    <label class="form-label">Judul</label>
                                    <input type="text" name="judul"
                                        class="form-input @error('judul') error @enderror"
                                        value="{{ old('judul', optional($aksiPerubahan)->judul) }}"
                                        placeholder="anunya">
                                    @error('judul')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Upload Hasil Projek (File PDF) -->
                                <div class="form-group">
                                    <label class="form-label">Upload Laporan Lengkap</label>
                                    <div class="form-hint">Format PDF, maksimal 1MB</div>
                                    <div class="form-file">
                                        <input type="file" name="file" id="file"
                                            class="form-file-input @error('file') error @enderror"
                                            accept=".pdf">
                                        <label class="form-file-label" for="file">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <span>{{ $isEdit && $aksiPerubahan && $aksiPerubahan->file ? 'Ganti File Projek' : 'Klik untuk mengunggah file Projek' }}</span>
                                        </label>
                                        <div class="form-file-name" id="filee">
                                            @if ($isEdit && $aksiPerubahan && $aksiPerubahan->file)
                                                File sudah ada: {{ basename($aksiPerubahan->file) }}
                                            @else
                                                Belum ada file dipilih
                                            @endif
                                        </div>
                                    </div>
                                    @error('file')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Upload Lembar Pengesahan (File) -->
                                <div class="form-group">
                                    <label class="form-label">Upload Lembar Pengesahan</label>
                                    <div class="form-hint">Format PDF, maksimal 1MB</div>
                                    <div class="form-file">
                                        <input type="file" name="lembar_pengesahan" id="lembar_pengesahan"
                                            class="form-file-input @error('lembar_pengesahan') error @enderror"
                                            accept=".pdf">
                                        <label class="form-file-label" for="lembar_pengesahan">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <span>{{ $isEdit && $aksiPerubahan && $aksiPerubahan->lembar_pengesahan ? 'Ganti Lembar Pengesahan' : 'Klik untuk mengunggah lembar pengesahan' }}</span>
                                        </label>
                                        <div class="form-file-name" id="lembar_pengesahan_name">
                                            @if ($isEdit && $aksiPerubahan && $aksiPerubahan->lembar_pengesahan)
                                                Lembar Pengesahan sudah ada: {{ basename($aksiPerubahan->lembar_pengesahan) }}
                                            @else
                                                Belum ada file dipilih
                                            @endif
                                        </div>
                                    </div>
                                    @error('lembar_pengesahan')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                @php
    $kategoriOptions = [
        'Memperkokoh ideologi Pancasila, demokrasi, dan hak asasi manusia (HAM)',
        'Memantapkan sistem pertahanan keamanan negara dan mendorong kemandirian bangsa melalui swasembada pangan, energi, air, ekonomi kreatif, ekonomi hijau, dan ekonomi biru',
        'Meningkatkan lapangan kerja yang berkualitas, mendorong kewirausahaan, mengembangkan industri kreatif, dan melanjutkan pengembangan infrastruktur',
        'Memperkuat pembangunan sumber daya manusia (SDM), sains, teknologi, pendidikan, kesehatan, prestasi olahraga, kesetaraan gender, serta penguatan peran perempuan, pemuda, dan penyandang disabilitas',
        'Melanjutkan hilirisasi dan industrialisasi untuk meningkatkan nilai tambah di dalam negeri',
        'Membangun dari desa dan dari bawah untuk pemerataan ekonomi dan pemberantasan kemiskinan.',
        'Memperkuat reformasi politik, hukum, dan birokrasi, serta memperkuat pencegahan dan pemberantasan korupsi dan narkoba',
        'Memperkuat penyelarasan kehidupan yang harmonis dengan lingkungan, alam, dan budaya, serta peningkatan toleransi antarumat beragama untuk mencapai masyarakat yang adil dan makmur',
    ];

    $selectedKategori = old('kategori_aksatika', optional($aksiPerubahan)->kategori_aksatika);
                                @endphp

                                <div class="form-group">
                                    <label class="form-label">Kategori Aksatika</label>
                                    <select name="kategori_aksatika" class="form-input @error('kategori_aksatika') error @enderror">
                                        <option value="">Pilih Kategori</option>

                                        @foreach ($kategoriOptions as $opt)
                                            <option value="{{ $opt }}" {{ $selectedKategori === $opt ? 'selected' : '' }}>
                                                {{ $opt }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('kategori_aksatika')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Link Video -->
                                <div class="form-group">
                                    <label class="form-label">Link Video</label>
                                    <input type="url" name="link_video" 
                                        class="form-input @error('link_video') error @enderror"
                                        value="{{ old('link_video', optional($aksiPerubahan)->link_video) }}"
                                        placeholder="Masukkan link video">
                                    @error('link_video')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Link Laporan Majalah -->
                                <div class="form-group">
                                    <label class="form-label">Link Laporan Majalah</label>
                                    <input type="url" name="link_laporan_majalah"
                                        class="form-input @error('link_laporan_majalah') error @enderror"
                                        value="{{ old('link_laporan_majalah', optional($aksiPerubahan)->link_laporan_majalah) }}"
                                        placeholder="Masukkan link laporan majalah">
                                    @error('link_laporan_majalah')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            @endif

                            
                           
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

                        <!-- Dokumen Pendukung -->
                        <div class="dynamic-form-container" id="dynamic-form-container-3">
                            <div class="form-section-header">
                                <i class="fas fa-file-upload"></i> Dokumen Pendukung
                            </div>

                            <!-- Current Document Previews -->
                            @if($isEdit)
                                @php
    $dokumenFields = [
        'file_surat_tugas' => 'Surat Tugas',
        'file_surat_kesediaan' => 'Surat Kesediaan',
        'file_pakta_integritas' => 'Pakta Integritas',
        'file_surat_komitmen' => 'Surat Komitmen',
        'file_surat_kelulusan_seleksi' => 'Surat Kelulusan Seleksi',
        'file_surat_sehat' => 'Surat Sehat',
        'file_surat_bebas_narkoba' => 'Surat Bebas Narkoba',
        'file_surat_pernyataan_administrasi' => 'Surat Pernyataan Administrasi',
        'file_persetujuan_mentor' => 'Persetujuan Mentor',
        'file_sertifikat_penghargaan' => 'Sertifikat Penghargaan'
    ];
                                @endphp

                                {{-- @foreach($dokumenFields as $field => $label)
                                    @if($pendaftaran->$field)
                                        <div class="form-group">
                                            <label class="form-label">{{ $label }} Saat Ini</label>
                                            <div class="current-file-preview">
                                                <a href="{{ asset($pendaftaran->$field) }}" target="_blank"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye me-1"></i> Lihat {{ $label }}
                                                </a>
                                                <small class="d-block text-muted mt-1">File:
                                                    {{ basename($pendaftaran->$field) }}</small>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach --}}
                            @endif

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
                                        @elseif(old('file_ktp'))
                                            File sudah diupload sebelumnya
                                        @else
                                            Belum ada file dipilih
                                        @endif
                                    </div>
                                </div>
                                @error('file_ktp')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Current Photo Preview -->
                            {{-- @if($isEdit && $pesertaData && $pesertaData->file_pas_foto)
                                <div class="form-group">
                                    <label class="form-label">Pasfoto Saat Ini</label>
                                    <div class="current-file-preview">
                                        <img src="{{ asset($pesertaData->file_pas_foto) }}" alt="Pasfoto" class="img-thumbnail"
                                            style="max-width: 200px; max-height: 200px;">
                                        <small class="d-block text-muted mt-1">Pasfoto yang sudah diunggah</small>
                                    </div>
                                </div>
                            @endif --}}

                            <!-- Pas Foto -->
                            {{-- <div class="form-group">
                                <label class="form-label">Upload Pasfoto peserta berwarna</label>
                                <div class="form-hint">Format JPG/PNG, maksimal 1MB</div>
                                <div class="form-file">
                                    <input type="file" name="file_pas_foto" id="file_pas_foto"
                                        class="form-file-input @error('file_pas_foto') error @enderror"
                                        accept=".jpg,.jpeg,.png">
                                    <label class="form-file-label" for="file_pas_foto">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>{{ $isEdit && $pesertaData && $pesertaData->file_pas_foto ? 'Ganti Pasfoto' : 'Klik untuk mengunggah file JPG/PNG (maks. 1MB)' }}</span>
                                    </label>
                                    <div class="form-file-name" id="filePasFotoName">
                                        @if($isEdit && $pesertaData && $pesertaData->file_pas_foto)
                                            File sudah ada: {{ basename($pesertaData->file_pas_foto) }}
                                        @elseif(old('file_pas_foto'))
                                            File sudah diupload sebelumnya
                                        @else
                                            Belum ada file dipilih
                                        @endif
                                    </div>
                                </div>
                                @error('file_pas_foto')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div> --}}
                            <!-- Pas Foto -->
<div class="form-group">
    <label class="form-label">Upload Pasfoto peserta berwarna</label>
    <div class="form-hint">Format JPG/PNG, maksimal 1MB. Ukuran rekomendasi: 3x4 cm</div>
    
    <!-- Layout baris untuk upload dan contoh foto -->
    <div style="display: flex; gap: 20px; align-items: flex-start; flex-wrap: wrap; margin-bottom: 15px;">
        <!-- Cropper Preview Area (Hanya muncul saat cropping) -->
        <div class="cropper-preview-container" id="cropperPreviewContainer" style="display: none; flex: 1; min-width: 100%;">
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
                        <div class="preview-hint">Ukuran: 3x4 cm</div>
                    </div>
                </div>
            </div>
            
            <div class="cropper-controls mt-3">
                <button type="button" class="btn btn-sm btn-outline-primary" id="rotateLeft">
                    <i class="fas fa-undo"></i> Putar Kiri
                </button>
                <button type="button" class="btn btn-sm btn-outline-primary" id="rotateRight">
                    <i class="fas fa-redo"></i> Putar Kanan
                </button>
                <button type="button" class="btn btn-sm btn-outline-primary" id="zoomIn">
                    <i class="fas fa-search-plus"></i> Zoom In
                </button>
                <button type="button" class="btn btn-sm btn-outline-primary" id="zoomOut">
                    <i class="fas fa-search-minus"></i> Zoom Out
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger" id="cancelCrop">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="button" class="btn btn-sm btn-success" id="cropImage">
                    <i class="fas fa-crop"></i> Potong & Simpan
                </button>
            </div>
        </div>
        
        <!-- File Input Container -->
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
                @elseif(old('file_pas_foto'))
                    File sudah diupload sebelumnya
                @else
                    Belum ada file dipilih
                @endif
            </div>
        </div>

        <!-- Contoh foto -->
        <div style="text-align: center;">
            <p style="margin: 0 0 8px 0; font-size: 0.9em; color: #666;"><strong>Contoh Foto 3×4:</strong></p>
            <div style="width: 90px; height: 120px; border: 2px solid #ddd; overflow: hidden; border-radius: 4px;">
                <img src="{{ asset('gambar/contohfoto.jpeg') }}" 
                     alt="Contoh Foto 3x4"
                     style="width: 100%; height: 100%; object-fit: cover;"
                     onerror="this.src='https://via.placeholder.com/90x120?text=Contoh+Foto'">
            </div>
        </div>
    </div>
    
    <!-- Hidden canvas for cropping -->
    <canvas id="croppedCanvas" style="display: none;"></canvas>
    
    <!-- Hidden input untuk menyimpan data cropped image -->
    <input type="hidden" name="cropped_image_data" id="croppedImageData">
    
    @error('file_pas_foto')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

                            <!-- SK Jabatan dan Pangkat -->
                            {{-- @if($isEdit && $kepegawaianData)
                                @if($kepegawaianData->file_sk_jabatan)
                                    <div class="form-group">
                                        <label class="form-label">SK Jabatan Saat Ini</label>
                                        <div class="current-file-preview">
                                            <a href="{{ asset($kepegawaianData->file_sk_jabatan) }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye me-1"></i> Lihat SK Jabatan
                                            </a>
                                            <small class="d-block text-muted mt-1">File:
                                                {{ basename($kepegawaianData->file_sk_jabatan) }}</small>
                                        </div>
                                    </div>
                                @endif

                                @if($kepegawaianData->file_sk_pangkat)
                                    <div class="form-group">
                                        <label class="form-label">SK Pangkat Saat Ini</label>
                                        <div class="current-file-preview">
                                            <a href="{{ asset($kepegawaianData->file_sk_pangkat) }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye me-1"></i> Lihat SK Pangkat
                                            </a>
                                            <small class="d-block text-muted mt-1">File:
                                                {{ basename($kepegawaianData->file_sk_pangkat) }}</small>
                                        </div>
                                    </div>
                                @endif
                            @endif --}}

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Unggah Bukti SK Jabatan Terakhir</label>
                                    <div class="form-hint">Format PDF, maksimal 1MB</div>
                                    <div class="form-file">
                                        <input type="file" name="file_sk_jabatan" id="file_sk_jabatan"
                                            class="form-file-input @error('file_sk_jabatan') error @enderror" accept=".pdf">
                                        <label class="form-file-label" for="file_sk_jabatan">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <span>{{ $isEdit && $kepegawaianData && $kepegawaianData->file_sk_jabatan ? 'Ganti SK Jabatan' : 'Klik untuk mengunggah file PDF (maks. 1MB)' }}</span>
                                        </label>
                                        <div class="form-file-name" id="fileSkJabatanName">
                                            @if($isEdit && $kepegawaianData && $kepegawaianData->file_sk_jabatan)
                                                File sudah ada: {{ basename($kepegawaianData->file_sk_jabatan) }}
                                            @elseif(old('file_sk_jabatan'))
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
                                    <label class="form-label">Unggah Bukti SK Pangkat/Golongan Ruang Terakhir</label>
                                    <div class="form-hint">Format PDF, maksimal 1MB</div>
                                    <div class="form-file">
                                        <input type="file" name="file_sk_pangkat" id="file_sk_pangkat"
                                            class="form-file-input @error('file_sk_pangkat') error @enderror" accept=".pdf">
                                        <label class="form-file-label" for="file_sk_pangkat">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <span>{{ $isEdit && $kepegawaianData && $kepegawaianData->file_sk_pangkat ? 'Ganti SK Pangkat' : 'Klik untuk mengunggah file PDF (maks. 1MB)' }}</span>
                                        </label>
                                        <div class="form-file-name" id="fileSkPangkatName">
                                            @if($isEdit && $kepegawaianData && $kepegawaianData->file_sk_pangkat)
                                                File sudah ada: {{ basename($kepegawaianData->file_sk_pangkat) }}
                                            @elseif(old('file_sk_pangkat'))
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

                            <!-- Surat Tugas -->
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
                                        <span>{{ $isEdit && $pendaftaran->file_surat_tugas ? 'Ganti Surat Tugas' : 'Klik untuk mengunggah file PDF (maks. 1MB)' }}</span>
                                    </label>
                                    <div class="form-file-name" id="fileSuratTugasName">
                                        @if($isEdit && $pendaftaran->file_surat_tugas)
                                            File sudah ada: {{ basename($pendaftaran->file_surat_tugas) }}
                                        @elseif(old('file_surat_tugas'))
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

                            <!-- Pakta Integritas -->
                            <div class="form-group">
                                <label class="form-label">Unggah Scan Pakta Integritas (Formulir menggunakan Kop Instansi)</label>
                                <div class="form-hint">Format PDF, maksimal 1MB</div>
                                <div class="form-file">
                                    <input type="file" name="file_pakta_integritas" id="file_pakta_integritas"
                                        class="form-file-input @error('file_pakta_integritas') error @enderror"
                                        accept=".pdf">
                                    <label class="form-file-label" for="file_pakta_integritas">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>{{ $isEdit && $pendaftaran->file_pakta_integritas ? 'Ganti Pakta Integritas' : 'Klik untuk mengunggah file PDF (maks. 1MB)' }}</span>
                                    </label>
                                    <div class="form-file-name" id="filePaktaIntegritasName">
                                        @if($isEdit && $pendaftaran->file_pakta_integritas)
                                            File sudah ada: {{ basename($pendaftaran->file_pakta_integritas) }}
                                        @elseif(old('file_pakta_integritas'))
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
                            <!-- Surat Komitmen -->
                            <div class="form-group">
                                <label class="form-label">Unggah Surat Pernyataan Komitmen</label>
                                <div class="form-hint">jika sudah ada dan di tandatangani pejabat pembuat komitmen, namun
                                    jika belum maka WAJIB disertakan saat registrasi ulang di Puslatbang KMP</div>
                                <div class="form-file">
                                    <input type="file" name="file_surat_komitmen" id="file_surat_komitmen"
                                        class="form-file-input @error('file_surat_komitmen') error @enderror" accept=".pdf">
                                    <label class="form-file-label" for="file_surat_komitmen">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>{{ $isEdit && $pendaftaran->file_surat_komitmen ? 'Ganti Surat Komitmen' : 'Klik untuk mengunggah file PDF (maks. 1MB)' }}</span>
                                    </label>
                                    <div class="form-file-name" id="fileSuratKomitmenName">
                                        @if($isEdit && $pendaftaran->file_surat_komitmen)
                                            File sudah ada: {{ basename($pendaftaran->file_surat_komitmen) }}
                                        @elseif(old('file_surat_komitmen'))
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

                            <!-- Surat Kelulusan Seleksi -->
                            <div class="form-group">
                                <label class="form-label">Unggah Scan Surat Keterangan Kelulusan/Hasil Seleksi calon
                                    peserta</label>
                                <div class="form-hint">bagi calon peserta yang masih menduduki jabatan administrator/Eselon
                                    III</div>
                                <div class="form-file">
                                    <input type="file" name="file_surat_kelulusan_seleksi" id="file_surat_kelulusan_seleksi"
                                        class="form-file-input @error('file_surat_kelulusan_seleksi') error @enderror"
                                        accept=".pdf">
                                    <label class="form-file-label" for="file_surat_kelulusan_seleksi">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>{{ $isEdit && $pendaftaran->file_surat_kelulusan_seleksi ? 'Ganti Surat Kelulusan' : 'Klik untuk mengunggah file PDF (maks. 1MB)' }}</span>
                                    </label>
                                    <div class="form-file-name" id="fileSuratKelulusanName">
                                        @if($isEdit && $pendaftaran->file_surat_kelulusan_seleksi)
                                            File sudah ada: {{ basename($pendaftaran->file_surat_kelulusan_seleksi) }}
                                        @elseif(old('file_surat_kelulusan_seleksi'))
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


                            <!-- Surat Sehat dan Bebas Narkoba -->
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
                                            @elseif(old('file_surat_sehat'))
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
                                    <label class="form-label">Unggah Surat Keterangan Bebas Narkoba</label>
                                    <div class="form-hint">Format PDF, maksimal 1MB</div>
                                    <div class="form-file">
                                        <input type="file" name="file_surat_bebas_narkoba" id="file_surat_bebas_narkoba"
                                            class="form-file-input @error('file_surat_bebas_narkoba') error @enderror"
                                            accept=".pdf">
                                        <label class="form-file-label" for="file_surat_bebas_narkoba">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <span>{{ $isEdit && $pendaftaran->file_surat_bebas_narkoba ? 'Ganti Surat Bebas Narkoba' : 'Klik untuk mengunggah file PDF (maks. 1MB)' }}</span>
                                        </label>
                                        <div class="form-file-name" id="fileSuratBebasNarkobaName">
                                            @if($isEdit && $pendaftaran->file_surat_bebas_narkoba)
                                                File sudah ada: {{ basename($pendaftaran->file_surat_bebas_narkoba) }}
                                            @elseif(old('file_surat_bebas_narkoba'))
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

        /* Additional Styles for Edit Mode */
        .current-file-preview {
            margin-bottom: 15px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 6px;
            border: 1px solid #e9ecef;
        }

        .current-file-preview img {
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }

        .form-input[readonly],
        .form-select[disabled] {
            background-color: #e9ecef;
            opacity: 0.8;
            cursor: not-allowed;
        }

        .text-muted.d-block {
            font-size: 0.85rem;
            margin-top: 4px;
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
        #info-pic-angkatan {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

#info-pic-angkatan strong {
    color: var(--primary-color);
    font-size: 1rem;
}

#info-pic-angkatan small {
    color: var(--gray-color);
    font-size: 0.85rem;
}

#info-pic-angkatan small i {
    margin-right: 5px;
    color: var(--accent-color);
}
    </style>
@endsection

@section('scripts')
    <script>
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
                opt.value = ndh;
                opt.textContent = `NDH ${ndh}`;
                if (currentNdh && parseInt(currentNdh) === ndh) opt.selected = true;
                ndhSelect.appendChild(opt);
            });

            // Jika edit mode & NDH peserta ini sudah terpakai
            if (currentNdh && !result.data.includes(parseInt(currentNdh))) {
                const opt = document.createElement('option');
                opt.value = currentNdh;
                opt.textContent = `NDH ${currentNdh} (Saat ini)`;
                opt.selected = true;
                ndhSelect.insertBefore(opt, ndhSelect.children[1]);
            }

            ndhSelect.disabled = false;
            document.getElementById('ndh-info').style.display = 'block';
            document.getElementById('ndh-stats').textContent = `Tersedia: ${result.tersedia}/${result.kuota}`;
        }
    } catch (err) {
        ndhSelect.innerHTML = '<option value="">Error! Refresh halaman</option>';
    }
}
        // ============================================
        // REALTIME AUTO CAPITALIZATION (HURUF PERTAMA SAJA)
        // ============================================
        function setupRealtimeAutoCapitalization() {
            // Field yang ingin auto capitalize huruf pertama setiap kata
            const capitalizeFields = [
                'nama_panggilan',
                'tempat_lahir',
                'nama_pasangan',
                'asal_instansi',
                'unit_kerja',
                'jabatan',
                'bidang_studi',
                'bidang_keahlian',
                'olahraga_hobi',
                'nama_mentor_baru',
                'jabatan_mentor_baru'
            ];

            capitalizeFields.forEach(fieldName => {
                const input = document.querySelector(`[name="${fieldName}"]`);
                if (input) {
                    input.addEventListener('input', function (e) {
                        // Simpan posisi cursor
                        const start = this.selectionStart;
                        const end = this.selectionEnd;

                        // Ambil nilai saat ini
                        const value = this.value;

                        // Kapitalisasi huruf pertama setiap kata
                        let newValue = '';
                        let capitalizeNext = true;

                        for (let i = 0; i < value.length; i++) {
                            const char = value[i];

                            if (capitalizeNext && char.match(/[a-zA-Z]/)) {
                                newValue += char.toUpperCase();
                                capitalizeNext = false;
                            } else {
                                newValue += char;
                            }

                            // Setelah spasi, huruf berikutnya harus dikapital
                            if (char === ' ' || char === '-' || char === "'") {
                                capitalizeNext = true;
                            }
                        }

                        // Update nilai jika ada perubahan
                        if (value !== newValue) {
                            this.value = newValue;

                            // Kembalikan posisi cursor (ditambah 1 karena ada perubahan)
                            const diff = newValue.length - value.length;
                            this.setSelectionRange(start + diff, end + diff);
                        }
                    });

                    // Format nilai yang sudah ada (edit mode)
                    if (input.value) {
                        setTimeout(() => {
                            let value = input.value;
                            let newValue = '';
                            let capitalizeNext = true;

                            for (let i = 0; i < value.length; i++) {
                                const char = value[i];

                                if (capitalizeNext && char.match(/[a-zA-Z]/)) {
                                    newValue += char.toUpperCase();
                                    capitalizeNext = false;
                                } else {
                                    newValue += char;
                                }

                                if (char === ' ' || char === '-' || char === "'") {
                                    capitalizeNext = true;
                                }
                            }

                            if (value !== newValue) {
                                input.value = newValue;
                            }
                        }, 100);
                    }
                }
            });

            // Email field - lowercase semua secara realtime
            const emailFields = ['email_pribadi', 'email_kantor'];
            emailFields.forEach(fieldName => {
                const input = document.querySelector(`[name="${fieldName}"]`);
                if (input) {
                    input.addEventListener('input', function (e) {
                        // Simpan posisi cursor
                        const start = this.selectionStart;
                        const end = this.selectionEnd;

                        // Ubah ke lowercase
                        const value = this.value;
                        const lowercased = value.toLowerCase();

                        // Jika ada perubahan, update nilai
                        if (value !== lowercased) {
                            this.value = lowercased;

                            // Kembalikan posisi cursor
                            this.setSelectionRange(start, end);
                        }
                    });

                    // Format nilai yang sudah ada
                    if (input.value) {
                        setTimeout(() => {
                            input.value = input.value.toLowerCase();
                        }, 100);
                    }
                }
            });
        }

        // ============================================
        // CROPPER FUNCTIONALITY
        // ============================================
        function initCropperFunctionality() {
            let cropper = null;
            let croppedBlob = null;
            let originalFile = null;

            // Elements
            const fileInput = document.getElementById('file_pas_foto');
            const cropperContainer = document.getElementById('cropperPreviewContainer');
            const previewContainer = document.getElementById('preview');
            const imagePreview = document.getElementById('imagePreview');
            const fileDisplay = document.getElementById('filePasFotoName');
            const croppedImageData = document.getElementById('croppedImageData');

            // Control buttons
            const rotateLeftBtn = document.getElementById('rotateLeft');
            const rotateRightBtn = document.getElementById('rotateRight');
            const zoomInBtn = document.getElementById('zoomIn');
            const zoomOutBtn = document.getElementById('zoomOut');
            const cancelCropBtn = document.getElementById('cancelCrop');
            const cropImageBtn = document.getElementById('cropImage');

            // Aspect ratio untuk pasfoto 3:4
            const ASPECT_RATIO = 3 / 4;

            // Event listener untuk file input
            if (fileInput) {
                fileInput.addEventListener('change', handleFileSelect);
            }

            // Control buttons event listeners
            if (rotateLeftBtn) rotateLeftBtn.addEventListener('click', rotateLeft);
            if (rotateRightBtn) rotateRightBtn.addEventListener('click', rotateRight);
            if (zoomInBtn) zoomInBtn.addEventListener('click', zoomIn);
            if (zoomOutBtn) zoomOutBtn.addEventListener('click', zoomOut);
            if (cancelCropBtn) cancelCropBtn.addEventListener('click', cancelCrop);
            if (cropImageBtn) cropImageBtn.addEventListener('click', cropAndSave);

            // Functions
            function handleFileSelect(e) {
                const file = e.target.files[0];
                if (!file) return;

                // Validasi file
                if (!file.type.match('image/jpeg') && !file.type.match('image/png')) {
                    alert('Hanya file JPG/PNG yang diperbolehkan');
                    this.value = '';
                    return;
                }

                if (file.size > 1024 * 1024) { // 1MB
                    alert('Ukuran file maksimal 1MB');
                    this.value = '';
                    return;
                }

                originalFile = file;

                // Baca file sebagai URL
                const reader = new FileReader();
                reader.onload = function (event) {
                    // Tampilkan container cropper
                    cropperContainer.style.display = 'block';

                    // Set image source
                    imagePreview.src = event.target.result;

                    // Initialize cropper
                    setTimeout(() => {
                        initCropper();
                    }, 100);
                };
                reader.readAsDataURL(file);
            }

            function initCropper() {
                if (cropper) {
                    cropper.destroy();
                }

                cropper = new Cropper(imagePreview, {
                    aspectRatio: ASPECT_RATIO,
                    viewMode: 2,
                    preview: previewContainer,
                    autoCropArea: 0.8,
                    responsive: true,
                    restore: true,
                    guides: true,
                    center: true,
                    highlight: false,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    toggleDragModeOnDblclick: false,
                    ready: function () {
                        this.cropper.setAspectRatio(ASPECT_RATIO);
                    }
                });
            }

            function rotateLeft() {
                if (cropper) {
                    cropper.rotate(-90);
                }
            }

            function rotateRight() {
                if (cropper) {
                    cropper.rotate(90);
                }
            }

            function zoomIn() {
                if (cropper) {
                    cropper.zoom(0.1);
                }
            }

            function zoomOut() {
                if (cropper) {
                    cropper.zoom(-0.1);
                }
            }

            function cancelCrop() {
                // Reset everything
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }

                cropperContainer.style.display = 'none';

                // Show upload container again
                document.getElementById('pasFotoUploadContainer').style.display = 'block';

                // Reset file input
                if (fileInput) {
                    fileInput.value = '';
                }

                croppedBlob = null;
                if (croppedImageData) croppedImageData.value = '';

                // Reset display
                if (fileDisplay) {
                    fileDisplay.textContent = 'Belum ada file dipilih';
                }
            }

            function cropAndSave() {
                if (!cropper) {
                    alert('Silakan pilih gambar terlebih dahulu');
                    return;
                }

                // Get cropped canvas dengan ukuran optimal untuk pasfoto
                const canvas = cropper.getCroppedCanvas({
                    width: 354,  // 3cm @ 300dpi ≈ 354 pixels
                    height: 472, // 4cm @ 300dpi ≈ 472 pixels
                    fillColor: '#fff',
                    imageSmoothingEnabled: true,
                    imageSmoothingQuality: 'high'
                });

                // Convert canvas to blob
                canvas.toBlob(function (blob) {
                    if (!blob) {
                        alert('Gagal memproses gambar');
                        return;
                    }

                    // Simpan blob untuk nanti diupload
                    croppedBlob = blob;

                    // Convert blob to base64 untuk hidden input
                    const reader = new FileReader();
                    reader.onloadend = function () {
                        if (croppedImageData) {
                            croppedImageData.value = reader.result;
                        }

                        // Update file display
                        if (fileDisplay) {
                            fileDisplay.textContent = 'Pasfoto siap diupload (' + formatBytes(blob.size) + ')';
                        }

                        // Hide cropper container
                        cropperContainer.style.display = 'none';

                        // Show upload container
                        document.getElementById('pasFotoUploadContainer').style.display = 'block';

                        // Clean up cropper
                        if (cropper) {
                            cropper.destroy();
                            cropper = null;
                        }
                    };
                    reader.readAsDataURL(blob);

                }, 'image/jpeg', 0.9); // Quality: 0.9 (90%)
            }

            function formatBytes(bytes, decimals = 2) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const dm = decimals < 0 ? 0 : decimals;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
            }

            // Return functions that might be needed externally
            return {
                getCroppedBlob: () => croppedBlob,
                hasCroppedImage: () => !!croppedBlob
            };
        }

        document.addEventListener('DOMContentLoaded', function () {
            // ============================================
            // KONFIGURASI AWAL
            // ============================================
            const isEdit = @json($isEdit);
            const jenis = @json($jenis);
            let selectedAngkatan = null;
            const picDataByAngkatan = @json($picDataByAngkatan ?? []);

            // Initialize cropper
            const cropperFunctions = initCropperFunctionality();

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

            // Status perkawinan dan nama pasangan
            const statusPerkawinanSelect = document.getElementById('status_perkawinan');
            const namaPasanganInput = document.getElementById('nama_pasangan');

            // Golongan ruang dan pangkat
            const golonganRuangSelect = document.getElementById('golongan_ruang');
            const pangkatInput = document.getElementById('pangkat');

            setupRealtimeAutoCapitalization();

            // Pangkat mapping
            const pangkatMapping = {
                'II/a': { pangkat: 'Pengatur Muda', description: 'Golongan IIa - Pengatur Muda' },
                'II/b': { pangkat: 'Pengatur Muda Tingkat I', description: 'Golongan IIb - Pengatur Muda Tingkat I' },
                'II/c': { pangkat: 'Pengatur', description: 'Golongan IIc - Pengatur' },
                'II/d': { pangkat: 'Pengatur Tingkat I', description: 'Golongan IId - Pengatur Tingkat I' },
                'III/a': { pangkat: 'Penata Muda', description: 'Golongan IIIa - Penata Muda' },
                'III/b': { pangkat: 'Penata Muda Tingkat I', description: 'Golongan IIIb - Penata Muda Tingkat I' },
                'III/c': { pangkat: 'Penata', description: 'Golongan IIIc - Penata' },
                'III/d': { pangkat: 'Penata Tingkat I', description: 'Golongan IIId - Penata Tingkat I' },
                'IV/a': { pangkat: 'Pembina', description: 'Golongan IVa - Pembina' },
                'IV/b': { pangkat: 'Pembina Tingkat I', description: 'Golongan IVb - Pembina Tingkat I' },
                'IV/c': { pangkat: 'Pembina Muda', description: 'Golongan IVc - Pembina Muda' },
                'IV/d': { pangkat: 'Pembina Madya', description: 'Golongan IVd - Pembina Madya' }
            };

            // Mentor elements
            const mentorContainer = document.getElementById('mentor-container');
            const sudahAdaMentorSelect = document.getElementById('sudah_ada_mentor');
            const mentorModeSelect = document.getElementById('mentor_mode');
            const selectMentorForm = document.getElementById('select-mentor-form');
            const addMentorForm = document.getElementById('add-mentor-form');
            const mentorSelect = document.getElementById('id_mentor');
            const namaMentorSelect = document.getElementById('nama_mentor_select');
            const nipMentorSelect = document.getElementById('nip_mentor_select');
            const jabatanMentorSelect = document.getElementById('jabatan_mentor_select');
            const nomorRekeningMentorSelect = document.getElementById('nomor_rekening_mentor_select');
            const npwpMentorSelect = document.getElementById('npwp_mentor_select');

            // Provinsi & Kabupaten elements
            const provinsiSelect = document.getElementById('id_provinsi');
            const kabupatenSelect = document.getElementById('id_kabupaten_kota');

            // File input elements - semua file inputs (kecuali file_pas_foto yang sudah ada cropper)
            const fileInputs = [
                { input: document.getElementById('file_ktp'), display: document.getElementById('fileKtpName') },
                { input: document.getElementById('file'), display: document.getElementById('filee') },
                { input: document.getElementById('lembar_pengesahan'), display: document.getElementById('lembar_pengesahan_name') },
                { input: document.getElementById('file_sk_jabatan'), display: document.getElementById('fileSkJabatanName') },
                { input: document.getElementById('file_sk_pangkat'), display: document.getElementById('fileSkPangkatName') },
                { input: document.getElementById('file_surat_kesediaan'), display: document.getElementById('fileSuratKesediaanName') },
                { input: document.getElementById('file_surat_komitmen'), display: document.getElementById('fileSuratKomitmenName') },
                { input: document.getElementById('file_pakta_integritas'), display: document.getElementById('filePaktaIntegritasName') },
                { input: document.getElementById('file_surat_tugas'), display: document.getElementById('fileSuratTugasName') },
                { input: document.getElementById('file_surat_kelulusan_seleksi'), display: document.getElementById('fileSuratKelulusanName') },
                { input: document.getElementById('file_surat_sehat'), display: document.getElementById('fileSuratSehatName') },
                { input: document.getElementById('file_surat_bebas_narkoba'), display: document.getElementById('fileSuratBebasNarkobaName') },
                { input: document.getElementById('file_surat_pernyataan_administrasi'), display: document.getElementById('fileSuratPernyataanName') },
                { input: document.getElementById('file_persetujuan_mentor'), display: document.getElementById('filePersetujuanMentorName') },
                { input: document.getElementById('file_sertifikat_penghargaan'), display: document.getElementById('fileSertifikatName') }
            ].filter(item => item.input); // Hanya yang ada di DOM

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
            // INITIALIZE EDIT MODE
            // ============================================
            if (isEdit) {
                // Auto-enable next button pada step 1
                nextToStep2Btn.disabled = false;

                // Load kabupaten saat halaman pertama kali dibuka (edit mode)
                setTimeout(() => {
                    if (provinsiSelect && provinsiSelect.value) {
                        provinsiSelect.dispatchEvent(new Event('change'));
                    }

                    // Auto skip ke step 2 untuk edit mode
                    setTimeout(() => {
                        moveToStep(1);
                    }, 800);
                }, 500);

                // Untuk edit, file input tidak required
                document.querySelectorAll('input[type="file"]').forEach(input => {
                    input.removeAttribute('required');
                });
            }

            // ============================================
            // STATUS PERKAWINAN HANDLER
            // ============================================
            if (statusPerkawinanSelect && namaPasanganInput) {
                statusPerkawinanSelect.addEventListener('change', function () {
                    if (this.value === 'Menikah') {
                        namaPasanganInput.disabled = false;
                        namaPasanganInput.placeholder = "Masukkan nama pasangan";
                    } else {
                        namaPasanganInput.disabled = true;
                        namaPasanganInput.value = '';
                        namaPasanganInput.placeholder = "Hanya tersedia jika status Menikah";
                    }
                });

                // Trigger change jika sudah ada nilai
                if (statusPerkawinanSelect.value) {
                    statusPerkawinanSelect.dispatchEvent(new Event('change'));
                }
            }

            // ============================================
            // GOLONGAN RUANG DAN PANGKAT HANDLER
            // ============================================
            if (golonganRuangSelect && pangkatInput) {
                golonganRuangSelect.addEventListener('change', function () {
                    const golongan = this.value;

                    if (golongan && pangkatMapping[golongan]) {
                        pangkatInput.value = pangkatMapping[golongan].pangkat;
                    } else {
                        pangkatInput.value = '';
                    }
                });

                // Trigger change jika sudah ada nilai (edit mode atau validation failed)
                if (golonganRuangSelect.value) {
                    golonganRuangSelect.dispatchEvent(new Event('change'));
                } else if (window.oldValues && window.oldValues.golongan_ruang) {
                    // Set nilai dari old values
                    golonganRuangSelect.value = window.oldValues.golongan_ruang;
                    golonganRuangSelect.dispatchEvent(new Event('change'));
                }
            }

            // ============================================
            // STEP 1: ANGKATAN SELECTION
            // ============================================
            const wilayahWrapper = document.getElementById('info-wilayah-wrapper');
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
                    kategori: selectedOption.dataset.kategori,
                    wilayah: selectedOption.dataset.wilayah,
                    status: selectedOption.dataset.status
                };

                // Update UI
                currentAngkatanName.textContent = `${selectedAngkatan.nama} (${selectedAngkatan.tahun})`;
                currentAngkatanName2.textContent = `${selectedAngkatan.nama} (${selectedAngkatan.tahun})`;
                currentAngkatanName3.textContent = `${selectedAngkatan.nama} (${selectedAngkatan.tahun})`;

                // Show angkatan info
                document.getElementById('info-nama-angkatan').textContent = selectedAngkatan.nama;
                document.getElementById('info-tahun-angkatan').textContent = selectedAngkatan.tahun;
                document.getElementById('info-kategori-angkatan').textContent = selectedAngkatan.kategori;
                document.getElementById('info-kuota-angkatan').textContent = selectedAngkatan.kuota;

                if (selectedAngkatan.wilayah) {
                    document.getElementById('info-wilayah-angkatan').textContent = selectedAngkatan.wilayah;
                    wilayahWrapper.style.display = 'flex';
                } else {
                    wilayahWrapper.style.display = 'none';
                }

                const statusBadge = document.getElementById('info-status-angkatan');
                statusBadge.textContent = selectedAngkatan.status;
                statusBadge.className = 'info-badge';
                if (selectedAngkatan.status === 'Aktif' || selectedAngkatan.status === 'Dibuka') {
                    statusBadge.style.background = 'var(--success-color)';
                } else if (selectedAngkatan.status === 'Penuh') {
                    statusBadge.style.background = 'var(--danger-color)';
                } else {
                    statusBadge.style.background = 'var(--warning-color)';
                }

                const picWrapper = document.getElementById('info-pic-wrapper');
                const picInfo = document.getElementById('info-pic-angkatan');
                
                if (picDataByAngkatan[selectedAngkatan.id]) {
                    const pic = picDataByAngkatan[selectedAngkatan.id];
                    picInfo.innerHTML = `
                        <strong>${pic.nama} (${pic.no_telp})</strong>
                    `;
                    picWrapper.style.display = 'flex';
                } else {
                    picInfo.innerHTML = '<em style="color: var(--gray-color);">PIC belum ditentukan</em>';
                    picWrapper.style.display = 'flex';
                }

                angkatanInfo.style.display = 'block';
                nextToStep2Btn.disabled = false;
                // Load NDH
const jenisPelatihanId = @json($jenisPelatihanId);
const currentNdh = @json($isEdit && $pesertaData ? $pesertaData->ndh : null);
loadAvailableNdh(selectedAngkatan.id, jenisPelatihanId, currentNdh);
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
                        if (mentorSelect && mentorSelect.options.length <= 1) {
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

                            // Populate fields with mentor data
                            if (namaMentorSelect) namaMentorSelect.value = selectedOption.dataset.nama || '';
                            if (nipMentorSelect) nipMentorSelect.value = selectedOption.dataset.nip || '';
                            if (jabatanMentorSelect) jabatanMentorSelect.value = selectedOption.dataset.jabatan || '';
                            if (nomorRekeningMentorSelect) nomorRekeningMentorSelect.value = selectedOption.dataset.nomorRekening || '';
                            if (npwpMentorSelect) npwpMentorSelect.value = selectedOption.dataset.npwp || '';
                        } else {
                            resetMentorFields();
                        }
                    });
                }

                // Trigger change if value exists (for old form values)
                if (sudahAdaMentorSelect.value) {
                    sudahAdaMentorSelect.dispatchEvent(new Event('change'));
                }
                if (mentorModeSelect && mentorModeSelect.value) {
                    mentorModeSelect.dispatchEvent(new Event('change'));
                }
                if (mentorSelect && mentorSelect.value) {
                    mentorSelect.dispatchEvent(new Event('change'));
                }
            }

            function resetMentorFields() {
                if (namaMentorSelect) namaMentorSelect.value = '';
                if (nipMentorSelect) nipMentorSelect.value = '';
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
                        option.dataset.nip = mentor.nip_mentor;
                        option.dataset.jabatan = mentor.jabatan_mentor;
                        option.dataset.nomorRekening = mentor.nomor_rekening_mentor || mentor.nomor_rekening || '';
                        option.dataset.npwp = mentor.npwp_mentor || mentor.npwp || '';

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
            if (provinsiSelect) {
                provinsiSelect.addEventListener('change', function () {
                    const provinsiId = this.value;

                    if (!provinsiId) {
                        kabupatenSelect.innerHTML = '<option value="">Pilih Kabupaten/Kota (Pilih Provinsi Dahulu)</option>';
                        kabupatenSelect.disabled = true;
                        return;
                    }

                    kabupatenSelect.innerHTML = '<option value="">Memuat kabupaten/kota...</option>';
                    kabupatenSelect.disabled = true;

                    try {
                        // Filter kabupaten dari data yang sudah ada (dikirim dari controller)
                        const allKabupaten = @json($kabupatenList);
                        const filteredKabupaten = allKabupaten.filter(kab => kab.province_id == provinsiId);

                        kabupatenSelect.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
                        kabupatenSelect.disabled = false;

                        if (filteredKabupaten.length > 0) {
                            filteredKabupaten.forEach(kabupaten => {
                                const option = document.createElement('option');
                                option.value = kabupaten.id;
                                option.textContent = kabupaten.name;
                                kabupatenSelect.appendChild(option);
                            });

                            // Set nilai untuk edit mode
                            const currentKabupatenId = @json($isEdit && $kepegawaianData ? $kepegawaianData->id_kabupaten_kota : null);
                            if (currentKabupatenId) {
                                kabupatenSelect.value = currentKabupatenId;
                            }

                            // Set old value jika ada dari validation
                            if (window.oldValues && window.oldValues.id_kabupaten_kota) {
                                kabupatenSelect.value = window.oldValues.id_kabupaten_kota;
                            }
                        } else {
                            kabupatenSelect.innerHTML = '<option value="">Tidak ada data kabupaten</option>';
                        }
                    } catch (error) {
                        console.error('Error filtering kabupaten:', error);
                        kabupatenSelect.innerHTML = '<option value="">Error loading data</option>';
                        kabupatenSelect.disabled = false;
                    }
                });

                // Trigger change jika sudah ada nilai (edit mode)
                if (provinsiSelect.value) {
                    setTimeout(() => {
                        provinsiSelect.dispatchEvent(new Event('change'));
                    }, 300);
                }
            }

            // ============================================
            // FILE INPUT HANDLERS
            // ============================================
            fileInputs.forEach(({ input, display }) => {
                if (input) {
                    input.addEventListener('change', function () {
                        const fileName = this.files[0]?.name || 'Belum ada file dipilih';
                        if (display) display.textContent = fileName;

                        // Remove error class when file is selected
                        this.classList.remove('error');
                    });
                }
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
                if (angkatanSelect.value) {
                    // Update current angkatan name
                    const selectedOption = angkatanSelect.options[angkatanSelect.selectedIndex];
                    if (selectedOption) {
                        const nama = selectedOption.dataset.nama || '';
                        const tahun = selectedOption.dataset.tahun || '';
                        currentAngkatanName.textContent = `${nama} (${tahun})`;
                        currentAngkatanName2.textContent = `${nama} (${tahun})`;
                        currentAngkatanName3.textContent = `${nama} (${tahun})`;
                    }
                    moveToStep(2);
                }
            });

            nextToStep3Btn.addEventListener('click', () => {
                // Untuk step 2, hanya validasi NIP saja
                const nipField = document.querySelector('input[name="nip_nrp"]');
                if (nipField && !nipField.value.trim()) {
                    nipField.classList.add('error');
                    nipField.focus();
                    nipField.scrollIntoView({ behavior: 'smooth', block: 'center' });

                    // Show notification
                    const notification = document.createElement('div');
                    notification.className = 'notification error';
                    notification.innerHTML = `
                        <div class="notification-content">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>NIP/NRP wajib diisi</span>
                        </div>
                    `;
                    document.body.appendChild(notification);

                    setTimeout(() => {
                        notification.classList.add('show');
                    }, 10);

                    setTimeout(() => {
                        notification.classList.remove('show');
                        setTimeout(() => {
                            notification.remove();
                        }, 300);
                    }, 3000);
                    return;
                }

                moveToStep(3);
            });

            nextToStep4Btn.addEventListener('click', () => {
                moveToStep(4);
            });

            backToStep1Btn.addEventListener('click', () => moveToStep(1));
            backToStep2Btn.addEventListener('click', () => moveToStep(2));
            backToStep3Btn.addEventListener('click', () => moveToStep(3));

            // ============================================
            // FORM SUBMISSION HANDLER (MODIFIED FOR CROPPER)
            // ============================================
            document.getElementById('pesertaForm').addEventListener('submit', async function (e) {
                e.preventDefault();

                const submitBtn = document.getElementById('submit-form');
                const originalText = submitBtn.innerHTML;

                // Show loading state
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ' + (isEdit ? 'Mengupdate...' : 'Menyimpan...');
                submitBtn.disabled = true;

                // Validasi minimal: NIP dan Angkatan
                const nipField = document.querySelector('input[name="nip_nrp"]');
                const angkatanField = document.querySelector('select[name="id_angkatan"]');

                let hasError = false;

                // Clear previous client-side errors
                document.querySelectorAll('.client-error').forEach(el => el.remove());

                // Validasi NIP
                if (!nipField.value.trim()) {
                    hasError = true;
                    nipField.classList.add('error');

                    const formGroup = nipField.closest('.form-group');
                    if (formGroup) {
                        const errorMsg = document.createElement('small');
                        errorMsg.className = 'text-danger client-error';
                        errorMsg.textContent = 'NIP/NRP wajib diisi';
                        formGroup.appendChild(errorMsg);
                    }
                }

                // Validasi Angkatan
                if (!angkatanField.value) {
                    hasError = true;
                    angkatanField.classList.add('error');

                    const formGroup = angkatanField.closest('.form-group');
                    if (formGroup) {
                        const errorMsg = document.createElement('small');
                        errorMsg.className = 'text-danger client-error';
                        errorMsg.textContent = 'Angkatan wajib dipilih';
                        formGroup.appendChild(errorMsg);
                    }
                }

                if (hasError) {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;

                    // Scroll ke error pertama
                    const firstError = document.querySelector('.error');
                    if (firstError) {
                        // Determine which step has the error
                        if (firstError.closest('#step1-content')) {
                            moveToStep(1);
                        } else if (firstError.closest('#step2-content')) {
                            moveToStep(2);
                        }

                        firstError.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }

                    return false;
                }

                // Handle cropped image jika ada
                const fileInput = document.getElementById('file_pas_foto');
                if (cropperFunctions.hasCroppedImage()) {
                    const croppedBlob = cropperFunctions.getCroppedBlob();

                    // Create a new File from blob
                    const croppedFile = new File([croppedBlob], 'pasfoto_cropped.jpg', {
                        type: 'image/jpeg',
                        lastModified: Date.now()
                    });

                    // Create a new DataTransfer object
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(croppedFile);

                    // Replace the file input files
                    fileInput.files = dataTransfer.files;
                }

                // Collect form data
                const formData = new FormData(this);

                // Add CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                try {
                    const response = await fetch(this.action, {
                        method: this.method,
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        showSuccessMessage(isEdit ? 'Peserta berhasil diperbarui!' : 'Peserta berhasil ditambahkan!');

                        // Redirect sesuai jenis
                        setTimeout(() => {
                            const jenis = "{{ $jenis }}";
                            const redirectUrl = data.redirect_url || `/peserta/${jenis}`;
                            window.location.href = redirectUrl;
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

                            if (data.errors.id_angkatan) {
                                moveToStep(1);

                                // Tampilkan error di field angkatan
                                const angkatanField = document.querySelector('[name="id_angkatan"]');
                                if (angkatanField) {
                                    angkatanField.classList.add('error');

                                    const formGroup = angkatanField.closest('.form-group');
                                    if (formGroup) {
                                        const errorMsg = document.createElement('small');
                                        errorMsg.className = 'text-danger server-error';
                                        errorMsg.textContent = data.errors.id_angkatan[0];
                                        formGroup.appendChild(errorMsg);
                                    }

                                    // Scroll ke field angkatan
                                    setTimeout(() => {
                                        angkatanField.scrollIntoView({
                                            behavior: 'smooth',
                                            block: 'center'
                                        });
                                    }, 300);
                                }

                                // Tampilkan notifikasi error
                                showErrorMessage(data.errors.id_angkatan[0]);

                                return; // Stop di sini, jangan lanjut ke loop biasa
                            }

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

            // Initialize angkatan change if value exists
            if (angkatanSelect.value) {
                angkatanSelect.dispatchEvent(new Event('change'));
            }

            // Initialize provinsi change for edit mode
            if (isEdit && provinsiSelect && provinsiSelect.value) {
                setTimeout(() => {
                    provinsiSelect.dispatchEvent(new Event('change'));
                }, 500);
            }

            // Initialize status perkawinan from old values if exists
            if (validationFailed && window.oldValues.status_perkawinan) {
                if (statusPerkawinanSelect) {
                    statusPerkawinanSelect.value = window.oldValues.status_perkawinan;
                    statusPerkawinanSelect.dispatchEvent(new Event('change'));
                }
            }

            // Initialize golongan ruang from old values if exists
            if (validationFailed && window.oldValues.golongan_ruang) {
                if (golonganRuangSelect) {
                    golonganRuangSelect.value = window.oldValues.golongan_ruang;
                    golonganRuangSelect.dispatchEvent(new Event('change'));
                }
            }
        });
    </script>
@endsection