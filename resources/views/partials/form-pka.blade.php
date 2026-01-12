<div class="form-section-header">
    <i class="fas fa-chalkboard-teacher"></i> Form kesediaan PKA/PKP
</div>

<!-- Hidden fields untuk ID peserta dan pendaftaran -->
<input type="hidden" name="peserta_id" id="peserta_id" value="{{ $peserta['id'] ?? '' }}">
<input type="hidden" name="pendaftaran_id" id="pendaftaran_id" value="{{ $pendaftaran['id'] ?? '' }}">

<div class="form-row">
    <div class="form-group">
        <label class="form-label required">NIP/NRP</label>
        <input type="text" name="nip_nrp" class="form-input @error('nip_nrp') error @enderror" 
               value="{{ $peserta['nip_nrp'] ?? old('nip_nrp') }}" required readonly>
        <small class="form-hint">NIP/NRP tidak dapat diubah</small>
        @error('nip_nrp')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label required">Nama lengkap gelar</label>
        <input type="text" name="nama_lengkap" class="form-input @error('nama_lengkap') error @enderror" 
               value="{{ $peserta['nama_lengkap'] ?? old('nama_lengkap') }}" required>
        @error('nama_lengkap')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-section-header">
    <i class="fas fa-user"></i> Data Pribadi
</div>

<div class="form-row">
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
        <label class="form-label required">Tempat lahir (sesuai SK)</label>
        <input type="text" name="tempat_lahir" class="form-input @error('tempat_lahir') error @enderror" 
               value="{{ $peserta['tempat_lahir'] ?? old('tempat_lahir') }}" required>
        @error('tempat_lahir')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label required">Tanggal lahir</label>
        <input type="date" name="tanggal_lahir" class="form-input @error('tanggal_lahir') error @enderror" 
               value="{{ $peserta['tanggal_lahir'] ?? old('tanggal_lahir') }}" required>
        @error('tanggal_lahir')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-group">
    <label class="form-label required">Alamat rumah</label>
    <textarea name="alamat_rumah" class="form-textarea @error('alamat_rumah') error @enderror" required>{{ $peserta['alamat_rumah'] ?? old('alamat_rumah') }}</textarea>
    @error('alamat_rumah')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="form-group">
    <label class="form-label required">Alamat kantor</label>
    <textarea name="alamat_kantor" class="form-textarea @error('alamat_kantor') error @enderror" required>{{ $peserta['kepegawaian']['alamat_kantor'] ?? old('alamat_kantor') }}</textarea>
    @error('alamat_kantor')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="form-section-header">
    <i class="fas fa-building"></i> Data Kepegawaian
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label required">Asal instansi</label>
        <input type="text" name="asal_instansi" class="form-input @error('asal_instansi') error @enderror" 
               value="{{ $peserta['kepegawaian']['asal_instansi'] ?? old('asal_instansi') }}" required placeholder="Contoh : Pemerintah Kabupaten Sorong">
        @error('asal_instansi')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label required">Instansi Detail/Unit Kerja</label>
        <input type="text" name="unit_kerja" class="form-input @error('unit_kerja') error @enderror" 
               value="{{ $peserta['kepegawaian']['unit_kerja'] ?? old('unit_kerja') }}" required placeholder="Contoh: Dinas Pendidikan Kota Makassar">
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
        <select name="id_kabupaten_kota" class="form-select @error('id_kabupaten_kota') error @enderror" required disabled>
            <option value="">Pilih Kabupaten/Kota (Pilih Provinsi Dahulu)</option>
        </select>
        @error('id_kabupaten_kota')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label required">Jabatan</label>
        <input type="text" name="jabatan" class="form-input @error('jabatan') error @enderror" 
               value="{{ $peserta['kepegawaian']['jabatan'] ?? old('jabatan') }}" required>
        @error('jabatan')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label required">Eselon</label>
        <select name="eselon" class="form-select @error('eselon') error @enderror" required>
            <option value="">Pilih</option>
            <option value="III" {{ ($peserta['kepegawaian']['eselon'] ?? old('eselon')) == 'III' ? 'selected' : '' }}>III</option>
            <option value="IV" {{ ($peserta['kepegawaian']['eselon'] ?? old('eselon')) == 'IV' ? 'selected' : '' }}>IV</option>
        </select>
        @error('eselon')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-group">
    <label class="form-label ">Unggah Scan fotokopi kelulusan/hasil seleksi calon peserta PKA / Sertifikat/Piagam Penghargaan Terbaik (Jika Anda Eselon IV)</label>
    <div class="form-file">
        <input type="file" name="file_surat_kelulusan_seleksi" class="form-file-input @error('file_surat_kelulusan_seleksi') error @enderror" 
               accept=".pdf">
        <label class="form-file-label">
            <i class="fas fa-cloud-upload-alt"></i><br>
            Klik untuk mengunggah file PDF (maks. 5MB)
        </label>
        <div class="form-file-name">
            @if($pendaftaran['file_surat_kelulusan_seleksi'] ?? false)
                File sudah diupload sebelumnya
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

<div class="form-row">
    <div class="form-group">
        <label class="form-label ">Tahun Lulus PKP/PIM IV</label>
        <input type="number" name="tahun_lulus_pkp_pim_iv" class="form-input @error('tahun_lulus_pkp_pim_iv') error @enderror" 
               min="1900" max="2099" value="{{ $peserta['kepegawaian']['tahun_lulus_pkp_pim_iv'] ?? old('tahun_lulus_pkp_pim_iv') }}" >
        @error('tahun_lulus_pkp_pim_iv')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label required">Pangkat / Golongan Ruang</label>
        <select name="golongan_ruang" class="form-select @error('golongan_ruang') error @enderror" required>
            <option value="">Pilih</option>
            <option value="Pembina Utama, IV/E" {{ ($peserta['kepegawaian']['golongan_ruang'] ?? old('golongan_ruang')) == 'Pembina Utama, IV/E' ? 'selected' : '' }}>Pembina Utama, IV/E</option>
            <option value="Pembina Utama Madya, IV/D" {{ ($peserta['kepegawaian']['golongan_ruang'] ?? old('golongan_ruang')) == 'Pembina Utama Madya, IV/D' ? 'selected' : '' }}>Pembina Utama Madya, IV/D</option>
            <option value="Pembina Utama Muda, IV/C" {{ ($peserta['kepegawaian']['golongan_ruang'] ?? old('golongan_ruang')) == 'Pembina Utama Muda, IV/C' ? 'selected' : '' }}>Pembina Utama Muda, IV/C</option>
            <option value="Pembina Tingkat I, IV/B" {{ ($peserta['kepegawaian']['golongan_ruang'] ?? old('golongan_ruang')) == 'Pembina Tingkat I, IV/B' ? 'selected' : '' }}>Pembina Tingkat I, IV/B</option>
            <option value="Pembina, IV/A" {{ ($peserta['kepegawaian']['golongan_ruang'] ?? old('golongan_ruang')) == 'Pembina, IV/A' ? 'selected' : '' }}>Pembina, IV/A</option>
            <option value="Penata Tingkat I, III/D" {{ ($peserta['kepegawaian']['golongan_ruang'] ?? old('golongan_ruang')) == 'Penata Tingkat I, III/D' ? 'selected' : '' }}>Penata Tingkat I, III/D</option>
            <option value="Penata, III/C" {{ ($peserta['kepegawaian']['golongan_ruang'] ?? old('golongan_ruang')) == 'Penata, III/C' ? 'selected' : '' }}>Penata, III/C</option>
            <option value="Penata Muda Tingkat I, III/B" {{ ($peserta['kepegawaian']['golongan_ruang'] ?? old('golongan_ruang')) == 'Penata Muda Tingkat I, III/B' ? 'selected' : '' }}>Penata Muda Tingkat I, III/B</option>
            <option value="Penata Muda, III/A" {{ ($peserta['kepegawaian']['golongan_ruang'] ?? old('golongan_ruang')) == 'Penata Muda, III/A' ? 'selected' : '' }}>Penata Muda, III/A</option>
        </select>
        @error('golongan_ruang')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label required">No WA</label>
        <input type="tel" name="nomor_hp" class="form-input @error('nomor_hp') error @enderror" 
               value="{{ $peserta['nomor_hp'] ?? old('nomor_hp') }}" required>
        @error('nomor_hp')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label required">Email pribadi</label>
        <input type="email" name="email_pribadi" class="form-input @error('email_pribadi') error @enderror" 
               value="{{ $peserta['email_pribadi'] ?? old('email_pribadi') }}" required>
        @error('email_pribadi')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-section-header">
    <i class="fas fa-graduation-cap"></i> Data Pendidikan
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label required">Pendidikan terakhir</label>
        <select name="pendidikan_terakhir" class="form-select @error('pendidikan_terakhir') error @enderror" required>
            <option value="">Pilih</option>
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
               value="{{ $peserta['bidang_studi'] ?? old('bidang_studi') }}" required>
        @error('bidang_studi')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-section-header">
    <i class="fas fa-heart"></i> Data Lainnya
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
        <label class="form-label required">Apakah Saudara Merokok ?</label>
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
        <label class="form-label required">Olahraga Kegemaran/Hobi</label>
        <input type="text" name="olahraga_hobi" class="form-input @error('olahraga_hobi') error @enderror" 
               value="{{ $peserta['olahraga_hobi'] ?? old('olahraga_hobi') }}" required>
        @error('olahraga_hobi')
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
                <option value="tambah" {{ ($peserta['mentor_mode'] ?? old('mentor_mode')) == 'tambah' ? 'selected' : '' }}>Tambah mentor(Jika tidak ada
                    di daftar mentor)</option>
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

<div class="form-group">
    <label class="form-label">Unggah Form Persetujuan Mentor</label>
    <div class="form-file">
        <input type="file" name="file_persetujuan_mentor"
            class="form-file-input @error('file_persetujuan_mentor') error @enderror" accept=".pdf">
        <label class="form-file-label">
            <i class="fas fa-cloud-upload-alt"></i><br>
            Klik untuk mengunggah file PDF (maks. 5MB)
        </label>
        <div class="form-file-name">
            @if($pendaftaran['file_persetujuan_mentor'] ?? false)
                File sudah diupload sebelumnya
            @elseif(old('file_persetujuan_mentor'))
                File sudah diupload sebelumnya
            @else
                Belum ada file dipilih
            @endif
        </div>
    </div>
    @error('file_persetujuan_mentor')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="form-section-header">
    <i class="fas fa-file-upload"></i> Dokumen Pendukung
</div>

<div class="alert alert-info">
    <i class="fas fa-info-circle"></i>
    <strong>Catatan:</strong> File yang sudah diupload sebelumnya akan tetap tersimpan. Upload ulang hanya jika ingin mengganti file.
</div>

<div class="form-group">
    <label class="form-label required">Foto KTP</label>
    <div class="form-file">
        <input type="file" name="file_ktp" class="form-file-input @error('file_ktp') error @enderror" 
               accept=".jpg,.jpeg,.png">
        <label class="form-file-label">
            <i class="fas fa-cloud-upload-alt"></i><br>
            Klik untuk mengunggah file JPG/PNG (maks. 5MB)
        </label>
        <div class="form-file-name">
            @if($peserta['file_ktp'] ?? false)
                File sudah diupload sebelumnya
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

<div class="form-row">
    <div class="form-group">
        <label class="form-label required">Nomor SK Jabatan Terakhir</label>
        <input type="text" name="nomor_sk_terakhir" class="form-input @error('nomor_sk_terakhir') error @enderror" 
               value="{{ $peserta['kepegawaian']['nomor_sk_jabatan'] ?? old('nomor_sk_terakhir') }}" required>
        @error('nomor_sk_terakhir')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label required">Tanggal SK Jabatan Terakhir</label>
        <input type="date" name="tanggal_sk_jabatan" class="form-input @error('tanggal_sk_jabatan') error @enderror" 
               value="{{ $peserta['kepegawaian']['tanggal_sk_jabatan'] ?? old('tanggal_sk_jabatan') }}" required>
        @error('tanggal_sk_jabatan')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label required">Unggah Bukti SK Jabatan Terakhir (Definitif)</label>
        <div class="form-file">
            <input type="file" name="file_sk_jabatan" class="form-file-input @error('file_sk_jabatan') error @enderror" 
                   accept=".pdf">
            <label class="form-file-label">
                <i class="fas fa-cloud-upload-alt"></i><br>
                Klik untuk mengunggah file PDF (maks. 5MB)
            </label>
            <div class="form-file-name">
                @if($peserta['kepegawaian']['file_sk_jabatan'] ?? false)
                    File sudah diupload sebelumnya
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
        <label class="form-label required">Unggah Bukti SK Pangkat / Golongan Ruang Terakhir</label>
        <div class="form-file">
            <input type="file" name="file_sk_pangkat" class="form-file-input @error('file_sk_pangkat') error @enderror" 
                   accept=".pdf">
            <label class="form-file-label">
                <i class="fas fa-cloud-upload-alt"></i><br>
                Klik untuk mengunggah file PDF (maks. 5MB)
            </label>
            <div class="form-file-name">
                @if($peserta['kepegawaian']['file_sk_pangkat'] ?? false)
                    File sudah diupload sebelumnya
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

<div class="form-group">
    <label class="form-label required">Unggah Scan Formulir Kesediaan (file dapat diunduh di <a href="https://bit.ly/3VtcljN">Disini</a>)</label>

    <div class="form-file">
        <input type="file" name="file_surat_kesediaan" class="form-file-input @error('file_surat_kesediaan') error @enderror" 
               accept=".pdf">
        <label class="form-file-label">
            <i class="fas fa-cloud-upload-alt"></i><br>
            Klik untuk mengunggah file PDF (maks. 5MB)
        </label>
        <div class="form-file-name">
            @if($pendaftaran['file_surat_kesediaan'] ?? false)
                File sudah diupload sebelumnya
            @elseif(old('file_surat_kesediaan'))
                File sudah diupload sebelumnya
            @else
                Belum ada file dipilih
            @endif
        </div>
    </div>
    @error('file_surat_kesediaan')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="form-group">
    <label class="form-label required">Unggah Scan Pakta Integritas (Formulir menggunakan Kop Instansi
    file dapat diunduh di <a href="https://bit.ly/3VtcljN">Disini</a>)</label>
    <div class="form-file">
        <input type="file" name="file_pakta_integritas" class="form-file-input @error('file_pakta_integritas') error @enderror" 
               accept=".pdf">
        <label class="form-file-label">
            <i class="fas fa-cloud-upload-alt"></i><br>
            Klik untuk mengunggah file PDF (maks. 5MB)
        </label>
        <div class="form-file-name">
            @if($pendaftaran['file_pakta_integritas'] ?? false)
                File sudah diupload sebelumnya
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

<div class="form-group">
    <label class="form-label required">Unggah Scan Surat Tugas mengikuti pelatihan yang ditandatangani oleh pejabat yang berwenang (jika surat tugas sudah ada, namun jika belum maka WAJIB disertakan  pada masa klasikal di Puslatbang
    KMP)</label>
    <div class="form-file">
        <input type="file" name="file_surat_tugas" class="form-file-input @error('file_surat_tugas') error @enderror" 
               accept=".pdf">
        <label class="form-file-label">
            <i class="fas fa-cloud-upload-alt"></i><br>
            Klik untuk mengunggah file PDF (maks. 5MB)
        </label>
        <div class="form-file-name">
            @if($pendaftaran['file_surat_tugas'] ?? false)
                File sudah diupload sebelumnya
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

<div class="form-row">
    <div class="form-group">
        <label class="form-label">Nomor Telepon Kantor</label>
        <input type="tel" name="nomor_telepon_kantor" class="form-input @error('nomor_telepon_kantor') error @enderror" 
               value="{{ $peserta['kepegawaian']['nomor_telepon_kantor'] ?? old('nomor_telepon_kantor') }}">
        @error('nomor_telepon_kantor')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label">E-mail Kantor</label>
        <input type="email" name="email_kantor" class="form-input @error('email_kantor') error @enderror" 
               value="{{ $peserta['kepegawaian']['email_kantor'] ?? old('email_kantor') }}">
        @error('email_kantor')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-group">
    <label class="form-label required">Unggah Pas Foto peserta</label>
    <div class="form-file">
        <input type="file" name="file_pas_foto" class="form-file-input @error('file_pas_foto') error @enderror" 
               accept=".jpg,.jpeg,.png">
        <label class="form-file-label">
            <i class="fas fa-cloud-upload-alt"></i><br>
            Klik untuk mengunggah file JPG/PNG (maks. 5MB)
        </label>
        <div class="form-file-name">
            @if($peserta['file_pas_foto'] ?? false)
                File sudah diupload sebelumnya
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
</div>

<div class="form-group">
    <label class="form-label">Unggah Surat Berbadan Sehat</label>
    <div class="form-file">
        <input type="file" name="file_surat_sehat" class="form-file-input @error('file_surat_sehat') error @enderror" 
               accept=".pdf">
        <label class="form-file-label">
            <i class="fas fa-cloud-upload-alt"></i><br>
            Klik untuk mengunggah file PDF (maks. 5MB)
        </label>
        <div class="form-file-name">
            @if($pendaftaran['file_surat_sehat'] ?? false)
                File sudah diupload sebelumnya
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
    <label class="form-label required">Unggah Surat Pernyataan Tidak Sedang mempertanggungjawabkan Penyelesaian Administrasi</label>
    <div class="form-file">
        <input type="file" name="file_surat_pernyataan_administrasi" class="form-file-input @error('file_surat_pernyataan_administrasi') error @enderror" 
               accept=".pdf">
        <label class="form-file-label">
            <i class="fas fa-cloud-upload-alt"></i><br>
            Klik untuk mengunggah file PDF (maks. 5MB)
        </label>
        <div class="form-file-name">
            @if($pendaftaran['file_surat_pernyataan_administrasi'] ?? false)
                File sudah diupload sebelumnya
            @elseif(old('file_surat_pernyataan_administrasi'))
                File sudah diupload sebelumnya
            @else
                Belum ada file dipilih
            @endif
        </div>
    </div>
    @error('file_surat_pernyataan_administrasi')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="form-group">
    <label class="form-label">Unggah Surat Keterangan bebas narkoba</label>
    <div class="form-file">
        <input type="file" name="file_surat_bebas_narkoba" class="form-file-input @error('file_surat_bebas_narkoba') error @enderror" 
               accept=".pdf">
        <label class="form-file-label">
            <i class="fas fa-cloud-upload-alt"></i><br>
            Klik untuk mengunggah file PDF (maks. 5MB)
        </label>
        <div class="form-file-name">
            @if($pendaftaran['file_surat_bebas_narkoba'] ?? false)
                File sudah diupload sebelumnya
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
</style>