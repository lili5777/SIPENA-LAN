<div class="form-section-header">
    <i class="fas fa-user-graduate"></i> Form Pembaruan Data LATSAR
</div>

<input type="hidden" name="peserta_id" id="peserta_id" value="{{ $peserta['id'] ?? '' }}">
<input type="hidden" name="pendaftaran_id" id="pendaftaran_id" value="{{ $pendaftaran['id'] ?? '' }}">


<div class="form-group">
    <label class="form-label required">Pilih (NDH) Sesuai Surat Pemanggilan</label>
    <select name="ndh" id="ndh" class="form-select @error('ndh') error @enderror" required>
        <option value="">-- Pilih NDH --</option>
        <!-- NDH akan dimuat via JavaScript -->
    </select>
    <small class="form-hint" id="ndh-hint">
        <i class="fas fa-info-circle"></i> 
        <span id="ndh-info">Memuat daftar NDH yang tersedia...</span>
    </small>
    @error('ndh')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label required">Nama Lengkap dan Gelar (Sesuai SK)</label>
        <input type="text" name="nama_lengkap" class="form-input @error('nama_lengkap') error @enderror"
            value="{{ $peserta['nama_lengkap'] ?? old('nama_lengkap') }}" required
            placeholder="Contoh: Muhammad Ali, S.H., M.H.">
        @error('nama_lengkap')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label required">NIP</label>
        <input type="text" name="nip_nrp" class="form-input @error('nip_nrp') error @enderror"
            value="{{ $peserta['nip_nrp'] ?? old('nip_nrp') }}" required readonly
            placeholder="Contoh: 198108122006041001">
        <small class="form-hint">NIP/NRP tidak dapat diubah</small>
        @error('nip_nrp')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>
<div class="form-row">
    <div class="form-group">
        <label class="form-label required">Nama Panggilan</label>
        <input type="text" name="nama_panggilan" class="form-input capitalize @error('nama_panggilan') error @enderror"
            value="{{ $peserta['nama_panggilan'] ?? old('nama_panggilan') }}" required placeholder="Contoh: Rudi">
        <small class="form-hint">Huruf akan otomatis kapital</small>
        @error('nama_panggilan')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label required">Jenis Kelamin</label>
        <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') error @enderror" required>
            <option value="">Pilih</option>
            <option value="Laki-laki" {{ ($peserta['jenis_kelamin'] ?? old('jenis_kelamin')) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
            <option value="Perempuan" {{ ($peserta['jenis_kelamin'] ?? old('jenis_kelamin')) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
        </select>
        @error('jenis_kelamin')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label required">Tempat Lahir (sesuai SK)</label>
        <input type="text" name="tempat_lahir" class="form-input capitalize @error('tempat_lahir') error @enderror"
            value="{{ $peserta['tempat_lahir'] ?? old('tempat_lahir') }}" required placeholder="Contoh: Jakarta">
        <small class="form-hint">Huruf akan otomatis kapital</small>
        @error('tempat_lahir')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label required">Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" class="form-input @error('tanggal_lahir') error @enderror"
            value="{{ $peserta['tanggal_lahir'] ?? old('tanggal_lahir') }}" required placeholder="Contoh: 1981-08-12">
        @error('tanggal_lahir')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label required">
            Email Pribadi (Aktif)
        </label>
        <input type="email" name="email_pribadi" class="form-input lowercase @error('email_pribadi') error @enderror"
            value="{{ $peserta['email_pribadi'] ?? old('email_pribadi') }}" required
            placeholder="Contoh: muhammad.ali@example.com">
        <small class="form-hint">
            Wajib email aktif! Akun dan link Grup WhatsApp akan dikirim melalui email ini.
        </small>
        @error('email_pribadi')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label required">Nomor HP/WhatsApp</label>
        <input type="tel" name="nomor_hp" class="form-input @error('nomor_hp') error @enderror"
            value="{{ $peserta['nomor_hp'] ?? old('nomor_hp') }}" required placeholder="Contoh: 081234567890">
        @error('nomor_hp')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label required">Olahraga Kegemaran</label>
        <input type="text" name="olahraga_hobi" class="form-input capitalize @error('olahraga_hobi') error @enderror"
            value="{{ $peserta['olahraga_hobi'] ?? old('olahraga_hobi') }}" required
            placeholder="Contoh: Sepak Bola, Badminton">
        <small class="form-hint">Huruf akan otomatis kapital</small>
        @error('olahraga_hobi')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label required">Apakah Anda merokok?</label>
        <select name="perokok" class="form-select @error('perokok') error @enderror" required>
            <option value="">Pilih</option>
            <option value="Ya" {{ ($peserta['perokok'] ?? old('perokok')) == 'Ya' ? 'selected' : '' }}>Ya</option>
            <option value="Tidak" {{ ($peserta['perokok'] ?? old('perokok')) == 'Tidak' ? 'selected' : '' }}>Tidak
            </option>
        </select>
        @error('perokok')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label required">Agama</label>
        <select name="agama" class="form-select @error('agama') error @enderror" required>
            <option value="">Pilih</option>
            <option value="Islam" {{ ($peserta['agama'] ?? old('agama')) == 'Islam' ? 'selected' : '' }}>Islam</option>
            <option value="Kristen" {{ ($peserta['agama'] ?? old('agama')) == 'Kristen' ? 'selected' : '' }}>Kristen</option>
            <option value="Katolik" {{ ($peserta['agama'] ?? old('agama')) == 'Katolik' ? 'selected' : '' }}>Katolik</option>
            <option value="Hindu" {{ ($peserta['agama'] ?? old('agama')) == 'Hindu' ? 'selected' : '' }}>Hindu</option>
            <option value="Buddha" {{ ($peserta['agama'] ?? old('agama')) == 'Buddha' ? 'selected' : '' }}>Buddha</option>
            <option value="Konghucu" {{ ($peserta['agama'] ?? old('agama')) == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
        </select>
        @error('agama')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label required">Status Perkawinan</label>
        <select name="status_perkawinan" id="status_perkawinan"
            class="form-select @error('status_perkawinan') error @enderror" required>
            <option value="">Pilih</option>
            <option value="Belum Menikah" {{ ($peserta['status_perkawinan'] ?? old('status_perkawinan')) == 'Belum Menikah' ? 'selected' : '' }}>Belum Menikah</option>
            <option value="Menikah" {{ ($peserta['status_perkawinan'] ?? old('status_perkawinan')) == 'Menikah' ? 'selected' : '' }}>Menikah</option>
            <option value="Duda" {{ ($peserta['status_perkawinan'] ?? old('status_perkawinan')) == 'Duda' ? 'selected' : '' }}>Duda</option>
            <option value="Janda" {{ ($peserta['status_perkawinan'] ?? old('status_perkawinan')) == 'Janda' ? 'selected' : '' }}>Janda</option>
        </select>
        @error('status_perkawinan')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label">Nama Istri/Suami</label>
        <input type="text" name="nama_pasangan" id="nama_pasangan"
            class="form-input capitalize @error('nama_pasangan') error @enderror"
            value="{{ $peserta['nama_pasangan'] ?? old('nama_pasangan') }}" {{ ($peserta['status_perkawinan'] ?? old('status_perkawinan')) != 'Menikah' ? 'disabled' : '' }} placeholder="Contoh: Siti Fatimah">
        <small class="form-hint">Hanya bisa diisi jika status "Menikah". Huruf akan otomatis kapital</small>
        @error('nama_pasangan')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label">Ukuran Baju Kaos</label>
        <select name="ukuran_kaos" class="form-select @error('ukuran_kaos') error @enderror">
            <option value="">Pilih</option>
            @foreach(['XS','S','M','L','XL','XXL','XXXL','XXXXL','XXXXXL','XXXXXXL','XXXXXXXL'] as $size)
                <option value="{{ $size }}" {{ ($peserta['ukuran_kaos'] ?? old('ukuran_kaos')) == $size ? 'selected' : '' }}>{{ $size }}</option>
            @endforeach
        </select>
        @error('ukuran_kaos')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label">Ukuran Baju Taktikal</label>
        <select name="ukuran_training" class="form-select @error('ukuran_training') error @enderror">
            <option value="">Pilih</option>
            @foreach(['XS','S','M','L','XL','XXL','XXXL','XXXXL','XXXXXL','XXXXXXL','XXXXXXXL'] as $size)
                <option value="{{ $size }}" {{ ($peserta['ukuran_training'] ?? old('ukuran_training')) == $size ? 'selected' : '' }}>{{ $size }}</option>
            @endforeach
        </select>
        @error('ukuran_training')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label">Ukuran Celana</label>
        <select name="ukuran_celana" class="form-select @error('ukuran_celana') error @enderror">
            <option value="">Pilih</option>
            @foreach(['XS','S','M','L','XL','XXL','XXXL','XXXXL','XXXXXL','XXXXXXL','XXXXXXXL'] as $size)
                <option value="{{ $size }}" {{ ($peserta['ukuran_celana'] ?? old('ukuran_celana')) == $size ? 'selected' : '' }}>{{ $size }}</option>
            @endforeach
        </select>
        @error('ukuran_celana')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-group">
    <label class="form-label required">Alamat Rumah</label>
    <textarea name="alamat_rumah" class="form-textarea capitalize @error('alamat_rumah') error @enderror" required
        placeholder="Contoh: Jalan Merdeka No. 123, Kelurahan Menteng, Kecamatan Menteng">{{ $peserta['alamat_rumah'] ?? old('alamat_rumah') }}</textarea>
    <small class="form-hint">Huruf akan otomatis kapital setiap kata</small>
    @error('alamat_rumah')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="form-group">
    <label class="form-label">Kondisi Peserta</label>
    <textarea name="kondisi_peserta" class="form-textarea capitalize @error('kondisi_peserta') error @enderror"
        placeholder="Contoh: Sehat, Tidak Memiliki Riwayat Penyakit Berat">{{ $peserta['kondisi_peserta'] ?? old('kondisi_peserta') }}</textarea>
    <small class="form-hint">Huruf akan otomatis kapital setiap kata</small>
    @error('kondisi_peserta')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>


<div class="form-section-header">
    <i class="fas fa-building"></i> Data Instansi
</div>

<div class="form-row">
    {{-- ===== ASAL INSTANSI — SEARCHABLE SELECT ===== --}}
    <div class="form-group">
        <label class="form-label required">Instansi</label>

        {{-- Hidden input dikirim ke server --}}
        <input type="hidden" name="asal_instansi" id="asal_instansi_hidden_partial"
            value="{{ $peserta['kepegawaian']['asal_instansi'] ?? old('asal_instansi') ?? '' }}">

        {{-- Custom select trigger --}}
        <div class="instansi-select-wrapper-partial" style="position:relative;">
            <div id="instansi_trigger_partial"
                class="form-input @error('asal_instansi') error @enderror"
                style="cursor:pointer; display:flex; align-items:center; justify-content:space-between; gap:8px; min-height:50px; user-select:none;">
                <span id="instansi_trigger_label_partial" style="flex:1; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;
                      color:{{ ($peserta['kepegawaian']['asal_instansi'] ?? old('asal_instansi') ?? '') ? 'var(--gray-800, #1f2937)' : 'var(--gray-400, #9ca3af)' }};">
                    {{ ($peserta['kepegawaian']['asal_instansi'] ?? old('asal_instansi') ?? '') ?: 'Pilih asal instansi...' }}
                </span>
                <span style="display:flex; align-items:center; gap:6px; flex-shrink:0;">
                    <span id="instansi_clear_btn_partial"
                        style="display:{{ ($peserta['kepegawaian']['asal_instansi'] ?? old('asal_instansi') ?? '') ? 'flex' : 'none' }};
                               align-items:center; color:#ef4444; cursor:pointer; font-size:1rem; padding:2px 4px;"
                        title="Hapus pilihan">
                        <i class="fas fa-times-circle"></i>
                    </span>
                    <i class="fas fa-chevron-down" id="instansi_chevron_partial"
                       style="color:var(--gray-400, #9ca3af); font-size:0.85rem; transition:transform 0.2s;"></i>
                </span>
            </div>

            {{-- Dropdown panel --}}
            <div id="instansi_dropdown_partial"
                style="display:none; position:absolute; z-index:9999; width:100%;
                       background:white; border:2px solid var(--primary-color, #1a3a6c); border-top:none;
                       border-radius:0 0 10px 10px; box-shadow:0 8px 24px rgba(0,0,0,0.12);">

                {{-- Search box sticky di atas list --}}
                <div style="padding:10px 12px; border-bottom:1px solid var(--gray-200, #e5e7eb); background:var(--gray-50, #f9fafb); position:sticky; top:0; z-index:1;">
                    <div style="position:relative;">
                        <input type="text" id="asal_instansi_search_partial"
                            placeholder="Cari instansi..."
                            autocomplete="off"
                            style="width:100%; padding:9px 36px 9px 12px; border:1.5px solid var(--gray-300, #d1d5db);
                                   border-radius:8px; font-size:0.9rem; outline:none; box-sizing:border-box; font-family:inherit;">
                        <i class="fas fa-search" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); color:var(--gray-400, #9ca3af); font-size:0.85rem; pointer-events:none;"></i>
                    </div>
                    <div id="instansi_count_partial" style="font-size:0.75rem; color:var(--gray-400, #9ca3af); margin-top:5px; padding-left:2px;">
                        Menampilkan {{ count(config('instansi')) }} instansi
                    </div>
                </div>

                {{-- List instansi --}}
                <div id="instansi_list_partial" style="max-height:260px; overflow-y:auto;"></div>
            </div>
        </div>

        @error('asal_instansi')
            <div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
        @enderror
        <small class="form-hint">
            <i class="fas fa-info-circle"></i>
            Klik untuk memilih dari {{ count(config('instansi')) }} instansi. Gunakan pencarian untuk filter.
        </small>
    </div>
    {{-- ===== END ASAL INSTANSI ===== --}}

    <div class="form-group">
        <label class="form-label required">Unit Kerja/Detail Instansi</label>
        <input type="text" name="unit_kerja" class="form-input capitalize @error('unit_kerja') error @enderror"
            placeholder="Contoh: Direktorat Jenderal Pelayanan Kesehatan"
            value="{{ $peserta['kepegawaian']['unit_kerja'] ?? old('unit_kerja') ?? '' }}" required>
        <small class="form-hint">Huruf akan otomatis kapital setiap kata</small>
        @error('unit_kerja')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label required">Provinsi</label>
        <select name="id_provinsi" class="form-select @error('id_provinsi') error @enderror" required>
            <option value="">Pilih Provinsi</option>
            <option value="">Memuat provinsi...</option>
        </select>
        @error('id_provinsi')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label required">Kabupaten/Kota</label>
        <select name="id_kabupaten_kota" class="form-select @error('id_kabupaten_kota') error @enderror" required
            disabled>
            <option value="">Pilih Kabupaten/Kota (Pilih Provinsi Dahulu)</option>
        </select>
        @error('id_kabupaten_kota')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-group">
    <label class="form-label required">Jabatan</label>
    <input type="text" name="jabatan" class="form-input capitalize @error('jabatan') error @enderror"
        value="{{ $peserta['kepegawaian']['jabatan'] ?? old('jabatan') }}" required
        placeholder="Contoh: Perencana Ahli Pertama">
    <small class="form-hint">Huruf akan otomatis kapital setiap kata</small>
    @error('jabatan')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label">Nomor Telepon Kantor</label>
        <input type="tel" name="nomor_telepon_kantor" class="form-input @error('nomor_telepon_kantor') error @enderror"
            value="{{ $peserta['kepegawaian']['nomor_telepon_kantor'] ?? old('nomor_telepon_kantor') }}"
            placeholder="Contoh: 0211234567">
        @error('nomor_telepon_kantor')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label">Email Kantor</label>
        <input type="email" name="email_kantor" class="form-input lowercase @error('email_kantor') error @enderror"
            value="{{ $peserta['kepegawaian']['email_kantor'] ?? old('email_kantor') }}"
            placeholder="Contoh: perencana@kemenkes.go.id">
        <small class="form-hint">Huruf akan otomatis kecil</small>
        @error('email_kantor')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-group">
    <label class="form-label">Alamat Kantor</label>
    <textarea name="alamat_kantor" class="form-textarea capitalize @error('alamat_kantor') error @enderror"
        placeholder="Contoh: Jalan HR Rasuna Said Kaveling 5, Kuningan, Jakarta Selatan">{{ $peserta['kepegawaian']['alamat_kantor'] ?? old('alamat_kantor') }}</textarea>
    <small class="form-hint">Huruf akan otomatis kapital setiap kata</small>
    @error('alamat_kantor')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label required">Golongan Ruang</label>
        <select name="golongan_ruang" id="golongan_ruang" class="form-select @error('golongan_ruang') error @enderror"
            required>
            <option value="">-- Pilih Golongan Ruang --</option>
            <option value="II/a" {{ ($peserta['kepegawaian']['golongan_ruang'] ?? old('golongan_ruang')) == 'II/a' ? 'selected' : '' }}>II/a</option>
            <option value="II/b" {{ ($peserta['kepegawaian']['golongan_ruang'] ?? old('golongan_ruang')) == 'II/b' ? 'selected' : '' }}>II/b</option>
            <option value="II/c" {{ ($peserta['kepegawaian']['golongan_ruang'] ?? old('golongan_ruang')) == 'II/c' ? 'selected' : '' }}>II/c</option>
            <option value="II/d" {{ ($peserta['kepegawaian']['golongan_ruang'] ?? old('golongan_ruang')) == 'II/d' ? 'selected' : '' }}>II/d</option>
            <option value="III/a" {{ ($peserta['kepegawaian']['golongan_ruang'] ?? old('golongan_ruang')) == 'III/a' ? 'selected' : '' }}>III/a</option>
            <option value="III/b" {{ ($peserta['kepegawaian']['golongan_ruang'] ?? old('golongan_ruang')) == 'III/b' ? 'selected' : '' }}>III/b</option>
            <option value="III/c" {{ ($peserta['kepegawaian']['golongan_ruang'] ?? old('golongan_ruang')) == 'III/c' ? 'selected' : '' }}>III/c</option>
            <option value="III/d" {{ ($peserta['kepegawaian']['golongan_ruang'] ?? old('golongan_ruang')) == 'III/d' ? 'selected' : '' }}>III/d</option>
            <option value="IV/a" {{ ($peserta['kepegawaian']['golongan_ruang'] ?? old('golongan_ruang')) == 'IV/a' ? 'selected' : '' }}>IV/a</option>
            <option value="IV/b" {{ ($peserta['kepegawaian']['golongan_ruang'] ?? old('golongan_ruang')) == 'IV/b' ? 'selected' : '' }}>IV/b</option>
            <option value="IV/c" {{ ($peserta['kepegawaian']['golongan_ruang'] ?? old('golongan_ruang')) == 'IV/c' ? 'selected' : '' }}>IV/c</option>
            <option value="IV/d" {{ ($peserta['kepegawaian']['golongan_ruang'] ?? old('golongan_ruang')) == 'IV/d' ? 'selected' : '' }}>IV/d</option>
        </select>
        @error('golongan_ruang')
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                {{ $message }}
            </div>
        @enderror
        <small class="form-hint"><i class="fas fa-info-circle"></i> Contoh: III/A ditulis sebagai IIIA</small>
    </div>

    <div class="form-group">
        <label class="form-label required">Pangkat</label>
        <input type="text" name="pangkat" id="pangkat" class="form-input capitalize @error('pangkat') error @enderror"
            value="{{ old('pangkat', $peserta['kepegawaian']['pangkat'] ?? '') }}" readonly
            placeholder="Akan terisi otomatis berdasarkan golongan ruang">
        <div id="pangkat_description" class="form-hint" style="display: none;">
            <i class="fas fa-info-circle"></i> <span id="pangkat_desc_text"></span>
        </div>
        @error('pangkat')
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                {{ $message }}
            </div>
        @enderror
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label required">Nomor SK CPNS</label>
        <input type="text" name="nomor_sk_cpns" class="form-input uppercase @error('nomor_sk_cpns') error @enderror"
            value="{{ $peserta['kepegawaian']['nomor_sk_cpns'] ?? old('nomor_sk_cpns') }}" required
            placeholder="Contoh: 820/KPTS/2023">
        <small class="form-hint">Huruf akan otomatis kapital</small>
        @error('nomor_sk_cpns')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label required">Tanggal SK CPNS</label>
        <input type="date" name="tanggal_sk_cpns" class="form-input @error('tanggal_sk_cpns') error @enderror"
            value="{{ $peserta['kepegawaian']['tanggal_sk_cpns'] ?? old('tanggal_sk_cpns') }}" required>
        @error('tanggal_sk_cpns')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-section-header">
    <i class="fas fa-graduation-cap"></i> Data Pendidikan
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label required">Pendidikan Terakhir (Sesuai SK CPNS)</label>
        <select name="pendidikan_terakhir" class="form-select @error('pendidikan_terakhir') error @enderror" required>
            <option value="">Pilih</option>
            <option value="SMU" {{ ($peserta['pendidikan_terakhir'] ?? old('pendidikan_terakhir')) == 'SMU' ? 'selected' : '' }}>SMU</option>
            <option value="D3" {{ ($peserta['pendidikan_terakhir'] ?? old('pendidikan_terakhir')) == 'D3' ? 'selected' : '' }}>D3</option>
            <option value="D4" {{ ($peserta['pendidikan_terakhir'] ?? old('pendidikan_terakhir')) == 'D4' ? 'selected' : '' }}>D4</option>
            <option value="S1" {{ ($peserta['pendidikan_terakhir'] ?? old('pendidikan_terakhir')) == 'S1' ? 'selected' : '' }}>S1</option>
            <option value="S2" {{ ($peserta['pendidikan_terakhir'] ?? old('pendidikan_terakhir')) == 'S2' ? 'selected' : '' }}>S2</option>
            <option value="S3" {{ ($peserta['pendidikan_terakhir'] ?? old('pendidikan_terakhir')) == 'S3' ? 'selected' : '' }}>S3</option>
        </select>
        @error('pendidikan_terakhir')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label required">Bidang Studi Pendidikan Terakhir</label>
        <input type="text" name="bidang_studi" class="form-input capitalize @error('bidang_studi') error @enderror"
            value="{{ $peserta['bidang_studi'] ?? old('bidang_studi') }}" required placeholder="Contoh: Ilmu Hukum">
        <small class="form-hint">Huruf akan otomatis kapital setiap kata</small>
        @error('bidang_studi')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label required">Bidang Keahlian/Kepakaran</label>
        <input type="text" name="bidang_keahlian"
            class="form-input capitalize @error('bidang_keahlian') error @enderror"
            value="{{ $peserta['bidang_keahlian'] ?? old('bidang_keahlian') }}" required
            placeholder="Contoh: Manajemen Pemerintahan">
        <small class="form-hint">Huruf akan otomatis kapital setiap kata</small>
        @error('bidang_keahlian')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-section-header">
    <i class="fas fa-user-graduate"></i> Data Mentor
</div>

<div class="form-group">
    <label class="form-label required">Apakah sudah ada penunjukan Mentor?</label>
    <select name="sudah_ada_mentor" id="sudah_ada_mentor" class="form-select @error('sudah_ada_mentor') error @enderror"
        required>
        <option value="">Pilih</option>
        <option value="Ya" {{ ($peserta['sudah_ada_mentor'] ?? old('sudah_ada_mentor')) == 'Ya' ? 'selected' : '' }}>Ya</option>
        <option value="Tidak" {{ ($peserta['sudah_ada_mentor'] ?? old('sudah_ada_mentor')) == 'Tidak' ? 'selected' : '' }}>Tidak</option>
    </select>
    @error('sudah_ada_mentor')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div id="mentor-container"
    style="display: {{ ($peserta['sudah_ada_mentor'] ?? old('sudah_ada_mentor')) == 'Ya' ? 'block' : 'none' }};">
    <div class="form-group">
        <label class="form-label required">Pilih Mentor atau Tambah Baru</label>
        <div class="mentor-options">
            <select name="mentor_mode" id="mentor_mode" class="form-select @error('mentor_mode') error @enderror">
                <option value="">Pilih Menu</option>
                <option value="pilih" {{ ($peserta['mentor_mode'] ?? old('mentor_mode')) == 'pilih' ? 'selected' : '' }}>
                    Daftar mentor
                </option>
                <option value="tambah" {{ ($peserta['mentor_mode'] ?? old('mentor_mode')) == 'tambah' ? 'selected' : '' }}>Tambah mentor(Jika tidak ada di daftar mentor)</option>
            </select>
        </div>
        @error('mentor_mode')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <!-- Form untuk memilih mentor yang sudah ada -->
    <div id="select-mentor-form"
        style="display: {{ ($peserta['mentor_mode'] ?? old('mentor_mode')) == 'pilih' ? 'block' : 'none' }};">
        
        <!-- FITUR PENCARIAN MENTOR -->
        <div class="form-group" style="margin-bottom: 20px;">
            <label class="form-label">
                <i class="fas fa-search"></i> Cari Mentor
            </label>
            <div style="position: relative;">
                <input type="text" 
                    id="search-mentor" 
                    class="form-input" 
                    placeholder="Ketik nama atau NIP mentor untuk mencari..."
                    style="padding-right: 40px;">
                <i class="fas fa-search" 
                style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #999;"></i>
            </div>
            <small class="form-hint">
                <i class="fas fa-info-circle"></i> 
                Pencarian berdasarkan Nama atau NIP Mentor (Ketik nip mentor tanpa spasi)
            </small>
            <div id="search-info" style="margin-top: 8px; font-size: 0.9em; color: #666; display: none;">
                <i class="fas fa-users"></i> <span id="search-result-count"></span>
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-label required">Pilih Mentor</label>
            <select name="id_mentor" id="id_mentor" class="form-select @error('id_mentor') error @enderror">
                <option value="">Pilih Mentor...</option>
                <!-- Data mentor akan dimuat via AJAX -->
            </select>
            <div id="mentor-loading" style="display: none; margin-top: 10px; color: #666;">
                <i class="fas fa-spinner fa-spin"></i> Memuat daftar mentor...
            </div>
            <div id="mentor-not-found" style="display: none; margin-top: 10px; color: #f56565;">
                <i class="fas fa-exclamation-circle"></i> Mentor tidak ditemukan
            </div>
            @error('id_mentor')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label required">Nama Mentor</label>
                <input type="text" name="nama_mentor" id="nama_mentor_select"
                    class="form-input capitalize @error('nama_mentor') error @enderror"
                    value="{{ $peserta['nama_mentor'] ?? old('nama_mentor') }}" readonly>
                @error('nama_mentor')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label required">NIP Mentor</label>
                <input type="text" name="nip_mentor" id="nip_mentor_select"
                    class="form-input @error('nip_mentor') error @enderror"
                    value="{{ $peserta['nip_mentor'] ?? old('nip_mentor') }}" readonly>
                @error('nip_mentor')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label required">Jabatan Mentor</label>
                <input type="text" name="jabatan_mentor" id="jabatan_mentor_select"
                    class="form-input capitalize @error('jabatan_mentor') error @enderror"
                    value="{{ $peserta['jabatan_mentor'] ?? old('jabatan_mentor') }}" readonly>
                @error('jabatan_mentor')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Golongan Mentor</label>
                <input type="text" name="golongan_mentor" id="golongan_mentor_select"
                    class="form-input @error('golongan_mentor') error @enderror"
                    value="{{ $peserta['golongan_mentor'] ?? old('golongan_mentor') }}" readonly
                    placeholder="Akan terisi otomatis saat memilih mentor">
                @error('golongan_mentor')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Pangkat Mentor</label>
                <input type="text" name="pangkat_mentor" id="pangkat_mentor_select"
                    class="form-input @error('pangkat_mentor') error @enderror"
                    value="{{ $peserta['pangkat_mentor'] ?? old('pangkat_mentor') }}" readonly
                    placeholder="Akan terisi otomatis saat memilih mentor">
                @error('pangkat_mentor')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Nama Bank & Nomor Rekening Mentor</label>
                <input type="text" name="nomor_rekening_mentor" id="nomor_rekening_mentor_select"
                    class="form-input @error('nomor_rekening_mentor') error @enderror"
                    placeholder="Contoh: Bank Mandiri, 174xxxxxxxxx a.n Nanang Wijaya"
                    value="{{ $peserta['nomor_rekening_mentor'] ?? old('nomor_rekening_mentor') }}" readonly>
                @error('nomor_rekening_mentor')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">NPWP Mentor</label>
                <input type="text" name="npwp_mentor" id="npwp_mentor_select"
                    class="form-input @error('npwp_mentor') error @enderror"
                    value="{{ $peserta['npwp_mentor'] ?? old('npwp_mentor') }}" readonly>
                @error('npwp_mentor')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Nomor Telepon Mentor</label>
                <input type="text" name="nomor_hp_mentor" id="nomor_hp_mentor_select"
                    class="form-input @error('nomor_hp_mentor') error @enderror"
                    placeholder="Akan terisi otomatis saat memilih mentor"
                    value="{{ $peserta['nomor_hp_mentor'] ?? old('nomor_hp_mentor') }}" readonly>
                @error('nomor_hp_mentor')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
    </div>

    <!-- Form untuk menambah mentor baru -->
    <div id="add-mentor-form"
        style="display: {{ ($peserta['mentor_mode'] ?? old('mentor_mode')) == 'tambah' ? 'block' : 'none' }};">
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            Silakan lengkapi data mentor baru
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label required">Nama Mentor</label>
                <input type="text" name="nama_mentor_baru" id="nama_mentor_baru"
                    class="form-input capitalize @error('nama_mentor_baru') error @enderror"
                    value="{{ $peserta['nama_mentor_baru'] ?? old('nama_mentor_baru') }}"
                    placeholder="Contoh: Dr. Ahmad Supriyadi, M.Si.">
                <small class="form-hint">Huruf akan otomatis kapital setiap kata</small>
                @error('nama_mentor_baru')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label required">NIP Mentor (Tanpa Spasi dan Titik)</label>
                <input type="text" 
                    name="nip_mentor_baru" 
                    id="nip_mentor_baru"
                    class="form-input nip-normalize @error('nip_mentor_baru') error @enderror"
                    value="{{ $peserta['nip_mentor_baru'] ?? old('nip_mentor_baru') }}"
                    placeholder="Contoh: 196512311989031001">
                <small class="form-hint">
                    <i class="fas fa-info-circle"></i> 
                    Masukkan NIP tanpa spasi dan titik. Sistem akan otomatis menghapus spasi/titik yang Anda ketik.
                </small>
                @error('nip_mentor_baru')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label required">Jabatan Mentor</label>
                <input type="text" name="jabatan_mentor_baru" id="jabatan_mentor_baru"
                    class="form-input capitalize @error('jabatan_mentor_baru') error @enderror"
                    value="{{ $peserta['jabatan_mentor_baru'] ?? old('jabatan_mentor_baru') }}"
                    placeholder="Contoh: Kepala Bagian Perencanaan">
                <small class="form-hint">Huruf akan otomatis kapital setiap kata</small>
                @error('jabatan_mentor_baru')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label required">Golongan Ruang Mentor</label>
                <select name="golongan_mentor_baru" id="golongan_mentor_baru"
                    class="form-select @error('golongan_mentor_baru') error @enderror">
                    <option value="">-- Pilih Golongan Ruang --</option>
                    @foreach(['II/a','II/b','II/c','II/d','III/a','III/b','III/c','III/d','IV/a','IV/b','IV/c','IV/d'] as $gol)
                        <option value="{{ $gol }}"
                            {{ ($peserta['golongan_mentor_baru'] ?? old('golongan_mentor_baru')) == $gol ? 'selected' : '' }}>
                            {{ $gol }}
                        </option>
                    @endforeach
                </select>
                @error('golongan_mentor_baru')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label class=" required">Pangkat Mentor</label>
                <input type="text" name="pangkat_mentor_baru" id="pangkat_mentor_baru"
                    class="form-input @error('pangkat_mentor_baru') error @enderror"
                    value="{{ $peserta['pangkat_mentor_baru'] ?? old('pangkat_mentor_baru') }}" readonly
                    placeholder="Terisi otomatis berdasarkan golongan">
                <small class="form-hint">
                    <i class="fas fa-info-circle"></i> Terisi otomatis saat golongan dipilih
                </small>
                @error('pangkat_mentor_baru')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label required">Nomor Rekening Mentor</label>
                <input type="text" name="nomor_rekening_mentor_baru" id="nomor_rekening_mentor_baru"
                    class="form-input @error('nomor_rekening_mentor_baru') error @enderror"
                    placeholder="Contoh: Bank Mandiri, 174xxxxxxxxx a.n Nanang Wijaya"
                    value="{{ $peserta['nomor_rekening_mentor_baru'] ?? old('nomor_rekening_mentor_baru') }}">
                @error('nomor_rekening_mentor_baru')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label required">NPWP Mentor</label>
                <input type="text" name="npwp_mentor_baru" id="npwp_mentor_baru"
                    class="form-input @error('npwp_mentor_baru') error @enderror"
                    value="{{ $peserta['npwp_mentor_baru'] ?? old('npwp_mentor_baru') }}"
                    placeholder="Contoh: 123456789012345">
                @error('npwp_mentor_baru')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Nomor Telepon Mentor</label>
                <input type="tel" name="nomor_hp_mentor_baru" id="nomor_hp_mentor_baru"
                    class="form-input @error('nomor_hp_mentor_baru') error @enderror"
                    placeholder="Contoh: 081234567890"
                    value="{{ $peserta['nomor_hp_mentor_baru'] ?? old('nomor_hp_mentor_baru') }}">
                <small class="form-hint">Format: +62812-3456-7890 atau 081234567890</small>
                @error('nomor_hp_mentor_baru')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
    </div>
</div>

<input type="hidden" class="form-input" value="{{ $pendaftaran['angkatan']['nama_angkatan'] ?? 'Tidak tersedia' }}"
    readonly>

<input type="hidden" class="form-input" value="{{ $pendaftaran['angkatan']['tahun'] ?? 'Tidak tersedia' }}" readonly>

<div class="form-section-header">
    <i class="fas fa-file-upload"></i> Dokumen Pendukung
</div><div class="alert alert-info">
    <i class="fas fa-info-circle"></i>
    <strong>Catatan:</strong> File yang sudah diupload sebelumnya akan tetap tersimpan. Upload ulang hanya jika ingin mengganti file.
</div>

<div class="form-group">
    <label class="form-label required">Unggah Scan atau Foto KTP yang berlaku</label>
    <div class="form-file">
        <input type="file" name="file_ktp" class="form-file-input @error('file_ktp') error @enderror" accept=".pdf,.jpg,.jpeg,.png">
        <label class="form-file-label">
            <i class="fas fa-cloud-upload-alt"></i><br>
            Klik untuk mengunggah file PDF/JPG/PNG (maks. 1MB)
        </label>
        <div class="form-file-name">
            @if(isset($peserta['file_ktp']) && $peserta['file_ktp'])
                <div class="file-info">
                    <i class="fas fa-check-circle text-success"></i>
                    <span>File sudah diupload: {{ basename($peserta['file_ktp']) }}</span>
                    <button type="button" class="btn-change-file" data-target="file_ktp">
                        <i class="fas fa-exchange-alt"></i> Ganti File
                    </button>
                </div>
            @else
                <span class="no-file">Belum ada file dipilih</span>
            @endif
        </div>
    </div>
    @error('file_ktp')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>
<!-- Unggah scan SK CPNS -->
<div class="form-group">
    <label class="form-label required">Unggah scan SK CPNS</label>
    <div class="form-file">
        <input type="file" name="file_sk_cpns" class="form-file-input @error('file_sk_cpns') error @enderror" accept=".pdf">
        <label class="form-file-label">
            <i class="fas fa-cloud-upload-alt"></i><br>
            Klik untuk mengunggah file PDF (maks. 1MB)
        </label>
        <div class="form-file-name">
            @if(isset($peserta['kepegawaian']['file_sk_cpns']) && $peserta['kepegawaian']['file_sk_cpns'])
                <div class="file-info">
                    <i class="fas fa-check-circle text-success"></i>
                    <span>File sudah diupload: {{ basename($peserta['kepegawaian']['file_sk_cpns']) }}</span>
                    <button type="button" class="btn-change-file" data-target="file_sk_cpns">
                        <i class="fas fa-exchange-alt"></i> Ganti File
                    </button>
                </div>
            @else
                <span class="no-file">Belum ada file dipilih</span>
            @endif
        </div>
    </div>
    @error('file_sk_cpns')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<!-- Unggah scan SPMT -->
<div class="form-group">
    <label class="form-label required">Unggah scan Surat Pernyataan Melaksanaan Tugas (SPMT)</label>
    <div class="form-file">
        <input type="file" name="file_spmt" class="form-file-input @error('file_spmt') error @enderror" accept=".pdf">
        <label class="form-file-label">
            <i class="fas fa-cloud-upload-alt"></i><br>
            Klik untuk mengunggah file PDF (maks. 1MB)
        </label>
        <div class="form-file-name">
            @if(isset($peserta['kepegawaian']['file_spmt']) && $peserta['kepegawaian']['file_spmt'])
                <div class="file-info">
                    <i class="fas fa-check-circle text-success"></i>
                    <span>File sudah diupload: {{ basename($peserta['kepegawaian']['file_spmt']) }}</span>
                    <button type="button" class="btn-change-file" data-target="file_spmt">
                        <i class="fas fa-exchange-alt"></i> Ganti File
                    </button>
                </div>
            @else
                <span class="no-file">Belum ada file dipilih</span>
            @endif
        </div>
    </div>
    @error('file_spmt')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<!-- Unggah scan Surat Penyataan Kesediaan -->
<div class="form-group">
    <label class="form-label required">Unggah scan Surat Penyataan Kesediaan</label>
    <div class="form-file">
        <input type="file" name="file_surat_kesediaan" class="form-file-input @error('file_surat_kesediaan') error @enderror" accept=".pdf">
        <label class="form-file-label">
            <i class="fas fa-cloud-upload-alt"></i><br>
            Klik untuk mengunggah file PDF (maks. 1MB)
        </label>
        <div class="form-file-name">
            @if(isset($pendaftaran['file_surat_kesediaan']) && $pendaftaran['file_surat_kesediaan'])
                <div class="file-info">
                    <i class="fas fa-check-circle text-success"></i>
                    <span>File sudah diupload: {{ basename($pendaftaran['file_surat_kesediaan']) }}</span>
                    <button type="button" class="btn-change-file" data-target="file_surat_kesediaan">
                        <i class="fas fa-exchange-alt"></i> Ganti File
                    </button>
                </div>
            @else
                <span class="no-file">Belum ada file dipilih</span>
            @endif
        </div>
    </div>
    @error('file_surat_kesediaan')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<!-- Unggah Scan Surat Tugas -->
<div class="form-group">
    <label class="form-label">Unggah Scan Surat Tugas mengikuti pelatihan yang ditandatangani oleh pejabat yang berwenang (jika belum maka WAJIB disertakan saat masa klasikal)</label>
    <div class="form-file">
        <input type="file" name="file_surat_tugas" class="form-file-input @error('file_surat_tugas') error @enderror" accept=".pdf">
        <label class="form-file-label">
            <i class="fas fa-cloud-upload-alt"></i><br>
            Klik untuk mengunggah file PDF (maks. 1MB)
        </label>
        <div class="form-file-name">
            @if(isset($pendaftaran['file_surat_tugas']) && $pendaftaran['file_surat_tugas'])
                <div class="file-info">
                    <i class="fas fa-check-circle text-success"></i>
                    <span>File sudah diupload: {{ basename($pendaftaran['file_surat_tugas']) }}</span>
                    <button type="button" class="btn-change-file" data-target="file_surat_tugas">
                        <i class="fas fa-exchange-alt"></i> Ganti File
                    </button>
                </div>
            @else
                <span class="no-file">Belum ada file dipilih</span>
            @endif
        </div>
    </div>
    @error('file_surat_tugas')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<!-- Sasaran Kinerja Pegawai (SKP) -->
<div class="form-group">
    <label class="form-label">Sasaran Kinerja Pegawai (SKP)</label>
    <div class="form-file">
        <input type="file" name="file_skp" class="form-file-input @error('file_skp') error @enderror" accept=".pdf">
        <label class="form-file-label">
            <i class="fas fa-cloud-upload-alt"></i><br>
            Klik untuk mengunggah file PDF (maks. 1MB)
        </label>
        <div class="form-file-name">
            @if(isset($peserta['kepegawaian']['file_skp']) && $peserta['kepegawaian']['file_skp'])
                <div class="file-info">
                    <i class="fas fa-check-circle text-success"></i>
                    <span>File sudah diupload: {{ basename($peserta['kepegawaian']['file_skp']) }}</span>
                    <button type="button" class="btn-change-file" data-target="file_skp">
                        <i class="fas fa-exchange-alt"></i> Ganti File
                    </button>
                </div>
            @else
                <span class="no-file">Belum ada file dipilih</span>
            @endif
        </div>
    </div>
    @error('file_skp')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<!-- Pas Foto peserta dengan cropping (HANYA CROP) -->
<div class="form-group">
    <label class="form-label required">Unggah Pas Foto peserta (Untuk Sertifikat)</label>

    @if(isset($peserta['file_pas_foto']) && $peserta['file_pas_foto'])
        <!-- Tampilkan foto yang sudah ada -->
        <div id="existing-photo-container" style="margin: 15px 0;">
            <p><strong>Foto yang sudah diupload:</strong></p>
            <div style="width: 150px; height: 200px; border: 1px solid #ddd; overflow: hidden; margin-bottom: 10px;">
                <img src="{{ Storage::disk('google')->url($peserta['file_pas_foto']) }}"
                    style="width: 100%; height: 100%; object-fit: cover;"
                    onerror="this.src='https://via.placeholder.com/150x200?text=Foto+Tidak+Ditemukan'">
            </div>
            <button type="button" id="btn-change-photo-existing" class="btn btn-secondary btn-sm">
                <i class="fas fa-exchange-alt"></i> Ganti Foto
            </button>
        </div>

        <!-- Container untuk cropping (tersembunyi awalnya) -->
        <div id="crop-container" style="display: none;">
            <div class="crop-wrapper" style="max-width: 600px; margin: 15px auto;">
                <img id="crop-image" style="max-width: 100%;">
            </div>

            <div class="crop-controls" style="margin-top: 15px; text-align: center;">
                <button type="button" id="crop-zoom-in" class="btn btn-sm btn-secondary">
                    <i class="fas fa-search-plus"></i>
                </button>
                <button type="button" id="crop-zoom-out" class="btn btn-sm btn-secondary">
                    <i class="fas fa-search-minus"></i>
                </button>
                <button type="button" id="crop-rotate-left" class="btn btn-sm btn-secondary">
                    <i class="fas fa-undo"></i>
                </button>
                <button type="button" id="crop-rotate-right" class="btn btn-sm btn-secondary">
                    <i class="fas fa-redo"></i>
                </button>
                <button type="button" id="crop-reset" class="btn btn-sm btn-secondary">
                    <i class="fas fa-sync"></i> Reset
                </button>
                <button type="button" id="crop-confirm" class="btn btn-sm btn-success">
                    <i class="fas fa-check"></i> Potong Foto
                </button>
                <button type="button" id="crop-cancel" class="btn btn-sm btn-danger">
                    <i class="fas fa-times"></i> Batal
                </button>
            </div>
        </div>

        <!-- Preview hasil crop -->
        <div id="crop-preview-container" style="margin: 15px 0; display: none;">
            <p><strong>Preview Foto :</strong></p>
            <div id="crop-preview" style="width: 150px; height: 200px; border: 1px solid #ddd; overflow: hidden;">
                <img id="cropped-preview" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <button type="button" id="change-photo" class="btn btn-sm btn-secondary" style="margin-top: 10px;">
                <i class="fas fa-exchange-alt"></i> Ganti Foto
            </button>
        </div>

        <!-- Input hidden untuk data crop -->
        <input type="hidden" name="file_pas_foto_cropped" id="file_pas_foto_cropped">
        <input type="hidden" name="crop_data" id="crop_data">

        <!-- UI upload awal (tersembunyi) -->
        <div class="form-file" id="upload-container" style="display: none;">
            <input type="file" name="file_pas_foto" id="file_pas_foto"
                class="form-file-input @error('file_pas_foto') error @enderror" accept=".jpg,.jpeg,.png">
            <label class="form-file-label" for="file_pas_foto">
                <i class="fas fa-cloud-upload-alt"></i><br>
                Klik untuk mengunggah file JPG/PNG (maks. 1MB)<br>
                <small style="font-size: 0.85em; color: #666;">Foto akan dipotong </small>
            </label>
            <div class="form-file-name" id="file-name-display">
                <span class="no-file">Belum ada file dipilih</span>
            </div>
        </div>

    @else
        <!-- Tampilkan upload container jika belum ada foto -->
        
        <!-- Layout baris untuk upload dan contoh foto -->
        <div style="display: flex; gap: 20px; align-items: flex-start; flex-wrap: wrap; margin-bottom: 15px;">
            <!-- UI upload awal -->
            <div class="form-file" id="upload-container" style="flex: 1; min-width: 300px;">
                <input type="file" name="file_pas_foto" id="file_pas_foto"
                    class="form-file-input @error('file_pas_foto') error @enderror" accept=".jpg,.jpeg,.png">
                <label class="form-file-label" for="file_pas_foto">
                    <i class="fas fa-cloud-upload-alt"></i><br>
                    Klik untuk mengunggah file JPG/PNG (maks. 1MB)<br>
                    <small style="font-size: 0.85em; color: #666;">Foto akan dipotong </small>
                </label>
                <div class="form-file-name" id="file-name-display">
                    <span class="no-file">Belum ada file dipilih</span>
                </div>
            </div>

            <!-- Contoh foto -->
            <div style="text-align: center;">
                <p style="margin: 0 0 8px 0; font-size: 0.9em; color: #666;"><strong>Contoh Foto :</strong></p>
                <div style="width: 90px; height: 120px; border: 2px solid #ddd; overflow: hidden; border-radius: 4px;">
                    <img src="{{ asset('gambar/contohfoto2.jpeg') }}" 
                         alt="Contoh Foto"
                         style="width: 100%; height: 100%; object-fit: cover;"
                         onerror="this.src='https://via.placeholder.com/90x120?text=Contoh+Foto'">
                </div>
            </div>
        </div>

        <!-- Container untuk cropping -->
        <div id="crop-container" style="display: none;">
            <div class="crop-wrapper" style="max-width: 600px; margin: 15px auto;">
                <img id="crop-image" style="max-width: 100%;">
            </div>

            <div class="crop-controls" style="margin-top: 15px; text-align: center;">
                <button type="button" id="crop-zoom-in" class="btn btn-sm btn-secondary">
                    <i class="fas fa-search-plus"></i>
                </button>
                <button type="button" id="crop-zoom-out" class="btn btn-sm btn-secondary">
                    <i class="fas fa-search-minus"></i>
                </button>
                <button type="button" id="crop-rotate-left" class="btn btn-sm btn-secondary">
                    <i class="fas fa-undo"></i>
                </button>
                <button type="button" id="crop-rotate-right" class="btn btn-sm btn-secondary">
                    <i class="fas fa-redo"></i>
                </button>
                <button type="button" id="crop-reset" class="btn btn-sm btn-secondary">
                    <i class="fas fa-sync"></i> Reset
                </button>
                <button type="button" id="crop-confirm" class="btn btn-sm btn-success">
                    <i class="fas fa-check"></i> Potong Foto
                </button>
                <button type="button" id="crop-cancel" class="btn btn-sm btn-danger">
                    <i class="fas fa-times"></i> Batal
                </button>
            </div>
        </div>

        <!-- Preview hasil crop -->
        <div id="crop-preview-container" style="margin: 15px 0; display: none;">
            <p><strong>Preview Foto :</strong></p>
            <div id="crop-preview" style="width: 150px; height: 200px; border: 1px solid #ddd; overflow: hidden;">
                <img id="cropped-preview" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <button type="button" id="change-photo" class="btn btn-sm btn-secondary" style="margin-top: 10px;">
                <i class="fas fa-exchange-alt"></i> Ganti Foto
            </button>
        </div>

        <!-- Input hidden untuk data crop (HANYA INI YANG DIBUTUHKAN) -->
        <input type="hidden" name="file_pas_foto_cropped" id="file_pas_foto_cropped">
        <input type="hidden" name="crop_data" id="crop_data">

    @endif

    @error('file_pas_foto')
        <small class="text-danger">{{ $message }}</small>
    @enderror
    @error('file_pas_foto_cropped')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<!-- Surat Keterangan Berbadan Sehat -->
<div class="form-group">
    <label class="form-label">Unggah Surat Keterangan Berbadan Sehat</label>
    <div class="form-file">
        <input type="file" name="file_surat_sehat" class="form-file-input @error('file_surat_sehat') error @enderror" accept=".pdf">
        <label class="form-file-label">
            <i class="fas fa-cloud-upload-alt"></i><br>
            Klik untuk mengunggah file PDF (maks. 1MB)
        </label>
        <div class="form-file-name">
            @if(isset($pendaftaran['file_surat_sehat']) && $pendaftaran['file_surat_sehat'])
                <div class="file-info">
                    <i class="fas fa-check-circle text-success"></i>
                    <span>File sudah diupload: {{ basename($pendaftaran['file_surat_sehat']) }}</span>
                    <button type="button" class="btn-change-file" data-target="file_surat_sehat">
                        <i class="fas fa-exchange-alt"></i> Ganti File
                    </button>
                </div>
            @else
                <span class="no-file">Belum ada file dipilih</span>
            @endif
        </div>
    </div>
    @error('file_surat_sehat')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

{{-- ===== SCRIPT INSTANSI SEARCHABLE SELECT — PARTIAL ===== --}}
<script>
(function () {
    // Data instansi dari config Laravel — di-embed oleh Blade
    const DAFTAR_INSTANSI_PARTIAL = @json(config('instansi'));

    const trigger      = document.getElementById('instansi_trigger_partial');
    const triggerLabel = document.getElementById('instansi_trigger_label_partial');
    const hiddenInput  = document.getElementById('asal_instansi_hidden_partial');
    const dropdown     = document.getElementById('instansi_dropdown_partial');
    const searchInput  = document.getElementById('asal_instansi_search_partial');
    const listEl       = document.getElementById('instansi_list_partial');
    const countEl      = document.getElementById('instansi_count_partial');
    const clearBtn     = document.getElementById('instansi_clear_btn_partial');
    const chevron      = document.getElementById('instansi_chevron_partial');

    if (!trigger || !hiddenInput) return;

    let selectedValue = hiddenInput.value || '';
    let isOpen = false;
    let debounceTimer = null;

    if (selectedValue) setTriggerSelected(selectedValue);

    trigger.addEventListener('click', function (e) {
        if (e.target.closest('#instansi_clear_btn_partial')) return;
        isOpen ? closeDropdown() : openDropdown();
    });

    function openDropdown() {
        isOpen = true;
        dropdown.style.display = 'block';
        trigger.style.borderRadius = '10px 10px 0 0';
        trigger.style.borderColor  = 'var(--primary-color, #1a3a6c)';
        trigger.style.boxShadow    = '0 0 0 4px rgba(26,58,108,0.1)';
        if (chevron) chevron.style.transform = 'rotate(180deg)';
        renderList(DAFTAR_INSTANSI_PARTIAL, '');
        setTimeout(() => { if (searchInput) searchInput.focus(); }, 50);
    }

    function closeDropdown() {
        isOpen = false;
        dropdown.style.display = 'none';
        trigger.style.borderRadius = '10px';
        trigger.style.boxShadow    = selectedValue ? '0 0 0 4px rgba(26,58,108,0.1)' : '';
        trigger.style.borderColor  = selectedValue ? 'var(--primary-color, #1a3a6c)' : '';
        if (chevron) chevron.style.transform = 'rotate(0deg)';
        if (searchInput) searchInput.value = '';
        if (countEl) countEl.textContent = 'Menampilkan ' + DAFTAR_INSTANSI_PARTIAL.length + ' instansi';
    }

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                const q = this.value.trim();
                const filtered = q
                    ? DAFTAR_INSTANSI_PARTIAL.filter(i => i.toLowerCase().includes(q.toLowerCase()))
                    : DAFTAR_INSTANSI_PARTIAL;
                renderList(filtered, q);
            }, 150);
        });

        searchInput.addEventListener('keydown', function (e) {
            const items  = listEl.querySelectorAll('.instansi-item-partial');
            const active = listEl.querySelector('.instansi-item-partial.iactive');
            let idx = active ? [...items].indexOf(active) : -1;
            if (e.key === 'ArrowDown') { e.preventDefault(); idx = Math.min(idx + 1, items.length - 1); setActive(items, idx); }
            else if (e.key === 'ArrowUp')   { e.preventDefault(); idx = Math.max(idx - 1, 0); setActive(items, idx); }
            else if (e.key === 'Enter')      { e.preventDefault(); if (active) selectInstansi(active.dataset.value); }
            else if (e.key === 'Escape')     { closeDropdown(); }
        });

        searchInput.addEventListener('click', e => e.stopPropagation());
    }

    function setActive(items, idx) {
        items.forEach(el => { el.classList.remove('iactive'); el.style.background = ''; });
        if (items[idx]) {
            items[idx].classList.add('iactive');
            items[idx].style.background = '#dbeafe';
            items[idx].scrollIntoView({ block: 'nearest' });
        }
    }

    function renderList(data, q) {
        if (!listEl) return;
        if (data.length === 0) {
            listEl.innerHTML = `<div style="padding:20px;text-align:center;color:var(--gray-500,#6b7280);font-size:0.9rem;">
                <i class="fas fa-search" style="font-size:1.5rem;margin-bottom:8px;display:block;opacity:0.4;"></i>
                Tidak ditemukan untuk "<strong>${escH(q)}</strong>"
            </div>`;
            if (countEl) countEl.textContent = '0 instansi ditemukan';
            return;
        }
        const regex = q ? new RegExp('(' + escR(q) + ')', 'gi') : null;
        listEl.innerHTML = data.map(item => {
            const hl   = regex ? item.replace(regex, '<mark style="background:#fef3c7;padding:0;border-radius:2px;">$1</mark>') : escH(item);
            const isSel = item === selectedValue;
            return `<div class="instansi-item-partial${isSel ? ' iactive' : ''}" data-value="${escH(item)}"
                style="padding:10px 16px;cursor:pointer;font-size:0.875rem;
                       border-bottom:1px solid var(--gray-100,#f3f4f6);
                       display:flex;align-items:center;gap:10px;transition:background 0.1s;
                       background:${isSel ? '#eff6ff' : ''};"
                onmouseover="this.style.background='#eff6ff';"
                onmouseout="this.style.background='${isSel ? '#eff6ff' : ''}';">
                <i class="fas fa-${isSel ? 'check-circle' : 'building'}"
                   style="color:${isSel ? 'var(--success-color,#10b981)' : 'var(--primary-color,#1a3a6c)'};font-size:0.8rem;flex-shrink:0;"></i>
                <span>${hl}</span>
            </div>`;
        }).join('');
        if (countEl) countEl.textContent = q
            ? (data.length + ' dari ' + DAFTAR_INSTANSI_PARTIAL.length + ' instansi')
            : ('Menampilkan ' + data.length + ' instansi');
        const selEl = listEl.querySelector('.iactive');
        if (selEl) setTimeout(() => selEl.scrollIntoView({ block: 'nearest' }), 10);
        listEl.querySelectorAll('.instansi-item-partial').forEach(el => {
            el.addEventListener('click', () => selectInstansi(el.dataset.value));
        });
    }

    function selectInstansi(value) {
        selectedValue = value;
        hiddenInput.value = value;
        setTriggerSelected(value);
        closeDropdown();
        trigger.classList.remove('error');
        const errMsg = trigger.closest('.form-group')?.querySelector('.error-message');
        if (errMsg) errMsg.remove();
    }

    function setTriggerSelected(value) {
        if (triggerLabel) { triggerLabel.textContent = value; triggerLabel.style.color = 'var(--gray-800,#1f2937)'; }
        if (clearBtn)  clearBtn.style.display  = 'flex';
        if (trigger)   trigger.style.borderColor = 'var(--primary-color,#1a3a6c)';
    }

    if (clearBtn) {
        clearBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            selectedValue = '';
            hiddenInput.value = '';
            triggerLabel.textContent = 'Pilih asal instansi...';
            triggerLabel.style.color = 'var(--gray-400,#9ca3af)';
            clearBtn.style.display   = 'none';
            trigger.style.borderColor = '';
            trigger.style.boxShadow   = '';
            closeDropdown();
        });
    }

    document.addEventListener('click', function (e) {
        if (isOpen && !trigger.contains(e.target) && !dropdown.contains(e.target)) closeDropdown();
    });

    function escH(s) { return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }
    function escR(s) { return s.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); }
})();
</script>