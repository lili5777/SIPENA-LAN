<div class="form-section-header">
    <i class="fas fa-chalkboard-teacher"></i> Form kesediaan PKA
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label required">NIP/NRP</label>
        <input type="text" name="nip_nrp" class="form-input @error('nip_nrp') error @enderror" 
               value="{{ old('nip_nrp') }}" required>
        @error('nip_nrp')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label required">Nama lengkap gelar</label>
        <input type="text" name="nama_lengkap" class="form-input @error('nama_lengkap') error @enderror" 
               value="{{ old('nama_lengkap') }}" required>
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
            <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
            <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
        </select>
        @error('jenis_kelamin')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label required">Agama</label>
        <select name="agama" class="form-select @error('agama') error @enderror" required>
            <option value="">Pilih</option>
            <option value="Islam" {{ old('agama') == 'Islam' ? 'selected' : '' }}>Islam</option>
            <option value="Kristen" {{ old('agama') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
            <option value="Katolik" {{ old('agama') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
            <option value="Hindu" {{ old('agama') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
            <option value="Buddha" {{ old('agama') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
            <option value="Konghucu" {{ old('agama') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
        </select>
        @error('agama')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label required">Tempat lahir</label>
        <input type="text" name="tempat_lahir" class="form-input @error('tempat_lahir') error @enderror" 
               value="{{ old('tempat_lahir') }}" required>
        @error('tempat_lahir')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label required">Tanggal lahir</label>
        <input type="date" name="tanggal_lahir" class="form-input @error('tanggal_lahir') error @enderror" 
               value="{{ old('tanggal_lahir') }}" required>
        @error('tanggal_lahir')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-group">
    <label class="form-label required">Alamat rumah</label>
    <textarea name="alamat_rumah" class="form-textarea @error('alamat_rumah') error @enderror" required>{{ old('alamat_rumah') }}</textarea>
    @error('alamat_rumah')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="form-group">
    <label class="form-label required">Alamat kantor</label>
    <textarea name="alamat_kantor" class="form-textarea @error('alamat_kantor') error @enderror" required>{{ old('alamat_kantor') }}</textarea>
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
               value="{{ old('asal_instansi') }}" required>
        @error('asal_instansi')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label required">Instansi Detail/Unit Kerja</label>
        <input type="text" name="unit_kerja" class="form-input @error('unit_kerja') error @enderror" 
               value="{{ old('unit_kerja') }}" required>
        @error('unit_kerja')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label required">Provinsi</label>
        <select name="provinsi" class="form-select @error('provinsi') error @enderror" required>
            <option value="">Pilih Provinsi</option>
            <option value="">Memuat provinsi...</option>
        </select>
        @error('provinsi')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label required">Kabupaten/Kota</label>
        <select name="kabupaten" class="form-select @error('kabupaten') error @enderror" required disabled>
            <option value="">Pilih Kabupaten/Kota (Pilih Provinsi Dahulu)</option>
        </select>
        @error('kabupaten')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label required">Jabatan</label>
        <input type="text" name="jabatan" class="form-input @error('jabatan') error @enderror" 
               value="{{ old('jabatan') }}" required>
        @error('jabatan')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label required">Eselon</label>
        <select name="eselon" class="form-select @error('eselon') error @enderror" required>
            <option value="">Pilih</option>
            <option value="III" {{ old('eselon') == 'III' ? 'selected' : '' }}>III</option>
            <option value="IV" {{ old('eselon') == 'IV' ? 'selected' : '' }}>IV</option>
        </select>
        @error('eselon')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-group">
    <label class="form-label required">Unggah Scan fotokopi kelulusan/hasil seleksi calon peserta PKA / Sertifikat/Piagam Penghargaan Terbaik</label>
    <div class="form-file">
        <input type="file" name="file_surat_kelulusan_seleksi" class="form-file-input @error('file_surat_kelulusan_seleksi') error @enderror" 
               accept=".pdf" {{ old('file_surat_kelulusan_seleksi') ? '' : 'required' }}>
        <label class="form-file-label">
            <i class="fas fa-cloud-upload-alt"></i><br>
            Klik untuk mengunggah file PDF (maks. 5MB)
        </label>
        <div class="form-file-name">
            @if(old('file_surat_kelulusan_seleksi'))
                File sudah diupload sebelumnya
            @endif
        </div>
    </div>
    @error('file_surat_kelulusan_seleksi')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label required">Tahun Lulus PKP/PIM IV</label>
        <input type="number" name="tahun_lulus_pkp_pim_iv" class="form-input @error('tahun_lulus_pkp_pim_iv') error @enderror" 
               min="1900" max="2099" value="{{ old('tahun_lulus_pkp_pim_iv') }}" required>
        @error('tahun_lulus_pkp_pim_iv')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label required">Pangkat / Golongan Ruang</label>
        <select name="golongan_ruang" class="form-select @error('golongan_ruang') error @enderror" required>
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
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label required">No WA</label>
        <input type="tel" name="no_wa" class="form-input @error('no_wa') error @enderror" 
               value="{{ old('no_wa') }}" required>
        @error('no_wa')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label required">Email pribadi</label>
        <input type="email" name="email_pribadi" class="form-input @error('email_pribadi') error @enderror" 
               value="{{ old('email_pribadi') }}" required>
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
            <option value="D3" {{ old('pendidikan_terakhir') == 'D3' ? 'selected' : '' }}>D3</option>
            <option value="S1" {{ old('pendidikan_terakhir') == 'S1' ? 'selected' : '' }}>S1</option>
            <option value="S2" {{ old('pendidikan_terakhir') == 'S2' ? 'selected' : '' }}>S2</option>
            <option value="S3" {{ old('pendidikan_terakhir') == 'S3' ? 'selected' : '' }}>S3</option>
        </select>
        @error('pendidikan_terakhir')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label required">Bidang Studi Pendidikan Terakhir</label>
        <input type="text" name="bidang_studi" class="form-input @error('bidang_studi') error @enderror" 
               value="{{ old('bidang_studi') }}" required>
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
        <select name="status_perkawinan" class="form-select @error('status_perkawinan') error @enderror" required>
            <option value="">Pilih</option>
            <option value="Belum Menikah" {{ old('status_perkawinan') == 'Belum Menikah' ? 'selected' : '' }}>Belum Menikah</option>
            <option value="Menikah" {{ old('status_perkawinan') == 'Menikah' ? 'selected' : '' }}>Menikah</option>
            <option value="Duda" {{ old('status_perkawinan') == 'Duda' ? 'selected' : '' }}>Duda</option>
            <option value="Janda" {{ old('status_perkawinan') == 'Janda' ? 'selected' : '' }}>Janda</option>
        </select>
        @error('status_perkawinan')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label">Nama Istri/Suami</label>
        <input type="text" name="nama_pasangan" class="form-input @error('nama_pasangan') error @enderror" 
               value="{{ old('nama_pasangan') }}">
        @error('nama_pasangan')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label required">Apakah Saudara Merokok ?</label>
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
        <label class="form-label required">Olahraga Kegemaran/Hobi</label>
        <input type="text" name="olahraga_hobi" class="form-input @error('olahraga_hobi') error @enderror" 
               value="{{ old('olahraga_hobi') }}" required>
        @error('olahraga_hobi')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-section-header">
    <i class="fas fa-file-upload"></i> Dokumen Pendukung
</div>

<div class="form-group">
    <label class="form-label required">Foto KTP</label>
    <div class="form-file">
        <input type="file" name="file_ktp" class="form-file-input @error('file_ktp') error @enderror" 
               accept=".jpg,.jpeg,.png" {{ old('file_ktp') ? '' : 'required' }}>
        <label class="form-file-label">
            <i class="fas fa-cloud-upload-alt"></i><br>
            Klik untuk mengunggah file JPG/PNG (maks. 2MB)
        </label>
        <div class="form-file-name">
            @if(old('file_ktp'))
                File sudah diupload sebelumnya
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
               value="{{ old('nomor_sk_terakhir') }}" required>
        @error('nomor_sk_terakhir')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label required">Tanggal SK Jabatan Terakhir</label>
        <input type="date" name="tanggal_sk_jabatan" class="form-input @error('tanggal_sk_jabatan') error @enderror" 
               value="{{ old('tanggal_sk_jabatan') }}" required>
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
                   accept=".jpg,.jpeg,.png" {{ old('file_sk_jabatan') ? '' : 'required' }}>
            <label class="form-file-label">
                <i class="fas fa-cloud-upload-alt"></i><br>
                Klik untuk mengunggah file JPG/PNG (maks. 2MB)
            </label>
            <div class="form-file-name">
                @if(old('file_sk_jabatan'))
                    File sudah diupload sebelumnya
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
                   accept=".jpg,.jpeg,.png" {{ old('file_sk_pangkat') ? '' : 'required' }}>
            <label class="form-file-label">
                <i class="fas fa-cloud-upload-alt"></i><br>
                Klik untuk mengunggah file JPG/PNG (maks. 2MB)
            </label>
            <div class="form-file-name">
                @if(old('file_sk_pangkat'))
                    File sudah diupload sebelumnya
                @endif
            </div>
        </div>
        @error('file_sk_pangkat')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-group">
    <label class="form-label required">Unggah Scan Formulir Kesediaan</label>
    <div class="form-file">
        <input type="file" name="file_surat_kesediaan" class="form-file-input @error('file_surat_kesediaan') error @enderror" 
               accept=".jpg,.jpeg,.png" {{ old('file_surat_kesediaan') ? '' : 'required' }}>
        <label class="form-file-label">
            <i class="fas fa-cloud-upload-alt"></i><br>
            Klik untuk mengunggah file JPG/PNG (maks. 2MB)
        </label>
        <div class="form-file-name">
            @if(old('file_surat_kesediaan'))
                File sudah diupload sebelumnya
            @endif
        </div>
    </div>
    @error('file_surat_kesediaan')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="form-group">
    <label class="form-label required">Unggah Scan Pakta Integritas</label>
    <div class="form-file">
        <input type="file" name="file_pakta_integritas" class="form-file-input @error('file_pakta_integritas') error @enderror" 
               accept=".pdf" {{ old('file_pakta_integritas') ? '' : 'required' }}>
        <label class="form-file-label">
            <i class="fas fa-cloud-upload-alt"></i><br>
            Klik untuk mengunggah file PDF (maks. 5MB)
        </label>
        <div class="form-file-name">
            @if(old('file_pakta_integritas'))
                File sudah diupload sebelumnya
            @endif
        </div>
    </div>
    @error('file_pakta_integritas')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="form-group">
    <label class="form-label required">Unggah Scan Surat Tugas mengikuti pelatihan yang ditandatangani oleh pejabat yang berwenang</label>
    <div class="form-file">
        <input type="file" name="file_surat_tugas" class="form-file-input @error('file_surat_tugas') error @enderror" 
               accept=".pdf" {{ old('file_surat_tugas') ? '' : 'required' }}>
        <label class="form-file-label">
            <i class="fas fa-cloud-upload-alt"></i><br>
            Klik untuk mengunggah file PDF (maks. 5MB)
        </label>
        <div class="form-file-name">
            @if(old('file_surat_tugas'))
                File sudah diupload sebelumnya
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
               value="{{ old('nomor_telepon_kantor') }}">
        @error('nomor_telepon_kantor')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label">E-mail Kantor</label>
        <input type="email" name="email_kantor" class="form-input @error('email_kantor') error @enderror" 
               value="{{ old('email_kantor') }}">
        @error('email_kantor')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-group">
    <label class="form-label required">Unggah Pas Foto peserta</label>
    <div class="form-file">
        <input type="file" name="file_pas_foto" class="form-file-input @error('file_pas_foto') error @enderror" 
               accept=".jpg,.jpeg,.png" {{ old('file_pas_foto') ? '' : 'required' }}>
        <label class="form-file-label">
            <i class="fas fa-cloud-upload-alt"></i><br>
            Klik untuk mengunggah file JPG/PNG (maks. 2MB)
        </label>
        <div class="form-file-name">
            @if(old('file_pas_foto'))
                File sudah diupload sebelumnya
            @endif
        </div>
    </div>
    @error('file_pas_foto')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="form-section-header">
    <i class="fas fa-user-graduate"></i> Data Mentor
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label">Nama Mentor</label>
        <input type="text" name="nama_mentor" class="form-input @error('nama_mentor') error @enderror" 
               value="{{ old('nama_mentor') }}">
        @error('nama_mentor')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label">Jabatan Mentor</label>
        <input type="text" name="jabatan_mentor" class="form-input @error('jabatan_mentor') error @enderror" 
               value="{{ old('jabatan_mentor') }}">
        @error('jabatan_mentor')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label">Nomor Rekening Mentor</label>
        <input type="text" name="nomor_rekening_mentor" class="form-input @error('nomor_rekening_mentor') error @enderror" 
               placeholder="Bank Mandiri, 174xxxxxxxxx a.n Nanang Wijaya" value="{{ old('nomor_rekening_mentor') }}">
        @error('nomor_rekening_mentor')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="form-label">NPWP Mentor</label>
        <input type="text" name="npwp_mentor" class="form-input @error('npwp_mentor') error @enderror" 
               value="{{ old('npwp_mentor') }}">
        @error('npwp_mentor')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-group">
    <label class="form-label">Unggah Form Persetujuan Mentor</label>
    <div class="form-file">
        <input type="file" name="file_persetujuan_mentor" class="form-file-input @error('file_persetujuan_mentor') error @enderror" 
               accept=".pdf">
        <label class="form-file-label">
            <i class="fas fa-cloud-upload-alt"></i><br>
            Klik untuk mengunggah file PDF (maks. 5MB)
        </label>
        <div class="form-file-name">
            @if(old('file_persetujuan_mentor'))
                File sudah diupload sebelumnya
            @endif
        </div>
    </div>
    @error('file_persetujuan_mentor')
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
            @if(old('file_surat_sehat'))
                File sudah diupload sebelumnya
            @endif
        </div>
    </div>
    @error('file_surat_sehat')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="form-group">
    <label class="form-label">Unggah Surat Pernyataan Tidak Sedang mempertanggungjawabkan Penyelesaian Administrasi</label>
    <div class="form-file">
        <input type="file" name="file_surat_pernyataan_administrasi" class="form-file-input @error('file_surat_pernyataan_administrasi') error @enderror" 
               accept=".pdf">
        <label class="form-file-label">
            <i class="fas fa-cloud-upload-alt"></i><br>
            Klik untuk mengunggah file PDF (maks. 5MB)
        </label>
        <div class="form-file-name">
            @if(old('file_surat_pernyataan_administrasi'))
                File sudah diupload sebelumnya
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
            @if(old('file_surat_bebas_narkoba'))
                File sudah diupload sebelumnya
            @endif
        </div>
    </div>
    @error('file_surat_bebas_narkoba')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>