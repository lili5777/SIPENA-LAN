<div class="form-section-header">
    <i class="fas fa-user-graduate"></i> Form Pembaruan Data LATSAR
</div>

<input type="hidden" name="peserta_id" id="peserta_id" value="{{ $peserta['id'] ?? '' }}">
<input type="hidden" name="pendaftaran_id" id="pendaftaran_id" value="{{ $pendaftaran['id'] ?? '' }}">

<div class="form-row">
    <div class="form-group">
        <label class="form-label required">Nama Lengkap dan Gelar</label>
        <input type="text" name="nama_lengkap" class="form-input @error('nama_lengkap') error @enderror"
            value="{{ $peserta['nama_lengkap'] ?? old('nama_lengkap') }}" required>
        @error('nama_lengkap')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label required">NIP</label>
        <input type="text" name="nip_nrp" class="form-input @error('nip_nrp') error @enderror" 
            value="{{ $peserta['nip_nrp'] ?? old('nip_nrp') }}" required readonly>
        <small class="form-hint">NIP/NRP tidak dapat diubah</small>
        @error('nip_nrp')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label">Nama Panggilan</label>
        <input type="text" name="nama_panggilan" class="form-input @error('nama_panggilan') error @enderror"
            value="{{ $peserta['nama_panggilan'] ?? old('nama_panggilan') }}">
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
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label required">Email</label>
        <input type="email" name="email_pribadi" class="form-input @error('email_pribadi') error @enderror" 
            value="{{ $peserta['email_pribadi'] ?? old('email_pribadi') }}" required>
        @error('email_pribadi')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label required">No HP (yang terhubung Whatsapp)</label>
        <input type="tel" name="nomor_hp" class="form-input @error('nomor_hp') error @enderror" 
            value="{{ $peserta['nomor_hp'] ?? old('nomor_hp') }}" required>
        @error('nomor_hp')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label required">Tempat Lahir (sesuai SK)</label>
        <input type="text" name="tempat_lahir" class="form-input @error('tempat_lahir') error @enderror"
            value="{{ $peserta['tempat_lahir'] ?? old('tempat_lahir') }}" required>
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

<div class="form-group">
    <label class="form-label required">Alamat Rumah</label>
    <textarea name="alamat_rumah" class="form-textarea @error('alamat_rumah') error @enderror"
        required>{{ $peserta['alamat_rumah'] ?? old('alamat_rumah') }}</textarea>
    @error('alamat_rumah')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>



<div class="form-section-header">
    <i class="fas fa-building"></i> Data Instansi
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label required">Asal Instansi</label>
        <input type="text" name="asal_instansi" class="form-input @error('asal_instansi') error @enderror"
            placeholder="Contoh: Dinas Kesehatan" 
            value="{{ $peserta['kepegawaian']['asal_instansi'] ?? old('asal_instansi') }}" required>
        @error('asal_instansi')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label required">Jabatan</label>
        <input type="text" name="jabatan" class="form-input @error('jabatan') error @enderror"
            value="{{ $peserta['kepegawaian']['jabatan'] ?? old('jabatan') }}" required placeholder="Contoh : Perencana Ahli Pertama">
        @error('jabatan')
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
        <select name="id_kabupaten_kota" class="form-select @error('id_kabupaten_kota') error @enderror" required disabled>
            <option value="">Pilih Kabupaten/Kota (Pilih Provinsi Dahulu)</option>
        </select>
        @error('id_kabupaten_kota')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-group">
    <label class="form-label">Alamat Kantor</label>
    <textarea name="alamat_kantor"
        class="form-textarea @error('alamat_kantor') error @enderror">{{ $peserta['kepegawaian']['alamat_kantor'] ?? old('alamat_kantor') }}</textarea>
    @error('alamat_kantor')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label required">Golongan Ruang</label>
        <select name="golongan_ruang" class="form-select @error('golongan_ruang') error @enderror" required>
            <option value="">Pilih</option>
            <option value="III/C" {{ ($peserta['kepegawaian']['golongan_ruang'] ?? old('golongan_ruang')) == 'III/C' ? 'selected' : '' }}>III/C</option>
            <option value="III/B" {{ ($peserta['kepegawaian']['golongan_ruang'] ?? old('golongan_ruang')) == 'III/B' ? 'selected' : '' }}>III/B</option>
            <option value="III/A" {{ ($peserta['kepegawaian']['golongan_ruang'] ?? old('golongan_ruang')) == 'III/A' ? 'selected' : '' }}>III/A</option>
            <option value="II/C" {{ ($peserta['kepegawaian']['golongan_ruang'] ?? old('golongan_ruang')) == 'II/C' ? 'selected' : '' }}>II/C</option>
            <option value="II/A" {{ ($peserta['kepegawaian']['golongan_ruang'] ?? old('golongan_ruang')) == 'II/A' ? 'selected' : '' }}>II/A</option>
        </select>
        @error('golongan_ruang')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label required">Pangkat</label>
        <select name="pangkat" class="form-select @error('pangkat') error @enderror" required>
            <option value="">Pilih</option>
            <option value="Penata" {{ ($peserta['kepegawaian']['pangkat'] ?? old('pangkat')) == 'Penata' ? 'selected' : '' }}>Penata</option>
            <option value="Penata Muda Tingkat I" {{ ($peserta['kepegawaian']['pangkat'] ?? old('pangkat')) == 'Penata Muda Tingkat I' ? 'selected' : '' }}>Penata
                Muda Tingkat I</option>
            <option value="Penata Muda" {{ ($peserta['kepegawaian']['pangkat'] ?? old('pangkat')) == 'Penata Muda' ? 'selected' : '' }}>Penata Muda</option>
            <option value="Pengatur" {{ ($peserta['kepegawaian']['pangkat'] ?? old('pangkat')) == 'Pengatur' ? 'selected' : '' }}>Pengatur</option>
            <option value="Pengatur Muda" {{ ($peserta['kepegawaian']['pangkat'] ?? old('pangkat')) == 'Pengatur Muda' ? 'selected' : '' }}>Pengatur Muda</option>
        </select>
        @error('pangkat')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label required">Nomor SK CPNS</label>
        <input type="text" name="nomor_sk_cpns" class="form-input @error('nomor_sk_cpns') error @enderror"
            value="{{ $peserta['kepegawaian']['nomor_sk_cpns'] ?? old('nomor_sk_cpns') }}" required>
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
        <label class="form-label required">Pendidikan Terakhir</label>
        <select name="pendidikan_terakhir" class="form-select @error('pendidikan_terakhir') error @enderror" required>
            <option value="">Pilih</option>
            <option value="SMU" {{ ($peserta['pendidikan_terakhir'] ?? old('pendidikan_terakhir')) == 'SMU' ? 'selected' : '' }}>SMU</option>
            <option value="D3" {{ ($peserta['pendidikan_terakhir'] ?? old('pendidikan_terakhir')) == 'D3' ? 'selected' : '' }}>D3</option>
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
        <input type="text" name="bidang_studi" class="form-input @error('bidang_studi') error @enderror"
            value="{{ $peserta['bidang_studi'] ?? old('bidang_studi') }}" required placeholder="Contoh : Ilmu Hukum">
        @error('bidang_studi')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label required">Bidang Keahlian/Kepakaran</label>
        <input type="text" name="bidang_keahlian" class="form-input @error('bidang_keahlian') error @enderror"
            value="{{ $peserta['bidang_keahlian'] ?? old('bidang_keahlian') }}" required placeholder="Contoh : Manajemen Pemerintahan ">
        @error('bidang_keahlian')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-section-header">
    <i class="fas fa-heart"></i> Data Lainnya
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label required">Olahraga Kegemaran/Hobi</label>
        <input type="text" name="olahraga_hobi" class="form-input @error('olahraga_hobi') error @enderror"
            value="{{ $peserta['olahraga_hobi'] ?? old('olahraga_hobi') }}" required>
        @error('olahraga_hobi')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label required">Apakah Saudara/i adalah perokok?</label>
        <select name="perokok" class="form-select @error('perokok') error @enderror" required>
            <option value="">Pilih</option>
            <option value="Ya" {{ ($peserta['perokok'] ?? old('perokok')) == 'Ya' ? 'selected' : '' }}>Ya</option>
            <option value="Tidak" {{ ($peserta['perokok'] ?? old('perokok')) == 'Tidak' ? 'selected' : '' }}>Tidak</option>
        </select>
        @error('perokok')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label">Kondisi Peserta</label>
        <textarea name="kondisi_peserta"
            class="form-textarea @error('kondisi_peserta') error @enderror">{{ $peserta['kondisi_peserta'] ?? old('kondisi_peserta') }}</textarea>
        @error('kondisi_peserta')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label required">Status Perkawinan</label>
        <select name="status_perkawinan" id="status_perkawinan" class="form-select @error('status_perkawinan') error @enderror" required>
            <option value="">Pilih</option>
            <option value="Belum Menikah" {{ ($peserta['status_perkawinan'] ?? old('status_perkawinan')) == 'Belum Menikah' ? 'selected' : '' }}>Belum
                Menikah</option>
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
        <input type="text" name="nama_pasangan" id="nama_pasangan" class="form-input @error('nama_pasangan') error @enderror"
            value="{{ $peserta['nama_pasangan'] ?? old('nama_pasangan') }}"
            {{ ($peserta['status_perkawinan'] ?? old('status_perkawinan')) != 'Menikah' ? 'disabled' : '' }}>
        @error('nama_pasangan')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        <small class="form-hint">Hanya bisa diisi jika status "Menikah"</small>
    </div>
    
</div>
<div class="form-row">
    <div class="form-group">
        <label class="form-label">Ukuran Kaos</label>
        <select name="ukuran_kaos" class="form-select @error('ukuran_kaos') error @enderror">
            <option value="">Pilih</option>
            <option value="S" {{ ($peserta['ukuran_kaos'] ?? old('ukuran_kaos')) == 'S' ? 'selected' : '' }}>S</option>
            <option value="M" {{ ($peserta['ukuran_kaos'] ?? old('ukuran_kaos')) == 'M' ? 'selected' : '' }}>M</option>
            <option value="L" {{ ($peserta['ukuran_kaos'] ?? old('ukuran_kaos')) == 'L' ? 'selected' : '' }}>L</option>
            <option value="XL" {{ ($peserta['ukuran_kaos'] ?? old('ukuran_kaos')) == 'XL' ? 'selected' : '' }}>XL</option>
            <option value="XXL" {{ ($peserta['ukuran_kaos'] ?? old('ukuran_kaos')) == 'XXL' ? 'selected' : '' }}>XXL</option>
            <option value="XXXL" {{ ($peserta['ukuran_kaos'] ?? old('ukuran_kaos')) == 'XXXL' ? 'selected' : '' }}>XXXL</option>
        </select>
        @error('ukuran_kaos')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label">Ukuran Kaos Olahraga</label>
        <select name="ukuran_training" class="form-select @error('ukuran_training') error @enderror">
            <option value="">Pilih</option>
            <option value="S" {{ ($peserta['ukuran_training'] ?? old('ukuran_training')) == 'S' ? 'selected' : '' }}>S</option>
            <option value="M" {{ ($peserta['ukuran_training'] ?? old('ukuran_training')) == 'M' ? 'selected' : '' }}>M</option>
            <option value="L" {{ ($peserta['ukuran_training'] ?? old('ukuran_training')) == 'L' ? 'selected' : '' }}>L</option>
            <option value="XL" {{ ($peserta['ukuran_training'] ?? old('ukuran_training')) == 'XL' ? 'selected' : '' }}>XL</option>
            <option value="XXL" {{ ($peserta['ukuran_training'] ?? old('ukuran_training')) == 'XXL' ? 'selected' : '' }}>XXL</option>
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
            <option value="S" {{ ($peserta['ukuran_celana'] ?? old('ukuran_celana')) == 'S' ? 'selected' : '' }}>S</option>
            <option value="M" {{ ($peserta['ukuran_celana'] ?? old('ukuran_celana')) == 'M' ? 'selected' : '' }}>M</option>
            <option value="L" {{ ($peserta['ukuran_celana'] ?? old('ukuran_celana')) == 'L' ? 'selected' : '' }}>L</option>
            <option value="XL" {{ ($peserta['ukuran_celana'] ?? old('ukuran_celana')) == 'XL' ? 'selected' : '' }}>XL</option>
            <option value="XXL" {{ ($peserta['ukuran_celana'] ?? old('ukuran_celana')) == 'XXL' ? 'selected' : '' }}>XXL</option>
            <option value="XXXL" {{ ($peserta['ukuran_celana'] ?? old('ukuran_celana')) == 'XXXL' ? 'selected' : '' }}>XXXL</option>
        </select>
        @error('ukuran_celana')
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

<div id="mentor-container" style="display: {{ ($peserta['sudah_ada_mentor'] ?? old('sudah_ada_mentor')) == 'Ya' ? 'block' : 'none' }};">
    <div class="form-group">
        <label class="form-label required">Pilih Mentor atau Tambah Baru</label>
        <div class="mentor-options">
            <select name="mentor_mode" id="mentor_mode" class="form-select @error('mentor_mode') error @enderror">
                <option value="">Pilih Menu</option>
                <option value="pilih" {{ ($peserta['mentor_mode'] ?? old('mentor_mode')) == 'pilih' ? 'selected' : '' }}>Daftar mentor
                </option>
                <option value="tambah" {{ ($peserta['mentor_mode'] ?? old('mentor_mode')) == 'tambah' ? 'selected' : '' }}>Tambah mentor(Jika tidak ada di daftar mentor)</option>
            </select>
        </div>
        @error('mentor_mode')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <!-- Form untuk memilih mentor yang sudah ada -->
    <div id="select-mentor-form" style="display: {{ ($peserta['mentor_mode'] ?? old('mentor_mode')) == 'pilih' ? 'block' : 'none' }};">
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
                    class="form-input @error('nama_mentor') error @enderror" value="{{ $peserta['nama_mentor'] ?? old('nama_mentor') }}" readonly>
                @error('nama_mentor')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label required">Jabatan Mentor</label>
                <input type="text" name="jabatan_mentor" id="jabatan_mentor_select"
                    class="form-input @error('jabatan_mentor') error @enderror" value="{{ $peserta['jabatan_mentor'] ?? old('jabatan_mentor') }}"
                    readonly>
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
                    placeholder="Bank Mandiri, 174xxxxxxxxx a.n Nanang Wijaya"
                    value="{{ $peserta['nomor_rekening_mentor'] ?? old('nomor_rekening_mentor') }}" readonly>
                @error('nomor_rekening_mentor')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">NPWP Mentor</label>
                <input type="text" name="npwp_mentor" id="npwp_mentor_select"
                    class="form-input @error('npwp_mentor') error @enderror" value="{{ $peserta['npwp_mentor'] ?? old('npwp_mentor') }}" readonly>
                @error('npwp_mentor')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
    </div>

    <!-- Form untuk menambah mentor baru -->
    <div id="add-mentor-form" style="display: {{ ($peserta['mentor_mode'] ?? old('mentor_mode')) == 'tambah' ? 'block' : 'none' }};">
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            Silakan lengkapi data mentor baru
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label required">Nama Mentor</label>
                <input type="text" name="nama_mentor_baru" id="nama_mentor_baru"
                    class="form-input @error('nama_mentor_baru') error @enderror" value="{{ $peserta['nama_mentor_baru'] ?? old('nama_mentor_baru') }}">
                @error('nama_mentor_baru')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label required">Jabatan Mentor</label>
                <input type="text" name="jabatan_mentor_baru" id="jabatan_mentor_baru"
                    class="form-input @error('jabatan_mentor_baru') error @enderror"
                    value="{{ $peserta['jabatan_mentor_baru'] ?? old('jabatan_mentor_baru') }}">
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
                    placeholder="Bank Mandiri, 174xxxxxxxxx a.n Nanang Wijaya"
                    value="{{ $peserta['nomor_rekening_mentor_baru'] ?? old('nomor_rekening_mentor_baru') }}">
                @error('nomor_rekening_mentor_baru')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">NPWP Mentor</label>
                <input type="text" name="npwp_mentor_baru" id="npwp_mentor_baru"
                    class="form-input @error('npwp_mentor_baru') error @enderror" value="{{ $peserta['npwp_mentor_baru'] ?? old('npwp_mentor_baru') }}">
                @error('npwp_mentor_baru')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
    </div>
</div>



        <input type="hidden" class="form-input" 
            value="{{ $pendaftaran['angkatan']['nama_angkatan'] ?? 'Tidak tersedia' }}" readonly>
 
    
        <input type="hidden" class="form-input" 
            value="{{ $pendaftaran['angkatan']['tahun'] ?? 'Tidak tersedia' }}" readonly>
    


<div class="form-section-header">
    <i class="fas fa-file-upload"></i> Dokumen Pendukung
</div>

<div class="alert alert-info">
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
    <label class="form-label required">Unggah scan SPMT</label>
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

<!-- Pas Foto peserta -->
<div class="form-group">
    <label class="form-label required">Unggah Pas Foto peserta (untuk digunakan di name tag peserta)</label>
    <div class="form-file">
        <input type="file" name="file_pas_foto" class="form-file-input @error('file_pas_foto') error @enderror" accept=".jpg,.jpeg,.png">
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
            @else
                <span class="no-file">Belum ada file dipilih</span>
            @endif
        </div>
    </div>
    @error('file_pas_foto')
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