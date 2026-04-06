{{-- admin/penguji/_password_fields.blade.php --}}
{{-- Variables: $isEdit (bool), $hasUser (bool) --}}

<div class="card border border-primary-subtle rounded-3 mb-4" style="background:#f8fbff;">
    <div class="card-body pt-4">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">
                        <i class="fas fa-lock me-1 text-primary"></i>
                        @if($hasUser)
                            Password Baru
                            <small class="text-muted fw-normal">(kosongkan jika tidak diubah)</small>
                        @else
                            Password <span class="text-danger">*</span>
                        @endif
                    </label>
                    <div class="input-group">
                        <input type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               id="password" name="password"
                               placeholder="{{ $hasUser ? 'Kosongkan jika tidak diubah' : 'Minimal 5 karakter' }}"
                               autocomplete="new-password">
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword" tabindex="-1"
                                data-bs-toggle="tooltip" title="Tampilkan/Sembunyikan">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Strength meter --}}
                    <div class="mt-2" id="strengthWrapper" style="display:none;">
                        <div class="progress" style="height:5px;">
                            <div class="progress-bar" id="strengthBar" role="progressbar" style="width:0%;transition:width .3s;"></div>
                        </div>
                        <small id="strengthLabel" class="text-muted"></small>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label fw-semibold">
                        <i class="fas fa-lock me-1 text-primary"></i>
                        @if($hasUser) Konfirmasi Password Baru @else Konfirmasi Password <span class="text-danger">*</span> @endif
                    </label>
                    <input type="password"
                           class="form-control"
                           id="password_confirmation" name="password_confirmation"
                           placeholder="{{ $hasUser ? 'Ulangi password baru' : 'Ulangi password' }}"
                           autocomplete="new-password">
                    <div id="matchFeedback" class="mt-1" style="font-size:.85rem;display:none;"></div>
                </div>
            </div>
        </div>

        {{-- Generate Password Button --}}
        <div class="mt-1">
            <button type="button" id="generatePassword"
                    class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-2">
                <i class="fas fa-magic"></i>
                Generate Password Acak
            </button>
            <small class="text-muted ms-2">
                <i class="fas fa-info-circle me-1"></i>Otomatis isi password & konfirmasi dengan password acak yang kuat
            </small>
        </div>
    </div>
</div>