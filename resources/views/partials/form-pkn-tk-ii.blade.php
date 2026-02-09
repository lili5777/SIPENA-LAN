<div class="form-section-header">
    <i class="fas fa-user-tie"></i> Data Pribadi
</div>

<!-- Hidden fields untuk ID peserta dan pendaftaran -->
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
        <label class="form-label required">Nama Lengkap (Berikut Gelar Pendidikan)</label>
        <input type="text" name="nama_lengkap" class="form-input @error('nama_lengkap') error @enderror"
            value="{{ $peserta['nama_lengkap'] ?? old('nama_lengkap') }}" required
            placeholder="Contoh: Muhammad Ali, S.H., M.H.">
        <small class="form-hint">Huruf akan otomatis kapital setiap kata</small>
        @error('nama_lengkap')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label required">NIP/NRP</label>
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
        <small class="form-hint">Huruf akan otomatis kapital setiap kata</small>
        @error('tempat_lahir')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label required">Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" class="form-input @error('tanggal_lahir') error @enderror"
            value="{{ $peserta['tanggal_lahir'] ?? old('tanggal_lahir') }}" required>
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
        <small class="form-hint">Huruf akan otomatis kapital setiap kata</small>
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
            <option value="Kristen" {{ ($peserta['agama'] ?? old('agama')) == 'Kristen' ? 'selected' : '' }}>Kristen
            </option>
            {{-- <option value="Kristen Protestan" {{ ($peserta['agama'] ?? old('agama')) == 'Kristen Protestan' ? 'selected' : '' }}>
                Kristen Protestan
            </option> --}}
            <option value="Katolik" {{ ($peserta['agama'] ?? old('agama')) == 'Katolik' ? 'selected' : '' }}>Katolik
            </option>
            <option value="Hindu" {{ ($peserta['agama'] ?? old('agama')) == 'Hindu' ? 'selected' : '' }}>Hindu</option>
            <option value="Buddha" {{ ($peserta['agama'] ?? old('agama')) == 'Buddha' ? 'selected' : '' }}>Buddha</option>
            <option value="Konghucu" {{ ($peserta['agama'] ?? old('agama')) == 'Konghucu' ? 'selected' : '' }}>Konghucu
            </option>
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
        <small class="form-hint">Hanya bisa diisi jika status "Menikah". Huruf akan otomatis kapital setiap kata</small>
        @error('nama_pasangan')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label required">Pendidikan Terakhir (Sesuai SK)</label>
        <select name="pendidikan_terakhir" class="form-select @error('pendidikan_terakhir') error @enderror" required>
            <option value="">Pilih</option>
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
        <label class="form-label required">Bidang Keahlian (Keahlian atau Kompetensi yang menonjol pada diri
            peserta)</label>
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

<div class="form-row">
    <div class="form-group">
        <label class="form-label">Ukuran Baju Taktikal</label>
        <select name="ukuran_kaos" class="form-select @error('ukuran_kaos') error @enderror">
            <option value="">Pilih</option>
            <option value="S" {{ ($peserta['ukuran_kaos'] ?? old('ukuran_kaos')) == 'S' ? 'selected' : '' }}>S</option>
            <option value="M" {{ ($peserta['ukuran_kaos'] ?? old('ukuran_kaos')) == 'M' ? 'selected' : '' }}>M</option>
            <option value="L" {{ ($peserta['ukuran_kaos'] ?? old('ukuran_kaos')) == 'L' ? 'selected' : '' }}>L</option>
            <option value="XL" {{ ($peserta['ukuran_kaos'] ?? old('ukuran_kaos')) == 'XL' ? 'selected' : '' }}>XL</option>
            <option value="XXL" {{ ($peserta['ukuran_kaos'] ?? old('ukuran_kaos')) == 'XXL' ? 'selected' : '' }}>XXL
            </option>
            <option value="XXXL" {{ ($peserta['ukuran_kaos'] ?? old('ukuran_kaos')) == 'XXXL' ? 'selected' : '' }}>XXXL
            </option>
        </select>
        @error('ukuran_kaos')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label">Ukuran Kaos Olahraga</label>
        <select name="ukuran_training" class="form-select @error('ukuran_training') error @enderror">
            <option value="">Pilih</option>
            <option value="S" {{ ($peserta['ukuran_training'] ?? old('ukuran_training')) == 'S' ? 'selected' : '' }}>S
            </option>
            <option value="M" {{ ($peserta['ukuran_training'] ?? old('ukuran_training')) == 'M' ? 'selected' : '' }}>M
            </option>
            <option value="L" {{ ($peserta['ukuran_training'] ?? old('ukuran_training')) == 'L' ? 'selected' : '' }}>L
            </option>
            <option value="XL" {{ ($peserta['ukuran_training'] ?? old('ukuran_training')) == 'XL' ? 'selected' : '' }}>XL
            </option>
            <option value="XXL" {{ ($peserta['ukuran_training'] ?? old('ukuran_training')) == 'XXL' ? 'selected' : '' }}>
                XXL</option>
            <option value="XXXL" {{ ($peserta['ukuran_training'] ?? old('ukuran_training')) == 'XXXL' ? 'selected' : '' }}>XXXL</option>
        </select>
        @error('ukuran_training')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label">Ukuran Celana</label>
        <select name="ukuran_celana" class="form-select @error('ukuran_celana') error @enderror">
            <option value="">Pilih</option>
            <option value="S" {{ ($peserta['ukuran_celana'] ?? old('ukuran_celana')) == 'S' ? 'selected' : '' }}>S
            </option>
            <option value="M" {{ ($peserta['ukuran_celana'] ?? old('ukuran_celana')) == 'M' ? 'selected' : '' }}>M
            </option>
            <option value="L" {{ ($peserta['ukuran_celana'] ?? old('ukuran_celana')) == 'L' ? 'selected' : '' }}>L
            </option>
            <option value="XL" {{ ($peserta['ukuran_celana'] ?? old('ukuran_celana')) == 'XL' ? 'selected' : '' }}>XL
            </option>
            <option value="XXL" {{ ($peserta['ukuran_celana'] ?? old('ukuran_celana')) == 'XXL' ? 'selected' : '' }}>XXL
            </option>
            <option value="XXXL" {{ ($peserta['ukuran_celana'] ?? old('ukuran_celana')) == 'XXXL' ? 'selected' : '' }}>
                XXXL</option>
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
    <label class="form-label ">Kondisi Peserta</label>
    <textarea name="kondisi_peserta" class="form-textarea capitalize @error('kondisi_peserta') error @enderror" required
        placeholder="Contoh: Sehat, Tidak Memiliki Riwayat Penyakit Berat">{{ $peserta['kondisi_peserta'] ?? old('kondisi_peserta') }}</textarea>
    <small class="form-hint">Huruf akan otomatis kapital setiap kata</small>
    @error('kondisi_peserta')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="form-section-header">
    <i class="fas fa-building"></i> Data Kepegawaian
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label required">Asal Instansi</label>
        <input type="text" name="asal_instansi" class="form-input capitalize @error('asal_instansi') error @enderror"
            placeholder="Contoh: Kementerian Kesehatan Republik Indonesia"
            value="{{ $peserta['kepegawaian']['asal_instansi'] ?? old('asal_instansi') }}" required>
        <small class="form-hint">Huruf akan otomatis kapital setiap kata</small>
        @error('asal_instansi')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label required">Unit Kerja Peserta</label>
        <input type="text" name="unit_kerja" class="form-input capitalize @error('unit_kerja') error @enderror"
            placeholder="Contoh: Direktorat Jenderal Pelayanan Kesehatan"
            value="{{ $peserta['kepegawaian']['unit_kerja'] ?? old('unit_kerja') }}" required>
        <small class="form-hint">Huruf akan otomatis kapital setiap kata</small>
        @error('unit_kerja')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-row">
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

    <div class="form-group">
        <label class="form-label required">Eselon</label>
        <select name="eselon" class="form-select @error('eselon') error @enderror" required>
            <option value="">Pilih</option>
            <option value="II" {{ ($peserta['kepegawaian']['eselon'] ?? old('eselon')) == 'II' ? 'selected' : '' }}>II
            </option>
            <option value="III/Pejabat Fungsional" {{ ($peserta['kepegawaian']['eselon'] ?? old('eselon')) == 'III/Pejabat Fungsional' ? 'selected' : '' }}>III/Pejabat Fungsional</option>
        </select>
        @error('eselon')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
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
        <label class="form-label required">Provinsi (Kantor/Tempat Tugas)</label>
        <select name="id_provinsi" class="form-select @error('id_provinsi') error @enderror" required>
            <option value="">Pilih Provinsi</option>
            <option value="">Memuat provinsi...</option>
        </select>
        @error('id_provinsi')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label required">Kabupaten (Lokasi Kantor/Tempat Tugas)</label>
        <select name="id_kabupaten_kota" class="form-select @error('id_kabupaten_kota') error @enderror" required
            disabled>
            <option value="">Pilih Kabupaten (Pilih Provinsi Dahulu)</option>
        </select>
        @error('id_kabupaten_kota')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-group">
    <label class="form-label required">Alamat Kantor</label>
    <textarea name="alamat_kantor" class="form-textarea capitalize @error('alamat_kantor') error @enderror" required
        placeholder="Contoh: Jalan HR Rasuna Said Kaveling 5, Kuningan, Jakarta Selatan">{{ $peserta['kepegawaian']['alamat_kantor'] ?? old('alamat_kantor') }}</textarea>
    <small class="form-hint">Huruf akan otomatis kapital setiap kata</small>
    @error('alamat_kantor')
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

<div class="form-section-header">
    <i class="fas fa-file-upload"></i> Dokumen Pendukung
</div>
<div class="form-row">
    <div class="form-group">
        <label class="form-label required">Unggah Bukti SK Jabatan Terakhir (Definitif)</label>
        <div class="form-file">
            <input type="file" name="file_sk_jabatan" class="form-file-input @error('file_sk_jabatan') error @enderror" 
                   accept=".pdf">
            <label class="form-file-label">
                <i class="fas fa-cloud-upload-alt"></i><br>
                Klik untuk mengunggah file PDF (maks. 1MB)
            </label>
            <div class="form-file-name">
                @if(isset($peserta['kepegawaian']['file_sk_jabatan']) && $peserta['kepegawaian']['file_sk_jabatan'])
                    <div class="file-info">
                        <i class="fas fa-check-circle text-success"></i>
                        <span>File sudah diupload: {{ basename($peserta['kepegawaian']['file_sk_jabatan']) }}</span>
                        <button type="button" class="btn-change-file" data-target="file_sk_jabatan">
                            <i class="fas fa-exchange-alt"></i> Ganti File
                        </button>
                    </div>
                @elseif(old('file_sk_jabatan'))
                    <div class="file-info">
                        <i class="fas fa-check-circle text-success"></i>
                        <span>File sudah diupload sebelumnya</span>
                        <button type="button" class="btn-change-file" data-target="file_sk_jabatan">
                            <i class="fas fa-exchange-alt"></i> Ganti File
                        </button>
                    </div>
                @else
                    <span class="no-file">Belum ada file dipilih</span>
                @endif
            </div>
        </div>
        @error('file_sk_jabatan')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label required">Unggah Bukti SK Pangkat/Golongan Ruang Terakhir</label>
        <div class="form-file">
            <input type="file" name="file_sk_pangkat" class="form-file-input @error('file_sk_pangkat') error @enderror" 
                   accept=".pdf">
            <label class="form-file-label">
                <i class="fas fa-cloud-upload-alt"></i><br>
                Klik untuk mengunggah file PDF (maks. 1MB)
            </label>
            <div class="form-file-name">
                @if(isset($peserta['kepegawaian']['file_sk_pangkat']) && $peserta['kepegawaian']['file_sk_pangkat'])
                    <div class="file-info">
                        <i class="fas fa-check-circle text-success"></i>
                        <span>File sudah diupload: {{ basename($peserta['kepegawaian']['file_sk_pangkat']) }}</span>
                        <button type="button" class="btn-change-file" data-target="file_sk_pangkat">
                            <i class="fas fa-exchange-alt"></i> Ganti File
                        </button>
                    </div>
                @elseif(old('file_sk_pangkat'))
                    <div class="file-info">
                        <i class="fas fa-check-circle text-success"></i>
                        <span>File sudah diupload sebelumnya</span>
                        <button type="button" class="btn-change-file" data-target="file_sk_pangkat">
                            <i class="fas fa-exchange-alt"></i> Ganti File
                        </button>
                    </div>
                @else
                    <span class="no-file">Belum ada file dipilih</span>
                @endif
            </div>
        </div>
        @error('file_sk_pangkat')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-group">
    <label class="form-label">Unggah Surat Pernyataan Komitmen (jika sudah ada dan di tandatangani pejabat pembuat komitmen, namun jika belum maka WAJIB disertakan saat registrasiulang di Puslatbang KMP)</label>
    <div class="form-file">
        <input type="file" name="file_surat_komitmen" class="form-file-input @error('file_surat_komitmen') error @enderror" 
               accept=".pdf">
        <label class="form-file-label">
            <i class="fas fa-cloud-upload-alt"></i><br>
            Klik untuk mengunggah file PDF (maks. 1MB)
        </label>
        <div class="form-file-name">
            @if(isset($pendaftaran['file_surat_komitmen']) && $pendaftaran['file_surat_komitmen'])
                <div class="file-info">
                    <i class="fas fa-check-circle text-success"></i>
                    <span>File sudah diupload: {{ basename($pendaftaran['file_surat_komitmen']) }}</span>
                    <button type="button" class="btn-change-file" data-target="file_surat_komitmen">
                        <i class="fas fa-exchange-alt"></i> Ganti File
                    </button>
                </div>
            @elseif(old('file_surat_komitmen'))
                <div class="file-info">
                    <i class="fas fa-check-circle text-success"></i>
                    <span>File sudah diupload sebelumnya</span>
                    <button type="button" class="btn-change-file" data-target="file_surat_komitmen">
                        <i class="fas fa-exchange-alt"></i> Ganti File
                    </button>
                </div>
            @else
                <span class="no-file">Belum ada file dipilih</span>
            @endif
        </div>
    </div>
    @error('file_surat_komitmen')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="form-group">
    <label class="form-label required">Unggah Scan Pakta Integritas (Formulir menggunakan Kop Instansi)</label>
    <div class="form-file">
        <input type="file" name="file_pakta_integritas" class="form-file-input @error('file_pakta_integritas') error @enderror" 
               accept=".pdf">
        <label class="form-file-label">
            <i class="fas fa-cloud-upload-alt"></i><br>
            Klik untuk mengunggah file PDF (maks. 1MB)
        </label>
        <div class="form-file-name">
            @if(isset($pendaftaran['file_pakta_integritas']) && $pendaftaran['file_pakta_integritas'])
                <div class="file-info">
                    <i class="fas fa-check-circle text-success"></i>
                    <span>File sudah diupload: {{ basename($pendaftaran['file_pakta_integritas']) }}</span>
                    <button type="button" class="btn-change-file" data-target="file_pakta_integritas">
                        <i class="fas fa-exchange-alt"></i> Ganti File
                    </button>
                </div>
            @elseif(old('file_pakta_integritas'))
                <div class="file-info">
                    <i class="fas fa-check-circle text-success"></i>
                    <span>File sudah diupload sebelumnya</span>
                    <button type="button" class="btn-change-file" data-target="file_pakta_integritas">
                        <i class="fas fa-exchange-alt"></i> Ganti File
                    </button>
                </div>
            @else
                <span class="no-file">Belum ada file dipilih</span>
            @endif
        </div>
    </div>
    @error('file_pakta_integritas')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="form-group">
    <label class="form-label ">Unggah Scan Surat Tugas mengikuti pelatihan yang ditandatangani oleh pejabat yang berwenang (jika belum maka WAJIB disertakan saat registrasi ulang di Puslatbang KMP)</label>
    <div class="form-file">
        <input type="file" name="file_surat_tugas" class="form-file-input @error('file_surat_tugas') error @enderror" 
               accept=".pdf">
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
            @elseif(old('file_surat_tugas'))
                <div class="file-info">
                    <i class="fas fa-check-circle text-success"></i>
                    <span>File sudah diupload sebelumnya</span>
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

<div class="form-group">
    <label class="form-label">Unggah Scan Surat Keterangan Kelulusan/Hasil Seleksi calon peserta PKN TK.II (bagi calon peserta yang masih menduduki jabatan administrator/Eselon III)</label>
    <div class="form-file">
        <input type="file" name="file_surat_kelulusan_seleksi" class="form-file-input @error('file_surat_kelulusan_seleksi') error @enderror" 
               accept=".pdf">
        <label class="form-file-label">
            <i class="fas fa-cloud-upload-alt"></i><br>
            Klik untuk mengunggah file PDF (maks. 1MB)
        </label>
        <div class="form-file-name">
            @if(isset($pendaftaran['file_surat_kelulusan_seleksi']) && $pendaftaran['file_surat_kelulusan_seleksi'])
                <div class="file-info">
                    <i class="fas fa-check-circle text-success"></i>
                    <span>File sudah diupload: {{ basename($pendaftaran['file_surat_kelulusan_seleksi']) }}</span>
                    <button type="button" class="btn-change-file" data-target="file_surat_kelulusan_seleksi">
                        <i class="fas fa-exchange-alt"></i> Ganti File
                    </button>
                </div>
            @elseif(old('file_surat_kelulusan_seleksi'))
                <div class="file-info">
                    <i class="fas fa-check-circle text-success"></i>
                    <span>File sudah diupload sebelumnya</span>
                    <button type="button" class="btn-change-file" data-target="file_surat_kelulusan_seleksi">
                        <i class="fas fa-exchange-alt"></i> Ganti File
                    </button>
                </div>
            @else
                <span class="no-file">Belum ada file dipilih</span>
            @endif
        </div>
    </div>
    @error('file_surat_kelulusan_seleksi')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label required">Unggah Surat Keterangan Berbadan Sehat</label>
        <div class="form-file">
            <input type="file" name="file_surat_sehat" class="form-file-input @error('file_surat_sehat') error @enderror" 
                   accept=".pdf">
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
                @elseif(old('file_surat_sehat'))
                    <div class="file-info">
                        <i class="fas fa-check-circle text-success"></i>
                        <span>File sudah diupload sebelumnya</span>
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
    <div class="form-group">
        <label class="form-label required">Unggah Surat Keterangan Bebas Narkoba</label>
        <div class="form-file">
            <input type="file" name="file_surat_bebas_narkoba" class="form-file-input @error('file_surat_bebas_narkoba') error @enderror" 
                   accept=".pdf">
            <label class="form-file-label">
                <i class="fas fa-cloud-upload-alt"></i><br>
                Klik untuk mengunggah file PDF (maks. 1MB)
            </label>
            <div class="form-file-name">
                @if(isset($pendaftaran['file_surat_bebas_narkoba']) && $pendaftaran['file_surat_bebas_narkoba'])
                    <div class="file-info">
                        <i class="fas fa-check-circle text-success"></i>
                        <span>File sudah diupload: {{ basename($pendaftaran['file_surat_bebas_narkoba']) }}</span>
                        <button type="button" class="btn-change-file" data-target="file_surat_bebas_narkoba">
                            <i class="fas fa-exchange-alt"></i> Ganti File
                        </button>
                    </div>
                @elseif(old('file_surat_bebas_narkoba'))
                    <div class="file-info">
                        <i class="fas fa-check-circle text-success"></i>
                        <span>File sudah diupload sebelumnya</span>
                        <button type="button" class="btn-change-file" data-target="file_surat_bebas_narkoba">
                            <i class="fas fa-exchange-alt"></i> Ganti File
                        </button>
                    </div>
                @else
                    <span class="no-file">Belum ada file dipilih</span>
                @endif
            </div>
        </div>
        @error('file_surat_bebas_narkoba')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

{{-- <div class="form-group">
    <label class="form-label required">Upload Pasfoto peserta berwarna</label>
    <div class="form-file">
        <input type="file" name="file_pas_foto" class="form-file-input @error('file_pas_foto') error @enderror" 
               accept=".jpg,.jpeg,.png">
        <label class="form-file-label">
            <i class="fas fa-cloud-upload-alt"></i><br>
            Klik untuk mengunggah file JPG/PNG (maks. 1MB)
        </label>
        <div class="form-file-name">
            @if(isset($peserta['file_pas_foto']) && $peserta['file_pas_foto'])
                <div class="file-info">
                    <i class="fas fa-check-circle text-success"></i>
                    <span>File sudah diupload: {{ basename($peserta['file_pas_foto']) }}</span>
                    <button type="button" class="btn-change-file" data-target="file_pas_foto">
                        <i class="fas fa-exchange-alt"></i> Ganti File
                    </button>
                </div>
            @elseif(old('file_pas_foto'))
                <div class="file-info">
                    <i class="fas fa-check-circle text-success"></i>
                    <span>File sudah diupload sebelumnya</span>
                    <button type="button" class="btn-change-file" data-target="file_pas_foto">
                        <i class="fas fa-exchange-alt"></i> Ganti File
                    </button>
                </div>
            @else
                <span class="no-file">Belum ada file dipilih</span>
            @endif
        </div>
    </div>
    @error('file_pas_foto')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div> --}}

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
            <p><strong>Preview Foto 3×4:</strong></p>
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
                <small style="font-size: 0.85em; color: #666;">Foto akan dipotong ke ukuran 3×4</small>
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
                    <small style="font-size: 0.85em; color: #666;">Foto akan dipotong ke ukuran 3×4</small>
                </label>
                <div class="form-file-name" id="file-name-display">
                    <span class="no-file">Belum ada file dipilih</span>
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
            <p><strong>Preview Foto 3×4:</strong></p>
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

<div class="form-group">
    <label class="form-label required">Upload Foto KTP</label>
    <div class="form-file">
        <input type="file" name="file_ktp" class="form-file-input @error('file_ktp') error @enderror" 
               accept=".pdf,.jpg,.jpeg,.png">
        <label class="form-file-label">
            <i class="fas fa-cloud-upload-alt"></i><br>
            Klik untuk mengunggah file JPG/PNG (maks. 1MB)
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
            @elseif(old('file_ktp'))
                <div class="file-info">
                    <i class="fas fa-check-circle text-success"></i>
                    <span>File sudah diupload sebelumnya</span>
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

<div class="form-section-header">
    <i class="fas fa-user-graduate"></i> Data Mentor
</div>

<div class="form-group">
    <label class="form-label required">Apakah sudah ada penunjukan Mentor?</label>
    <select name="sudah_ada_mentor" id="sudah_ada_mentor" class="form-select @error('sudah_ada_mentor') error @enderror"
        required>
        <option value="">Pilih</option>
        <option value="Ya" {{ ($peserta['sudah_ada_mentor'] ?? old('sudah_ada_mentor')) == 'Ya' ? 'selected' : '' }}>Ya
        </option>
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
                    Daftar mentor</option>
                <option value="tambah" {{ ($peserta['mentor_mode'] ?? old('mentor_mode')) == 'tambah' ? 'selected' : '' }}>Tambah mentor (Jika tidak ada di daftar mentor)</option>
            </select>
        </div>
        @error('mentor_mode')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <!-- Form untuk memilih mentor yang sudah ada -->
    <div id="select-mentor-form"
        style="display: {{ ($peserta['mentor_mode'] ?? old('mentor_mode')) == 'pilih' ? 'block' : 'none' }};">
        <div class="form-group">
            <label class="form-label required">Pilih Mentor</label>
            <select name="id_mentor" id="id_mentor" class="form-select @error('id_mentor') error @enderror">
                <option value="">Pilih Mentor...</option>
                <!-- Data mentor akan dimuat via AJAX -->
            </select>
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
                    class="form-input uppercase @error('nip_mentor') error @enderror"
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
                <label class="form-label">Nomor Rekening Mentor</label>
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
            <!-- Di dalam #select-mentor-form (sekitar line 444-468) -->
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
                <label class="form-label required">NIP Mentor</label>
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
                <label class="form-label">Nomor Rekening Mentor</label>
                <input type="text" name="nomor_rekening_mentor_baru" id="nomor_rekening_mentor_baru"
                    class="form-input @error('nomor_rekening_mentor_baru') error @enderror"
                    placeholder="Contoh: Bank Mandiri, 174xxxxxxxxx a.n Nanang Wijaya"
                    value="{{ $peserta['nomor_rekening_mentor_baru'] ?? old('nomor_rekening_mentor_baru') }}">
                @error('nomor_rekening_mentor_baru')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">NPWP Mentor</label>
                <input type="text" name="npwp_mentor_baru" id="npwp_mentor_baru"
                    class="form-input @error('npwp_mentor_baru') error @enderror"
                    value="{{ $peserta['npwp_mentor_baru'] ?? old('npwp_mentor_baru') }}"
                    placeholder="Contoh: 123456789012345">
                @error('npwp_mentor_baru')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <!-- Di dalam #add-mentor-form (sekitar line 487-528) -->
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
</div>

<style>
    .alert {
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 20px;
        border-left: 4px solid;
    }

    .alert-info {
        background-color: rgba(66, 153, 225, 0.1);
        border-color: var(--accent-color);
        color: var(--secondary-color);
    }

    .alert i {
        margin-right: 10px;
    }

    .form-hint {
        font-size: 0.85rem;
        color: var(--muted-color);
        margin-top: 4px;
        display: block;
    }
</style>