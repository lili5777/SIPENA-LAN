{{-- resources/views/admin/dashboard/edit.blade.php --}}
@extends('admin.partials.layout')

@section('title', 'Edit Data Peserta - LAN Pusjar SKMP')
@section('page-title', 'Edit Data Peserta')

@section('styles')
    <style>
        :root {
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #3b82f6;
            --dark-color: #1f2937;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--gray-50);
            color: var(--gray-800);
            line-height: 1.6;
        }

        /* Container */
        .edit-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Header */
        .edit-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 16px;
            padding: 2.5rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }

        .edit-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, transparent 70%);
            border-radius: 50%;
        }

        .edit-header::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .header-content {
            position: relative;
            z-index: 1;
        }

        .edit-header h1 {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            letter-spacing: -0.025em;
        }

        .edit-header p {
            opacity: 0.95;
            font-size: 1.05rem;
            margin-bottom: 1.5rem;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: white;
            text-decoration: none;
            padding: 0.625rem 1.25rem;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 600;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .back-button:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateX(-5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* Alert Messages */
        .alert-container {
            margin-bottom: 1.5rem;
        }

        .alert {
            padding: 1rem 1.25rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            border: 1px solid;
            animation: slideInDown 0.3s ease-out;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-color: #6ee7b7;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border-color: #fca5a5;
        }

        .alert-warning {
            background: #fef3c7;
            color: #92400e;
            border-color: #fcd34d;
        }

        .alert-info {
            background: #dbeafe;
            color: #1e40af;
            border-color: #93c5fd;
        }

        .alert i {
            margin-top: 0.125rem;
            font-size: 1.25rem;
        }

        .alert-content {
            flex: 1;
        }

        .alert-title {
            font-weight: 700;
            margin-bottom: 0.25rem;
            font-size: 1rem;
        }

        .alert-message {
            font-size: 0.95rem;
            opacity: 0.95;
        }

        .alert-close {
            background: none;
            border: none;
            color: inherit;
            cursor: pointer;
            padding: 0.25rem;
            opacity: 0.6;
            transition: opacity 0.2s;
        }

        .alert-close:hover {
            opacity: 1;
        }

        /* Form */
        .edit-form {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-xl);
            overflow: hidden;
        }

        /* Tabs */
        .form-tabs {
            display: flex;
            background: var(--gray-50);
            border-bottom: 2px solid var(--gray-200);
            overflow-x: auto;
            scrollbar-width: thin;
            scrollbar-color: var(--gray-400) var(--gray-100);
        }

        .form-tabs::-webkit-scrollbar {
            height: 6px;
        }

        .form-tabs::-webkit-scrollbar-track {
            background: var(--gray-100);
        }

        .form-tabs::-webkit-scrollbar-thumb {
            background: var(--gray-400);
            border-radius: 3px;
        }

        .form-tab {
            padding: 1.25rem 2rem;
            background: none;
            border: none;
            font-weight: 600;
            color: var(--gray-600);
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            min-width: 180px;
            justify-content: center;
            font-size: 0.95rem;
        }

        .form-tab i {
            font-size: 1.1rem;
        }

        .form-tab:hover:not(.active) {
            color: var(--primary-color);
            background: rgba(26, 58, 108, 0.05);
        }

        .form-tab.active {
            color: var(--primary-color);
            background: white;
        }

        .form-tab.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        }

        .form-tab.error::before {
            content: '';
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            width: 8px;
            height: 8px;
            background: var(--danger-color);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }

        /* Tab Content */
        .form-tab-content {
            display: none;
            padding: 2.5rem;
            animation: fadeInUp 0.4s ease-out;
        }

        .form-tab-content.active {
            display: block;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Section Header */
        .form-section-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--gray-200);
        }

        .form-section-header i {
            color: var(--primary-color);
            background: linear-gradient(135deg, rgba(26, 58, 108, 0.1), rgba(37, 99, 235, 0.1));
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .form-section-header h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-color);
            letter-spacing: -0.025em;
        }

        /* Form Layout */
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-row-3 {
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        /* Form Labels */
        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.625rem;
            color: var(--gray-700);
            font-size: 0.95rem;
        }

        .form-label.required::after {
            content: ' *';
            color: var(--danger-color);
            font-weight: 700;
        }

        /* Form Inputs */
        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid var(--gray-300);
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.2s ease;
            background: white;
            font-family: inherit;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(26, 58, 108, 0.1);
        }

        .form-input:disabled,
        .form-select:disabled,
        .form-textarea:disabled {
            background: var(--gray-100);
            color: var(--gray-500);
            cursor: not-allowed;
        }

        .form-input.error,
        .form-select.error,
        .form-textarea.error {
            border-color: var(--danger-color);
            background: #fef2f2;
        }

        .form-input.error:focus,
        .form-select.error:focus,
        .form-textarea.error:focus {
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
        }

        .form-input.success {
            border-color: var(--success-color);
        }

        .form-textarea {
            min-height: 120px;
            resize: vertical;
            line-height: 1.6;
        }

        .form-hint {
            display: block;
            margin-top: 0.5rem;
            font-size: 0.875rem;
            color: var(--gray-500);
        }

        .form-hint i {
            margin-right: 0.25rem;
        }

        /* Error Messages */
        .error-message {
            color: var(--danger-color);
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.375rem;
            font-weight: 500;
        }

        .error-message i {
            font-size: 0.875rem;
        }

        .text-danger {
            color: var(--danger-color);
        }

        .text-success {
            color: var(--success-color);
        }

        /* File Upload */
        .form-file {
            position: relative;
            margin-top: 0.5rem;
        }

        .form-file-input {
            position: absolute;
            width: 0.1px;
            height: 0.1px;
            opacity: 0;
            overflow: hidden;
            z-index: -1;
        }

        .form-file-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2.5rem;
            border: 2px dashed var(--gray-300);
            border-radius: 12px;
            background: var(--gray-50);
            color: var(--gray-600);
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }

        .form-file-label:hover {
            border-color: var(--primary-color);
            background: #f0f4ff;
        }

        .form-file-label i {
            font-size: 2.5rem;
            margin-bottom: 0.75rem;
            color: var(--primary-color);
        }

        .form-file-label-text {
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.25rem;
        }

        .form-file-label-hint {
            font-size: 0.875rem;
            color: var(--gray-500);
        }

        .form-file-name {
            margin-top: 1rem;
        }

        .file-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem;
            background: var(--gray-50);
            border-radius: 10px;
            border: 1px solid var(--gray-200);
        }

        .file-info i.fa-check-circle {
            color: var(--success-color);
            font-size: 1.25rem;
        }

        .file-info i.fa-file-pdf {
            color: var(--danger-color);
            font-size: 1.25rem;
        }

        .file-info i.fa-file-image {
            color: var(--info-color);
            font-size: 1.25rem;
        }

        .file-info-content {
            flex: 1;
            min-width: 0;
        }

        .file-name {
            font-weight: 600;
            color: var(--gray-700);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .file-size {
            font-size: 0.875rem;
            color: var(--gray-500);
        }

        .btn-change-file {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.375rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .btn-change-file:hover {
            background: var(--secondary-color);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-remove-file {
            background: var(--danger-color);
            color: white;
            border: none;
            padding: 0.5rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-remove-file:hover {
            background: #dc2626;
        }

        .no-file {
            color: var(--gray-400);
            font-style: italic;
            font-size: 0.9rem;
        }

        /* Mentor Container */
        #mentor-container {
            margin-top: 1.5rem;
            padding: 2rem;
            background: var(--gray-50);
            border-radius: 12px;
            border: 1px solid var(--gray-200);
        }

        /* Form Actions */
        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 2rem 2.5rem;
            background: var(--gray-50);
            border-top: 2px solid var(--gray-200);
            gap: 1rem;
            flex-wrap: wrap;
        }

        .form-actions-left {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .btn {
            padding: 0.875rem 2rem;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.625rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            font-size: 1rem;
            border: none;
        }

        .btn-cancel {
            background: white;
            color: var(--gray-700);
            border: 2px solid var(--gray-300);
        }

        .btn-cancel:hover {
            background: var(--gray-100);
            border-color: var(--gray-400);
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            box-shadow: var(--shadow-md);
        }

        .btn-submit:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        /* Loading Spinner */
        .spinner {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Progress Indicator */
        .form-progress {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: var(--gray-600);
        }

        .progress-text {
            font-weight: 600;
        }

        .progress-bar-container {
            width: 120px;
            height: 6px;
            background: var(--gray-200);
            border-radius: 3px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            transition: width 0.3s ease;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .edit-container {
                padding: 1.5rem;
            }
        }

        @media (max-width: 992px) {
            .edit-container {
                padding: 1rem;
            }

            .edit-header {
                padding: 2rem;
            }

            .edit-header h1 {
                font-size: 1.75rem;
            }

            .form-tab {
                min-width: 160px;
                padding: 1rem 1.5rem;
                font-size: 0.9rem;
            }

            .form-tab-content {
                padding: 2rem;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .form-section-header {
                flex-direction: column;
                text-align: center;
                gap: 0.75rem;
            }

            .form-section-header i {
                width: 50px;
                height: 50px;
            }

            .form-section-header h3 {
                font-size: 1.3rem;
            }
        }

        @media (max-width: 768px) {
            .edit-header h1 {
                font-size: 1.5rem;
            }

            .edit-header p {
                font-size: 0.95rem;
            }

            .form-tab {
                min-width: 140px;
                padding: 0.875rem 1rem;
                font-size: 0.875rem;
            }

            .form-tab i {
                font-size: 1rem;
            }

            .form-tab-content {
                padding: 1.5rem;
            }

            .form-actions {
                flex-direction: column;
                align-items: stretch;
                padding: 1.5rem;
            }

            .form-actions-left {
                flex-direction: column;
                width: 100%;
            }

            .btn-cancel,
            .btn-submit {
                width: 100%;
                justify-content: center;
            }

            .file-info {
                flex-wrap: wrap;
            }

            .btn-change-file {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .edit-header {
                padding: 1.5rem;
            }

            .edit-header h1 {
                font-size: 1.25rem;
            }

            .form-tabs {
                flex-wrap: nowrap;
                overflow-x: auto;
            }

            .form-tab {
                flex: 0 0 auto;
                min-width: 120px;
                padding: 0.75rem 0.5rem;
                font-size: 0.8rem;
                flex-direction: column;
                gap: 0.25rem;
            }

            .form-tab i {
                font-size: 1.25rem;
            }

            .form-file-label {
                padding: 2rem 1rem;
            }
        }

        /* Smooth Scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Focus Visible for Accessibility */
        *:focus-visible {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }

        /* Highlight error fields */
        .error-field {
            animation: pulseError 1.5s ease-in-out;
        }

        @keyframes pulseError {
            0%, 100% {
                background-color: transparent;
            }
            50% {
                background-color: rgba(239, 68, 68, 0.1);
            }
        }
    </style>
@endsection

@section('content')
    <div class="edit-container">
        <!-- Header -->
        <div class="edit-header">
            <div class="header-content">
                <h1><i class="fas fa-user-edit"></i> Edit Data Peserta</h1>
                <p>Perbarui informasi pribadi, kepegawaian, dan dokumen Anda dengan lengkap dan akurat</p>
                <a href="{{ route('dashboard') }}" class="back-button">
                    <i class="fas fa-arrow-left"></i>
                    Kembali ke Dashboard
                </a>
            </div>
        </div>

        <!-- Alert Container -->
        <div class="alert-container">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <div class="alert-content">
                        <div class="alert-title">Berhasil!</div>
                        <div class="alert-message">{{ session('success') }}</div>
                    </div>
                    <button type="button" class="alert-close" onclick="this.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <div class="alert-content">
                        <div class="alert-title">Terjadi Kesalahan!</div>
                        <div class="alert-message">{{ session('error') }}</div>
                    </div>
                    <button type="button" class="alert-close" onclick="this.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div class="alert-content">
                        <div class="alert-title">Periksa Kembali Form Anda</div>
                        <div class="alert-message">
                            Terdapat {{ $errors->count() }} kesalahan yang perlu diperbaiki
                        </div>
                    </div>
                    <button type="button" class="alert-close" onclick="this.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif
        </div>

        <!-- Form -->
        <form id="editForm" class="edit-form" method="POST" action="{{ route('admin.dashboard.update') }}"
            enctype="multipart/form-data">
            @csrf

            <!-- Tab Navigation -->
            <div class="form-tabs">
                <button type="button" class="form-tab active" data-tab="tab-personal">
                    <i class="fas fa-user"></i>
                    <span>Data Pribadi</span>
                </button>
                <button type="button" class="form-tab" data-tab="tab-employment">
                    <i class="fas fa-briefcase"></i>
                    <span>Kepegawaian</span>
                </button>
                <button type="button" class="form-tab" data-tab="tab-mentor">
                    <i class="fas fa-user-tie"></i>
                    <span>Mentor</span>
                </button>
                <button type="button" class="form-tab" data-tab="tab-documents">
                    <i class="fas fa-file-alt"></i>
                    <span>Dokumen</span>
                </button>
            </div>

            <!-- Tab 1: Data Pribadi -->
            <div id="tab-personal" class="form-tab-content active">
                <div class="form-section-header">
                    <i class="fas fa-user-circle"></i>
                    <h3>Informasi Pribadi</h3>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">NIP/NRP</label>
                        <input type="text" name="nip_nrp" class="form-input @error('nip_nrp') error @enderror"
                            value="{{ old('nip_nrp', $peserta->nip_nrp) }}" required readonly>
                        <small class="form-hint"><i class="fas fa-info-circle"></i> NIP/NRP tidak dapat diubah</small>
                        @error('nip_nrp')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-input @error('nama_lengkap') error @enderror"
                            value="{{ old('nama_lengkap', $peserta->nama_lengkap) }}" required>
                        @error('nama_lengkap')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nama Panggilan</label>
                        <input type="text" name="nama_panggilan" class="form-input @error('nama_panggilan') error @enderror"
                            value="{{ old('nama_panggilan', $peserta->nama_panggilan) }}">
                        @error('nama_panggilan')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') error @enderror" required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin', $peserta->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin', $peserta->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Agama</label>
                        <select name="agama" class="form-select @error('agama') error @enderror" required>
                            <option value="">-- Pilih Agama --</option>
                            <option value="Islam" {{ old('agama', $peserta->agama) == 'Islam' ? 'selected' : '' }}>Islam</option>
                            <option value="Kristen" {{ old('agama', $peserta->agama) == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                            <option value="Katolik" {{ old('agama', $peserta->agama) == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                            <option value="Hindu" {{ old('agama', $peserta->agama) == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                            <option value="Buddha" {{ old('agama', $peserta->agama) == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                            <option value="Konghucu" {{ old('agama', $peserta->agama) == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                        </select>
                        @error('agama')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Status Perkawinan</label>
                        <select name="status_perkawinan" id="status_perkawinan"
                            class="form-select @error('status_perkawinan') error @enderror" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="Belum Menikah" {{ old('status_perkawinan', $peserta->status_perkawinan) == 'Belum Menikah' ? 'selected' : '' }}>Belum Menikah</option>
                            <option value="Menikah" {{ old('status_perkawinan', $peserta->status_perkawinan) == 'Menikah' ? 'selected' : '' }}>Menikah</option>
                            <option value="Duda" {{ old('status_perkawinan', $peserta->status_perkawinan) == 'Duda' ? 'selected' : '' }}>Duda</option>
                            <option value="Janda" {{ old('status_perkawinan', $peserta->status_perkawinan) == 'Janda' ? 'selected' : '' }}>Janda</option>
                        </select>
                        @error('status_perkawinan')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>

                <div class="form-row" id="nama-pasangan-container" style="{{ old('status_perkawinan', $peserta->status_perkawinan) == 'Menikah' ? 'display: grid;' : 'display: none;' }}">
                        <div class="form-group">
                            <label class="form-label required">Nama Pasangan</label>
                            <input type="text" name="nama_pasangan" id="nama_pasangan"
                                class="form-input @error('nama_pasangan') error @enderror"
                                value="{{ old('nama_pasangan', $peserta->nama_pasangan) }}"
                                {{ old('status_perkawinan', $peserta->status_perkawinan) == 'Menikah' ? '' : 'disabled' }}>
                            <small class="form-hint"><i class="fas fa-info-circle"></i> Diisi hanya jika status "Menikah"</small>
                            @error('nama_pasangan')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Kolom kosong untuk menjaga layout -->
                        <div class="form-group"></div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="form-input @error('tempat_lahir') error @enderror"
                            value="{{ old('tempat_lahir', $peserta->tempat_lahir) }}" required>
                        @error('tempat_lahir')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-input @error('tanggal_lahir') error @enderror"
                            value="{{ old('tanggal_lahir', $peserta->tanggal_lahir) }}" required>
                        @error('tanggal_lahir')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Email Pribadi</label>
                        <input type="email" name="email_pribadi" class="form-input @error('email_pribadi') error @enderror"
                            value="{{ old('email_pribadi', $peserta->email_pribadi) }}" required>
                        @error('email_pribadi')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Nomor HP</label>
                        <input type="tel" name="nomor_hp" class="form-input @error('nomor_hp') error @enderror"
                            value="{{ old('nomor_hp', $peserta->nomor_hp) }}" required placeholder="08xxxxxxxxxx">
                        @error('nomor_hp')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="form-group full-width">
                    <label class="form-label required">Alamat Rumah</label>
                    <textarea name="alamat_rumah" class="form-textarea @error('alamat_rumah') error @enderror"
                        required placeholder="Masukkan alamat lengkap">{{ old('alamat_rumah', $peserta->alamat_rumah) }}</textarea>
                    @error('alamat_rumah')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Olahraga & Hobi</label>
                        <input type="text" name="olahraga_hobi" class="form-input @error('olahraga_hobi') error @enderror"
                            value="{{ old('olahraga_hobi', $peserta->olahraga_hobi) }}" placeholder="Contoh: Futsal, Membaca">
                        @error('olahraga_hobi')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Status Perokok</label>
                        <select name="perokok" class="form-select @error('perokok') error @enderror" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="Ya" {{ old('perokok', $peserta->perokok) == 'Ya' ? 'selected' : '' }}>Ya</option>
                            <option value="Tidak" {{ old('perokok', $peserta->perokok) == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                        </select>
                        @error('perokok')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="form-row form-row-3">
                    <div class="form-group">
                        <label class="form-label">Ukuran Kaos</label>
                        <select name="ukuran_kaos" class="form-select @error('ukuran_kaos') error @enderror">
                            <option value="">-- Pilih Ukuran --</option>
                            @foreach(['S', 'M', 'L', 'XL', 'XXL', 'XXXL'] as $size)
                                <option value="{{ $size }}" {{ old('ukuran_kaos', $peserta->ukuran_kaos) == $size ? 'selected' : '' }}>{{ $size }}</option>
                            @endforeach
                        </select>
                        @error('ukuran_kaos')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ukuran Celana</label>
                        <select name="ukuran_celana" class="form-select @error('ukuran_celana') error @enderror">
                            <option value="">-- Pilih Ukuran --</option>
                            @foreach(['S', 'M', 'L', 'XL', 'XXL', 'XXXL'] as $size)
                                <option value="{{ $size }}" {{ old('ukuran_celana', $peserta->ukuran_celana) == $size ? 'selected' : '' }}>{{ $size }}</option>
                            @endforeach
                        </select>
                        @error('ukuran_celana')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ukuran Training</label>
                        <select name="ukuran_training" class="form-select @error('ukuran_training') error @enderror">
                            <option value="">-- Pilih Ukuran --</option>
                            @foreach(['S', 'M', 'L', 'XL', 'XXL', 'XXXL'] as $size)
                                <option value="{{ $size }}" {{ old('ukuran_training', $peserta->ukuran_training) == $size ? 'selected' : '' }}>{{ $size }}</option>
                            @endforeach
                        </select>
                        @error('ukuran_training')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="form-section-header">
                    <i class="fas fa-graduation-cap"></i>
                    <h3>Pendidikan</h3>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Pendidikan Terakhir</label>
                        <select name="pendidikan_terakhir" class="form-select @error('pendidikan_terakhir') error @enderror" required>
                            <option value="">-- Pilih Pendidikan --</option>
                            @foreach(['SD', 'SMP', 'SMU', 'D3', 'D4', 'S1', 'S2', 'S3'] as $edu)
                                <option value="{{ $edu }}" {{ old('pendidikan_terakhir', $peserta->pendidikan_terakhir) == $edu ? 'selected' : '' }}>{{ $edu }}</option>
                            @endforeach
                        </select>
                        @error('pendidikan_terakhir')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Bidang Studi</label>
                        <input type="text" name="bidang_studi" class="form-input @error('bidang_studi') error @enderror"
                            value="{{ old('bidang_studi', $peserta->bidang_studi) }}" placeholder="Contoh: Ilmu Komputer">
                        @error('bidang_studi')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Bidang Keahlian</label>
                        <input type="text" name="bidang_keahlian" class="form-input @error('bidang_keahlian') error @enderror"
                            value="{{ old('bidang_keahlian', $peserta->bidang_keahlian) }}" placeholder="Contoh: Data Science">
                        @error('bidang_keahlian')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kondisi Peserta</label>
                        <textarea name="kondisi_peserta" class="form-textarea @error('kondisi_peserta') error @enderror" 
                            placeholder="Catatan khusus (alergi, kondisi kesehatan, dll)">{{ old('kondisi_peserta', $peserta->kondisi_peserta) }}</textarea>
                        @error('kondisi_peserta')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Tab 2: Data Kepegawaian -->
            <div id="tab-employment" class="form-tab-content">
                <div class="form-section-header">
                    <i class="fas fa-building"></i>
                    <h3>Data Kepegawaian</h3>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Asal Instansi</label>
                        <input type="text" name="asal_instansi" class="form-input @error('asal_instansi') error @enderror"
                            value="{{ old('asal_instansi', $kepegawaian->asal_instansi ?? '') }}" required>
                        @error('asal_instansi')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Unit Kerja/Detail Instansi</label>
                        <input type="text" name="unit_kerja" class="form-input @error('unit_kerja') error @enderror"
                            value="{{ old('unit_kerja', $kepegawaian->unit_kerja ?? '') }}">
                        @error('unit_kerja')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Provinsi</label>
                        <select name="id_provinsi" id="id_provinsi" class="form-select @error('id_provinsi') error @enderror" required>
                            <option value="">-- Pilih Provinsi --</option>
                            @foreach($provinsiList as $provinsi)
                                <option value="{{ $provinsi->id }}" {{ old('id_provinsi', $kepegawaian->id_provinsi ?? '') == $provinsi->id ? 'selected' : '' }}>
                                    {{ $provinsi->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_provinsi')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                    <label class="form-label">Kabupaten/Kota</label>
                    <select name="id_kabupaten_kota" id="id_kabupaten_kota" 
                            class="form-select @error('id_kabupaten_kota') error @enderror" 
                            {{ !$kepegawaian?->id_provinsi ? 'disabled' : '' }}>
                        <option value="">-- Pilih Kabupaten/Kota --</option>
                        @if($kepegawaian && $kepegawaian->id_kabupaten_kota)
                            @php
                                $currentKabupaten = $kabupatenList->firstWhere('id', $kepegawaian->id_kabupaten_kota);
                            @endphp
                            @if($currentKabupaten)
                                <option value="{{ $currentKabupaten->id }}" selected>
                                    {{ $currentKabupaten->name }}
                                </option>
                            @endif
                        @endif
                    </select>
                    @error('id_kabupaten_kota')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                </div>

                <div class="form-group full-width">
                    <label class="form-label required">Alamat Kantor</label>
                    <textarea name="alamat_kantor" class="form-textarea @error('alamat_kantor') error @enderror"
                        required placeholder="Masukkan alamat kantor lengkap">{{ old('alamat_kantor', $kepegawaian->alamat_kantor ?? '') }}</textarea>
                    @error('alamat_kantor')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nomor Telepon Kantor</label>
                        <input type="tel" name="nomor_telepon_kantor" class="form-input @error('nomor_telepon_kantor') error @enderror"
                            value="{{ old('nomor_telepon_kantor', $kepegawaian->nomor_telepon_kantor ?? '') }}" placeholder="021xxxxxxxx">
                        @error('nomor_telepon_kantor')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email Kantor</label>
                        <input type="email" name="email_kantor" class="form-input @error('email_kantor') error @enderror"
                            value="{{ old('email_kantor', $kepegawaian->email_kantor ?? '') }}" placeholder="nama@instansi.go.id">
                        @error('email_kantor')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Jabatan</label>
                        <input type="text" name="jabatan" class="form-input @error('jabatan') error @enderror"
                            value="{{ old('jabatan', $kepegawaian->jabatan ?? '') }}" required>
                        @error('jabatan')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    @if (!($jenisPelatihanData->kode_pelatihan == "LATSAR"))
                    <div class="form-group">
                        <label class="form-label">Eselon</label>
                        <input type="text" name="eselon" class="form-input @error('eselon') error @enderror"
                            value="{{ old('eselon', $kepegawaian->eselon ?? '') }}" placeholder="Contoh: III/a">
                        @error('eselon')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    @endif

                    
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Golongan Ruang</label>
                        <select name="golongan_ruang" id="golongan_ruang" class="form-select @error('golongan_ruang') error @enderror" required>
                            <option value="">-- Pilih Golongan Ruang --</option>
                            <!-- Golongan II -->
                            <option value="II/a" {{ old('golongan_ruang', $kepegawaian->golongan_ruang ?? '') == 'II/a' ? 'selected' : '' }}>II/a</option>
                            <option value="II/b" {{ old('golongan_ruang', $kepegawaian->golongan_ruang ?? '') == 'II/b' ? 'selected' : '' }}>II/b</option>
                            <option value="II/c" {{ old('golongan_ruang', $kepegawaian->golongan_ruang ?? '') == 'II/c' ? 'selected' : '' }}>II/c</option>
                            <option value="II/d" {{ old('golongan_ruang', $kepegawaian->golongan_ruang ?? '') == 'II/d' ? 'selected' : '' }}>II/d</option>
                            <!-- Golongan III -->
                            <option value="III/a" {{ old('golongan_ruang', $kepegawaian->golongan_ruang ?? '') == 'III/a' ? 'selected' : '' }}>III/a</option>
                            <option value="III/b" {{ old('golongan_ruang', $kepegawaian->golongan_ruang ?? '') == 'III/b' ? 'selected' : '' }}>III/b</option>
                            <option value="III/c" {{ old('golongan_ruang', $kepegawaian->golongan_ruang ?? '') == 'III/c' ? 'selected' : '' }}>III/c</option>
                            <option value="III/d" {{ old('golongan_ruang', $kepegawaian->golongan_ruang ?? '') == 'III/d' ? 'selected' : '' }}>III/d</option>
                            <!-- Golongan IV -->
                            <option value="IV/a" {{ old('golongan_ruang', $kepegawaian->golongan_ruang ?? '') == 'IV/a' ? 'selected' : '' }}>IV/a</option>
                            <option value="IV/b" {{ old('golongan_ruang', $kepegawaian->golongan_ruang ?? '') == 'IV/b' ? 'selected' : '' }}>IV/b</option>
                            <option value="IV/c" {{ old('golongan_ruang', $kepegawaian->golongan_ruang ?? '') == 'IV/c' ? 'selected' : '' }}>IV/c</option>
                            <option value="IV/d" {{ old('golongan_ruang', $kepegawaian->golongan_ruang ?? '') == 'IV/d' ? 'selected' : '' }}>IV/d</option>
                        </select>
                        @error('golongan_ruang')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        <small class="form-hint"><i class="fas fa-info-circle"></i> Contoh: III/a ditulis sebagai IIIa</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Pangkat</label>
                        <input type="text" name="pangkat" id="pangkat" 
                            class="form-input @error('pangkat') error @enderror"
                            value="{{ old('pangkat', $kepegawaian->pangkat ?? '') }}" 
                            readonly
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

                @if (!($jenisPelatihanData->kode_pelatihan == "PKN_TK_II"))
                    <div class="form-section-header">
                        <i class="fas fa-file-contract"></i>
                        <h3>Data SK</h3>
                    </div>

                    @if (!($jenisPelatihanData->kode_pelatihan == "PKA" || $jenisPelatihanData->kode_pelatihan == "PKP"))
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Nomor SK CPNS</label>
                            <input type="text" name="nomor_sk_cpns" class="form-input @error('nomor_sk_cpns') error @enderror"
                                value="{{ old('nomor_sk_cpns', $kepegawaian->nomor_sk_cpns ?? '') }}">
                            @error('nomor_sk_cpns')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Tanggal SK CPNS</label>
                            <input type="date" name="tanggal_sk_cpns" class="form-input @error('tanggal_sk_cpns') error @enderror"
                                value="{{ old('tanggal_sk_cpns', $kepegawaian->tanggal_sk_cpns ?? '') }}">
                            @error('tanggal_sk_cpns')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    @endif

                    <div class="form-row">
                        @if (!($jenisPelatihanData->kode_pelatihan == "LATSAR"))
                        <div class="form-group">
                            <label class="form-label">Nomor SK Terakhir</label>
                            <input type="text" name="nomor_sk_terakhir" class="form-input @error('nomor_sk_terakhir') error @enderror"
                                value="{{ old('nomor_sk_terakhir', $kepegawaian->nomor_sk_terakhir ?? '') }}">
                            @error('nomor_sk_terakhir')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        @endif

                        @if (!($jenisPelatihanData->kode_pelatihan == "LATSAR"))
                        <div class="form-group">
                            <label class="form-label">Tanggal SK Jabatan</label>
                            <input type="date" name="tanggal_sk_jabatan" class="form-input @error('tanggal_sk_jabatan') error @enderror"
                                value="{{ old('tanggal_sk_jabatan', $kepegawaian->tanggal_sk_jabatan ?? '') }}">
                            @error('tanggal_sk_jabatan')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        @endif
                    </div>

                    @if (!($jenisPelatihanData->kode_pelatihan == "LATSAR"))
                    <div class="form-group">
                        <label class="form-label">Tahun Lulus PKP/PIM IV</label>
                        <input type="number" name="tahun_lulus_pkp_pim_iv" class="form-input @error('tahun_lulus_pkp_pim_iv') error @enderror"
                            value="{{ old('tahun_lulus_pkp_pim_iv', $kepegawaian->tahun_lulus_pkp_pim_iv ?? '') }}"
                            min="1900" max="{{ date('Y') }}" placeholder="{{ date('Y') }}">
                        @error('tahun_lulus_pkp_pim_iv')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    @endif
                @endif
            </div>

            <!-- Tab 3: Data Mentor -->
            <div id="tab-mentor" class="form-tab-content">
                <div class="form-section-header">
                    <i class="fas fa-user-tie"></i>
                    <h3>Data Mentor</h3>
                </div>

                @if($pendaftaranTerbaru)
                    <div class="form-group">
                        <label class="form-label required">Sudah Ada Penunjukan Mentor?</label>
                        <select name="sudah_ada_mentor" id="sudah_ada_mentor" class="form-select @error('sudah_ada_mentor') error @enderror" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="Ya" {{ old('sudah_ada_mentor', $pendaftaranTerbaru->id_mentor ? 'Ya' : 'Tidak') == 'Ya' ? 'selected' : '' }}>Ya</option>
                            <option value="Tidak" {{ old('sudah_ada_mentor', $pendaftaranTerbaru->id_mentor ? 'Ya' : 'Tidak') == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                        </select>
                        @error('sudah_ada_mentor')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div id="mentor-container" style="{{ old('sudah_ada_mentor', $pendaftaranTerbaru->id_mentor ? 'Ya' : 'Tidak') == 'Ya' ? 'display: block;' : 'display: none;' }}">
                        <div class="form-group">
                            <label class="form-label required">Pilih Menu Mentor</label>
                            <select name="mentor_mode" id="mentor_mode" class="form-select @error('mentor_mode') error @enderror">
                                <option value="">-- Pilih Menu --</option>
                                @if(count($mentorList) > 0)
                                <option value="pilih" {{ old('mentor_mode', $pendaftaranTerbaru->id_mentor ? 'pilih' : 'tambah') == 'pilih' ? 'selected' : '' }}>Pilih dari Daftar Mentor</option>
                                @endif
                                <option value="tambah" {{ old('mentor_mode', !$pendaftaranTerbaru->id_mentor ? 'tambah' : '') == 'tambah' ? 'selected' : '' }}>Tambah Mentor Baru</option>
                            </select>
                            @error('mentor_mode')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Pilih dari daftar mentor -->
                        @if(count($mentorList) > 0)
                        <div id="select-mentor-form" style="{{ old('mentor_mode', $pendaftaranTerbaru->id_mentor ? 'pilih' : '') == 'pilih' ? 'display: block;' : 'display: none;' }}">
                            <div class="form-group">
                                <label class="form-label required">Pilih Mentor</label>
                                <select name="id_mentor" id="id_mentor" class="form-select @error('id_mentor') error @enderror">
                                    <option value="">-- Pilih Mentor --</option>
                                    @foreach($mentorList as $mentor)
                                        <option value="{{ $mentor->id }}" 
                                            data-nama="{{ $mentor->nama_mentor }}"
                                            data-jabatan="{{ $mentor->jabatan_mentor }}"
                                            data-rekening="{{ $mentor->nomor_rekening }}" 
                                            data-npwp="{{ $mentor->npwp_mentor }}" 
                                            {{ old('id_mentor', $pendaftaranTerbaru->id_mentor) == $mentor->id ? 'selected' : '' }}>
                                            {{ $mentor->nama_mentor }} - {{ $mentor->jabatan_mentor }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_mentor')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Nama Mentor</label>
                                    <input type="text" name="nama_mentor" id="nama_mentor_select" class="form-input" readonly
                                        value="{{ old('nama_mentor', $pendaftaranTerbaru->mentor->nama_mentor ?? '') }}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Jabatan Mentor</label>
                                    <input type="text" name="jabatan_mentor" id="jabatan_mentor_select" class="form-input" readonly
                                        value="{{ old('jabatan_mentor', $pendaftaranTerbaru->mentor->jabatan_mentor ?? '') }}">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Nomor Rekening Mentor</label>
                                    <input type="text" name="nomor_rekening_mentor" id="nomor_rekening_mentor_select" class="form-input" readonly
                                        value="{{ old('nomor_rekening_mentor', $pendaftaranTerbaru->mentor->nomor_rekening ?? '') }}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">NPWP Mentor</label>
                                    <input type="text" name="npwp_mentor" id="npwp_mentor_select" class="form-input" readonly
                                        value="{{ old('npwp_mentor', $pendaftaranTerbaru->mentor->npwp_mentor ?? '') }}">
                                </div>
                            </div>
                        </div>
                        @else
                        <input type="hidden" name="mentor_mode" value="tambah">
                        @endif

                        <!-- Tambah mentor baru -->
                        <div id="add-mentor-form" style="{{ old('mentor_mode', !$pendaftaranTerbaru->id_mentor ? 'tambah' : '') == 'tambah' ? 'display: block;' : 'display: none;' }}">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                <div class="alert-content">
                                    <div class="alert-message">Silakan lengkapi data mentor baru dengan informasi yang akurat</div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label required">Nama Mentor</label>
                                    <input type="text" name="nama_mentor_baru" id="nama_mentor_baru"
                                        class="form-input @error('nama_mentor_baru') error @enderror"
                                        value="{{ old('nama_mentor_baru', $pendaftaranTerbaru->mentor->nama_mentor ?? '') }}">
                                    @error('nama_mentor_baru')
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label required">Jabatan Mentor</label>
                                    <input type="text" name="jabatan_mentor_baru" id="jabatan_mentor_baru"
                                        class="form-input @error('jabatan_mentor_baru') error @enderror"
                                        value="{{ old('jabatan_mentor_baru', $pendaftaranTerbaru->mentor->jabatan_mentor ?? '') }}">
                                    @error('jabatan_mentor_baru')
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Nomor Rekening Mentor</label>
                                    <input type="text" name="nomor_rekening_mentor_baru" id="nomor_rekening_mentor_baru"
                                        class="form-input @error('nomor_rekening_mentor_baru') error @enderror"
                                        value="{{ old('nomor_rekening_mentor_baru', $pendaftaranTerbaru->mentor->nomor_rekening ?? '') }}">
                                    @error('nomor_rekening_mentor_baru')
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">NPWP Mentor</label>
                                    <input type="text" name="npwp_mentor_baru" id="npwp_mentor_baru"
                                        class="form-input @error('npwp_mentor_baru') error @enderror"
                                        value="{{ old('npwp_mentor_baru', $pendaftaranTerbaru->mentor->npwp_mentor ?? '') }}">
                                    @error('npwp_mentor_baru')
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <div class="alert-content">
                            <div class="alert-message">Anda belum memiliki pendaftaran aktif untuk mengatur mentor.</div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Tab 4: Dokumen -->
            <div id="tab-documents" class="form-tab-content">
                <div class="form-section-header">
                    <i class="fas fa-file-upload"></i>
                    <h3>Dokumen Pendukung</h3>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <div class="alert-content">
                        <div class="alert-title">Perhatian</div>
                        <div class="alert-message">
                            Upload file hanya jika ingin mengganti file yang sudah ada. 
                            Format yang diterima: PDF, JPG, JPEG, PNG (maks. 1MB).
                        </div>
                    </div>
                </div>

                <div class="form-section-header">
                    <i class="fas fa-id-card"></i>
                    <h3>Dokumen Pribadi</h3>
                </div>

                <!-- KTP -->
                <div class="form-group">
                    <label class="form-label">KTP</label>
                    <div class="form-file">
                        <input type="file" name="file_ktp" id="file_ktp" class="form-file-input" accept=".pdf,.jpg,.jpeg,.png">
                        <label for="file_ktp" class="form-file-label">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <div class="form-file-label-text">Klik untuk mengunggah file KTP</div>
                            <div class="form-file-label-hint">PDF, JPG, JPEG, PNG (Maks. 1MB)</div>
                        </label>
                        <div class="form-file-name">
                            @if($peserta->file_ktp)
                                <div class="file-info">
                                    <i class="fas fa-file-image"></i>
                                    <div class="file-info-content">
                                        <div class="file-name">{{ basename($peserta->file_ktp) }}</div>
                                        <div class="file-size">File tersedia</div>
                                    </div>
                                    <button type="button" class="btn-change-file" data-target="file_ktp">
                                        <i class="fas fa-exchange-alt"></i> Ganti
                                    </button>
                                </div>
                            @else
                                <span class="no-file">Belum ada file diupload</span>
                            @endif
                        </div>
                    </div>
                    @error('file_ktp')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Pas Foto -->
                <div class="form-group">
                    <label class="form-label">Pas Foto</label>
                    <div class="form-file">
                        <input type="file" name="file_pas_foto" id="file_pas_foto" class="form-file-input" accept=".jpg,.jpeg,.png">
                        <label for="file_pas_foto" class="form-file-label">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <div class="form-file-label-text">Klik untuk mengunggah pas foto</div>
                            <div class="form-file-label-hint">JPG, JPEG, PNG (Maks. 1MB)</div>
                        </label>
                        <div class="form-file-name">
                            @if($peserta->file_pas_foto)
                                <div class="file-info">
                                    <i class="fas fa-file-image"></i>
                                    <div class="file-info-content">
                                        <div class="file-name">{{ basename($peserta->file_pas_foto) }}</div>
                                        <div class="file-size">File tersedia</div>
                                    </div>
                                    <button type="button" class="btn-change-file" data-target="file_pas_foto">
                                        <i class="fas fa-exchange-alt"></i> Ganti
                                    </button>
                                </div>
                            @else
                                <span class="no-file">Belum ada file diupload</span>
                            @endif
                        </div>
                    </div>
                    @error('file_pas_foto')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                @if($kepegawaian)
                    <div class="form-section-header">
                        <i class="fas fa-file-contract"></i>
                        <h3>Dokumen Kepegawaian</h3>
                    </div>

                    @php
                        $kepegawaianDocs = [
                            ['name' => 'file_sk_jabatan', 'label' => 'SK Jabatan'],
                            ['name' => 'file_sk_pangkat', 'label' => 'SK Pangkat'],
                            ['name' => 'file_sk_cpns', 'label' => 'SK CPNS'],
                            ['name' => 'file_spmt', 'label' => 'SPMT'],
                            ['name' => 'file_skp', 'label' => 'SKP'],
                        ];

                        if (isset($jenisPelatihanData->kode_pelatihan) && $jenisPelatihanData->kode_pelatihan == "LATSAR") {
                                $kepegawaianDocs = array_filter($kepegawaianDocs, function($doc) {
                                    return !in_array($doc['name'], ['file_sk_jabatan', 'file_sk_pangkat']);
                                });
                            }

                        if (isset($jenisPelatihanData->kode_pelatihan) && ($jenisPelatihanData->kode_pelatihan == "PKN_TK_II" || $jenisPelatihanData->kode_pelatihan == "PKA" || $jenisPelatihanData->kode_pelatihan == "PKP")) {
                                $kepegawaianDocs = array_filter($kepegawaianDocs, function($doc) {
                                    return !in_array($doc['name'], ['file_sk_cpns', 'file_spmt', 'file_skp']);
                                });
                            }
                    @endphp

                    @foreach($kepegawaianDocs as $doc)
                        <div class="form-group">
                            <label class="form-label">{{ $doc['label'] }}</label>
                            <div class="form-file">
                                <input type="file" name="{{ $doc['name'] }}" id="{{ $doc['name'] }}" class="form-file-input" accept=".pdf">
                                <label for="{{ $doc['name'] }}" class="form-file-label">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <div class="form-file-label-text">Klik untuk mengunggah {{ $doc['label'] }}</div>
                                    <div class="form-file-label-hint">PDF (Maks. 1MB)</div>
                                </label>
                                <div class="form-file-name">
                                    @if($kepegawaian->{$doc['name']})
                                        <div class="file-info">
                                            <i class="fas fa-file-pdf"></i>
                                            <div class="file-info-content">
                                                <div class="file-name">{{ basename($kepegawaian->{$doc['name']}) }}</div>
                                                <div class="file-size">File tersedia</div>
                                            </div>
                                            <button type="button" class="btn-change-file" data-target="{{ $doc['name'] }}">
                                                <i class="fas fa-exchange-alt"></i> Ganti
                                            </button>
                                        </div>
                                    @else
                                        <span class="no-file">Belum ada file diupload</span>
                                    @endif
                                </div>
                            </div>
                            @error($doc['name'])
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    @endforeach
                @endif

                @if($pendaftaranTerbaru)
                    <div class="form-section-header">
                        <i class="fas fa-file-alt"></i>
                        <h3>Dokumen Pendaftaran</h3>
                    </div>

                    @php
                        $pendaftaranDocs = [
                            ['name' => 'file_surat_tugas', 'label' => 'Surat Tugas'],
                            ['name' => 'file_surat_kesediaan', 'label' => 'Surat Kesediaan'],
                            ['name' => 'file_pakta_integritas', 'label' => 'Pakta Integritas'],
                            ['name' => 'file_surat_sehat', 'label' => 'Surat Sehat'],
                            ['name' => 'file_surat_komitmen', 'label' => 'Surat Komitmen'],
                            ['name' => 'file_surat_kelulusan_seleksi', 'label' => 'Surat Kelulusan Seleksi'],
                            ['name' => 'file_surat_bebas_narkoba', 'label' => 'Surat Bebas Narkoba'],
                            ['name' => 'file_surat_pernyataan_administrasi', 'label' => 'Surat Pernyataan Administrasi'],
                            ['name' => 'file_persetujuan_mentor', 'label' => 'Surat Persetujuan Mentor'],
                        ];

                        if (isset($jenisPelatihanData->kode_pelatihan) && $jenisPelatihanData->kode_pelatihan == "LATSAR") {
                                $pendaftaranDocs = array_filter($pendaftaranDocs, function($doc) {
                                    return !in_array($doc['name'], ['file_pakta_integritas','file_surat_komitmen','file_surat_kelulusan_selesksi','file_surat_bebas_narkoba','file_surat_pernyataan_administrasi','file_persetujuan_mentor']);
                                });
                            }

                        if (isset($jenisPelatihanData->kode_pelatihan) && $jenisPelatihanData->kode_pelatihan == "PKN_TK_II") {
                                $pendaftaranDocs = array_filter($pendaftaranDocs, function($doc) {
                                    return !in_array($doc['name'], ['file_surat_pernyataan_administrasi','file_persetujuan_mentor','file_surat_kesediaan']);
                                });
                            }
                        
                        if ((isset($jenisPelatihanData->kode_pelatihan) && $jenisPelatihanData->kode_pelatihan == "PKA") || $jenisPelatihanData->kode_pelatihan == "PKP") {
                                $pendaftaranDocs = array_filter($pendaftaranDocs, function($doc) {
                                    return !in_array($doc['name'], ['file_surat_komitmen','file_sertifikat_penghargaan','file_surat_pernyataan_administrasi']);
                                });
                            }
                    @endphp

                    @foreach($pendaftaranDocs as $doc)
                        <div class="form-group">
                            <label class="form-label">{{ $doc['label'] }}</label>
                            <div class="form-file">
                                <input type="file" name="{{ $doc['name'] }}" id="{{ $doc['name'] }}" class="form-file-input" accept=".pdf">
                                <label for="{{ $doc['name'] }}" class="form-file-label">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <div class="form-file-label-text">Klik untuk mengunggah {{ $doc['label'] }}</div>
                                    <div class="form-file-label-hint">PDF (Maks. 1MB)</div>
                                </label>
                                <div class="form-file-name">
                                    @if($pendaftaranTerbaru->{$doc['name']})
                                        <div class="file-info">
                                            <i class="fas fa-file-pdf"></i>
                                            <div class="file-info-content">
                                                <div class="file-name">{{ basename($pendaftaranTerbaru->{$doc['name']}) }}</div>
                                                <div class="file-size">File tersedia</div>
                                            </div>
                                            <button type="button" class="btn-change-file" data-target="{{ $doc['name'] }}">
                                                <i class="fas fa-exchange-alt"></i> Ganti
                                            </button>
                                        </div>
                                    @else
                                        <span class="no-file">Belum ada file diupload</span>
                                    @endif
                                </div>
                            </div>
                            @error($doc['name'])
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <div class="form-actions-left">
                    <a href="{{ route('dashboard') }}" class="btn btn-cancel">
                        <i class="fas fa-times"></i>
                        Batal
                    </a>
                    <div class="form-progress">
                        <span class="progress-text">Progress:</span>
                        <div class="progress-bar-container">
                            <div class="progress-bar" id="formProgress" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-submit" id="submitBtn">
                    <i class="fas fa-save"></i>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        // ===== KONFIGURASI AWAL =====
        window.oldValues = @json(old(), JSON_PRETTY_PRINT);
        window.allKabupatenData = @json($kabupatenList);
        const validationFailed = @json($errors->any() ? true : false);

        // Jika ada validation errors, set old values
        if (validationFailed && window.oldValues.id_provinsi) {
            setTimeout(() => {
                if (provinceSelect) {
                    provinceSelect.value = window.oldValues.id_provinsi;
                    provinceSelect.dispatchEvent(new Event('change'));
                }
            }, 100);
        }
        document.addEventListener('DOMContentLoaded', function () {
            // ===== TAB NAVIGATION =====
            const formTabs = document.querySelectorAll('.form-tab');
            const tabContents = document.querySelectorAll('.form-tab-content');

            formTabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    const tabId = tab.getAttribute('data-tab');

                    formTabs.forEach(t => t.classList.remove('active'));
                    tabContents.forEach(content => content.classList.remove('active'));

                    tab.classList.add('active');
                    const activeContent = document.getElementById(tabId);
                    if (activeContent) {
                        activeContent.classList.add('active');
                    }

                    // Smooth scroll to top of form
                    document.querySelector('.edit-form').scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
            });

            // ===== FILE UPLOAD HANDLING =====
            const fileInputs = document.querySelectorAll('.form-file-input');

            fileInputs.forEach(input => {
                input.addEventListener('change', function () {
                    const fileInfo = this.closest('.form-file').querySelector('.form-file-name');
                    
                    if (this.files && this.files[0]) {
                        const file = this.files[0];
                        const fileSize = (file.size / 1024).toFixed(2); // KB
                        const fileName = file.name;
                        const fileExt = fileName.split('.').pop().toLowerCase();
                        
                        let fileIcon = 'fa-file';
                        if (['jpg', 'jpeg', 'png'].includes(fileExt)) {
                            fileIcon = 'fa-file-image';
                        } else if (fileExt === 'pdf') {
                            fileIcon = 'fa-file-pdf';
                        }

                        fileInfo.innerHTML = `
                            <div class="file-info">
                                <i class="fas ${fileIcon}"></i>
                                <div class="file-info-content">
                                    <div class="file-name">${fileName}</div>
                                    <div class="file-size">${fileSize} KB</div>
                                </div>
                                <button type="button" class="btn-change-file" data-target="${this.name}">
                                    <i class="fas fa-exchange-alt"></i> Ganti
                                </button>
                            </div>
                        `;

                        const changeBtn = fileInfo.querySelector('.btn-change-file');
                        if (changeBtn) {
                            changeBtn.addEventListener('click', () => {
                                this.click();
                            });
                        }

                        // Validate file size
                        if (file.size > 1024 * 1024) { // 1MB
                            alert(`File ${fileName} terlalu besar. Maksimal ukuran file adalah 1MB.`);
                            this.value = '';
                            fileInfo.innerHTML = '<span class="no-file">Belum ada file diupload</span>';
                        }
                    }
                });
            });

            // Change file button functionality
            document.querySelectorAll('.btn-change-file').forEach(btn => {
                btn.addEventListener('click', function () {
                    const target = this.getAttribute('data-target');
                    const fileInput = document.querySelector(`input[name="${target}"]`);
                    if (fileInput) {
                        fileInput.click();
                    }
                });
            });


            // ===== STATUS PERKAWINAN & NAMA PASANGAN HANDLING =====
            const statusPerkawinanSelect = document.getElementById('status_perkawinan');
            const namaPasanganContainer = document.getElementById('nama-pasangan-container');
            const namaPasanganInput = document.getElementById('nama_pasangan');

            // Function untuk mengatur tampilan field nama pasangan
            function toggleNamaPasanganField() {
                if (!statusPerkawinanSelect || !namaPasanganContainer || !namaPasanganInput) return;
                
                const status = statusPerkawinanSelect.value;
                
                if (status === 'Menikah') {
                    // Tampilkan field nama pasangan
                    namaPasanganContainer.style.display = 'grid';
                    namaPasanganInput.disabled = false;
                    namaPasanganInput.setAttribute('required', 'required');
                } else {
                    // Sembunyikan field nama pasangan
                    namaPasanganContainer.style.display = 'none';
                    namaPasanganInput.disabled = true;
                    namaPasanganInput.removeAttribute('required');
                    namaPasanganInput.value = ''; // Kosongkan nilai jika status bukan "Menikah"
                }
            }

            // Event listener untuk status perkawinan
            if (statusPerkawinanSelect) {
                statusPerkawinanSelect.addEventListener('change', toggleNamaPasanganField);
                
                // Initialize on page load
                document.addEventListener('DOMContentLoaded', function() {
                    toggleNamaPasanganField();
                    
                    // Handle old values from validation
                    if (window.oldValues && window.oldValues.status_perkawinan) {
                        statusPerkawinanSelect.value = window.oldValues.status_perkawinan;
                        toggleNamaPasanganField();
                        
                        // Set nama pasangan dari old values jika ada
                        if (window.oldValues.nama_pasangan && namaPasanganInput) {
                            namaPasanganInput.value = window.oldValues.nama_pasangan;
                        }
                    }
                });
            }

            // ===== GOLONGAN RUANG & PANGKAT AUTO-FILL =====
            const golonganRuangSelect = document.getElementById('golongan_ruang');
            const pangkatInput = document.getElementById('pangkat');
            const pangkatDescription = document.getElementById('pangkat_description');
            const pangkatDescText = document.getElementById('pangkat_desc_text');

            // Mapping golongan ruang ke pangkat dan deskripsi
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

            // Function untuk update pangkat berdasarkan golongan
            function updatePangkatFromGolongan() {
                if (!golonganRuangSelect || !pangkatInput) return;
                
                const selectedGolongan = golonganRuangSelect.value;
                
                if (selectedGolongan && pangkatMapping[selectedGolongan]) {
                    pangkatInput.value = pangkatMapping[selectedGolongan].pangkat;
                    pangkatDescText.textContent = pangkatMapping[selectedGolongan].description;
                    pangkatDescription.style.display = 'block';
                } else {
                    pangkatInput.value = '';
                    pangkatDescription.style.display = 'none';
                }
            }

            // Event listener untuk golongan ruang
            if (golonganRuangSelect) {
                golonganRuangSelect.addEventListener('change', updatePangkatFromGolongan);
                
                // Initialize on page load
                document.addEventListener('DOMContentLoaded', function() {
                    // Set initial value for golongan ruang from database
                    const currentGolongan = @json($kepegawaian->golongan_ruang ?? '');
                    
                    // Check if current golongan is in old format (like "III/a") and convert
                    if (currentGolongan) {
                        let normalizedGolongan = currentGolongan;
                        
                        // Convert format "III/a" to "IIIa"
                        if (currentGolongan.includes('/')) {
                            normalizedGolongan = currentGolongan.replace('/', '');
                        }
                        
                        // Check if the normalized value exists in our options
                        if (pangkatMapping[normalizedGolongan]) {
                            golonganRuangSelect.value = normalizedGolongan;
                        }
                    }
                    
                    // Set old value jika ada dari validation
                    if (window.oldValues && window.oldValues.golongan_ruang) {
                        let oldGolongan = window.oldValues.golongan_ruang;
                        
                        // Convert format if needed
                        if (oldGolongan.includes('/')) {
                            oldGolongan = oldGolongan.replace('/', '');
                        }
                        
                        golonganRuangSelect.value = oldGolongan;
                    }
                    
                    // Update pangkat berdasarkan golongan yang dipilih
                    updatePangkatFromGolongan();
                });
            }

          

            // Optional: Reset to auto-fill if input is cleared
            pangkatInput.addEventListener('blur', function() {
                if (!this.value.trim() && golonganRuangSelect.value) {
                    this.setAttribute('readonly', true);
                    this.placeholder = 'Akan terisi otomatis berdasarkan golongan ruang';
                    updatePangkatFromGolongan();
                }
            });

           // ===== PROVINSI & KABUPATEN HANDLERS =====
const provinceSelect = document.getElementById('id_provinsi');
const citySelect = document.getElementById('id_kabupaten_kota');

if (provinceSelect && citySelect) {
    provinceSelect.addEventListener('change', function () {
        const provinceId = this.value;

        if (!provinceId) {
            citySelect.innerHTML = '<option value="">Pilih Kabupaten/Kota (Pilih Provinsi Dahulu)</option>';
            citySelect.disabled = true;
            return;
        }

        citySelect.innerHTML = '<option value="">Memuat kabupaten/kota...</option>';
        citySelect.disabled = true;

        try {
            // Filter kabupaten dari data yang sudah ada (dikirim dari controller)
            // Asumsi: window.allKabupatenData sudah tersedia di global scope
            const allKabupaten = window.allKabupatenData || [];
            const filteredKabupaten = allKabupaten.filter(kab => kab.province_id == provinceId);

            citySelect.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
            citySelect.disabled = false;

            if (filteredKabupaten.length > 0) {
                filteredKabupaten.forEach(kabupaten => {
                    const option = document.createElement('option');
                    option.value = kabupaten.id;
                    option.textContent = kabupaten.name;
                    citySelect.appendChild(option);
                });

                // Set nilai untuk edit mode
                const currentKabupatenId = @json($kepegawaian && $kepegawaian->id_kabupaten_kota ? $kepegawaian->id_kabupaten_kota : null);
                if (currentKabupatenId) {
                    citySelect.value = currentKabupatenId;
                }

                // Set old value jika ada dari validation
                if (window.oldValues && window.oldValues.id_kabupaten_kota) {
                    citySelect.value = window.oldValues.id_kabupaten_kota;
                }
            } else {
                citySelect.innerHTML = '<option value="">Tidak ada data kabupaten</option>';
            }
        } catch (error) {
            console.error('Error filtering kabupaten:', error);
            citySelect.innerHTML = '<option value="">Error loading data</option>';
            citySelect.disabled = false;
        }
    });

    // Inisialisasi data kabupaten di window object
    window.allKabupatenData = @json($kabupatenList);

    // Trigger change jika sudah ada nilai (edit mode)
    if (provinceSelect.value) {
        setTimeout(() => {
            provinceSelect.dispatchEvent(new Event('change'));
        }, 300);
    }
}
            
            // ===== MENTOR HANDLING =====
            const sudahAdaMentor = document.getElementById('sudah_ada_mentor');
            const mentorContainer = document.getElementById('mentor-container');
            const mentorMode = document.getElementById('mentor_mode');
            const selectMentorForm = document.getElementById('select-mentor-form');
            const addMentorForm = document.getElementById('add-mentor-form');
            
            
            const mentorSelect = document.getElementById('id_mentor');

            if (sudahAdaMentor && mentorContainer) {
                sudahAdaMentor.addEventListener('change', function () {
                    if (this.value === 'Ya') {
                        mentorContainer.style.display = 'block';
                    } else {
                        mentorContainer.style.display = 'none';
                        if (mentorMode) mentorMode.value = '';
                        if (selectMentorForm) selectMentorForm.style.display = 'none';
                        if (addMentorForm) addMentorForm.style.display = 'none';
                    }
                });
            }

            if (mentorMode && selectMentorForm && addMentorForm) {
                mentorMode.addEventListener('change', function () {
                    if (this.value === 'pilih') {
                        selectMentorForm.style.display = 'block';
                        addMentorForm.style.display = 'none';
                    } else if (this.value === 'tambah') {
                        selectMentorForm.style.display = 'none';
                        addMentorForm.style.display = 'block';
                    } else {
                        selectMentorForm.style.display = 'none';
                        addMentorForm.style.display = 'none';
                    }
                });
            }

            if (mentorSelect) {
                mentorSelect.addEventListener('change', function () {
                    const selectedOption = this.options[this.selectedIndex];

                    if (selectedOption.dataset.nama) {
                        document.getElementById('nama_mentor_select').value = selectedOption.dataset.nama;
                        document.getElementById('jabatan_mentor_select').value = selectedOption.dataset.jabatan;
                        document.getElementById('nomor_rekening_mentor_select').value = selectedOption.dataset.rekening || '';
                        document.getElementById('npwp_mentor_select').value = selectedOption.dataset.npwp || '';
                    } else {
                        document.getElementById('nama_mentor_select').value = '';
                        document.getElementById('jabatan_mentor_select').value = '';
                        document.getElementById('nomor_rekening_mentor_select').value = '';
                        document.getElementById('npwp_mentor_select').value = '';
                    }
                });

                if (mentorSelect.value) {
                    mentorSelect.dispatchEvent(new Event('change'));
                }
            }

            // ===== FORM VALIDATION =====
            const editForm = document.getElementById('editForm');
            const submitBtn = document.getElementById('submitBtn');

            // Real-time validation
            const formInputs = editForm.querySelectorAll('input, select, textarea');

            formInputs.forEach(input => {
                input.addEventListener('blur', function () {
                    validateField(this);
                });

                input.addEventListener('input', function () {
                    if (this.classList.contains('error')) {
                        validateField(this);
                    }
                    updateProgress();
                });
            });

            function validateField(field) {
                if (field.hasAttribute('required') && !field.value.trim()) {
                    field.classList.add('error');
                    return false;
                } else {
                    field.classList.remove('error');
                }

                if (field.type === 'email' && field.value) {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(field.value)) {
                        field.classList.add('error');
                        return false;
                    } else {
                        field.classList.remove('error');
                    }
                }

                if (field.name === 'nomor_hp' && field.value) {
                    const phoneRegex = /^[0-9+\-\s()]{10,20}$/;
                    if (!phoneRegex.test(field.value)) {
                        field.classList.add('error');
                        return false;
                    } else {
                        field.classList.remove('error');
                    }
                }

                return true;
            }

            // Progress calculation
            function updateProgress() {
                const requiredFields = editForm.querySelectorAll('[required]');
                let filledCount = 0;

                requiredFields.forEach(field => {
                    if (field.value && field.value.trim()) {
                        filledCount++;
                    }
                });

                const progress = (filledCount / requiredFields.length) * 100;
                document.getElementById('formProgress').style.width = progress + '%';
            }

            updateProgress();

            // Mark tabs with errors
            function markErrorTabs() {
                formTabs.forEach(tab => tab.classList.remove('error'));

                const errorFields = editForm.querySelectorAll('.error');
                errorFields.forEach(field => {
                    const tabContent = field.closest('.form-tab-content');
                    if (tabContent) {
                        const tabId = tabContent.id;
                        const tabButton = document.querySelector(`.form-tab[data-tab="${tabId}"]`);
                        if (tabButton) {
                            tabButton.classList.add('error');
                        }
                    }
                });
            }

            // Function to scroll to first error field
            function scrollToFirstError() {
                const firstError = editForm.querySelector('.error');
                if (firstError) {
                    // Find the tab containing the error
                    const tabContent = firstError.closest('.form-tab-content');
                    if (tabContent) {
                        const tabId = tabContent.id;
                        const tabButton = document.querySelector(`.form-tab[data-tab="${tabId}"]`);
                        if (tabButton) {
                            tabButton.click();
                        }
                    }

                    // Scroll to error field with animation
                    setTimeout(() => {
                        firstError.classList.add('error-field');
                        firstError.scrollIntoView({ 
                            behavior: 'smooth', 
                            block: 'center',
                            inline: 'nearest'
                        });
                        
                        // Focus the field
                        if (firstError.tagName === 'INPUT' || firstError.tagName === 'SELECT' || firstError.tagName === 'TEXTAREA') {
                            firstError.focus();
                        }
                        
                        // Remove highlight after 3 seconds
                        setTimeout(() => {
                            firstError.classList.remove('error-field');
                        }, 3000);
                    }, 300);
                }
            }

            // Check for errors on page load and scroll to them
            if (editForm.querySelector('.error')) {
                setTimeout(() => {
                    scrollToFirstError();
                    markErrorTabs();
                }, 500);
            }

            // Form submission
            editForm.addEventListener('submit', function (e) {
                const requiredFields = editForm.querySelectorAll('[required]');
                let isValid = true;

                requiredFields.forEach(field => {
                    if (!validateField(field)) {
                        isValid = false;
                    }
                });

                markErrorTabs();

                if (!isValid) {
                    e.preventDefault();
                    scrollToFirstError();
                    alert('Harap lengkapi semua field yang wajib diisi dengan benar.');
                    return;
                }

                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner"></span> Menyimpan...';
            });

            // Auto-dismiss alerts
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-10px)';
                    setTimeout(() => alert.remove(), 500);
                });
            }, 5000);

            // Prevent accidental page leave
            let formChanged = false;
            formInputs.forEach(input => {
                input.addEventListener('change', () => {
                    formChanged = true;
                });
            });

            window.addEventListener('beforeunload', (e) => {
                if (formChanged && !editForm.classList.contains('submitted')) {
                    e.preventDefault();
                    e.returnValue = '';
                }
            });

            editForm.addEventListener('submit', () => {
                editForm.classList.add('submitted');
            });
        });
    </script>
@endsection