@extends('admin.partials.layout')

@section('title', 'Dashboard - LAN Pusjar SKMP')
@section('page-title', 'Dashboard')

@section('styles')
<style>
    :root {
        --primary-color: #1a3a6c;
        --secondary-color: #2c5282;
        --dark-color: #1e293b;
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --info-color: #3b82f6;
    }

    .welcome-card {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: white;
        border-radius: 20px;
        padding: 2.5rem 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(26, 58, 108, 0.2);
        position: relative;
        overflow: hidden;
        animation: fadeIn 0.6s ease-out;
    }

    .welcome-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .welcome-card::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
    }

    .welcome-header {
        position: relative;
        z-index: 1;
    }

    .welcome-card h2 {
        font-size: 1.9rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        letter-spacing: -0.5px;
    }

    .welcome-card p {
        opacity: 0.95;
        margin-bottom: 0.25rem;
        font-size: 1rem;
    }

    .welcome-stats {
        display: flex;
        gap: 2rem;
        margin-top: 1.5rem;
        flex-wrap: wrap;
        position: relative;
        z-index: 1;
    }

    .welcome-stat {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .welcome-stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .welcome-stat-content h5 {
        font-size: 1.25rem;
        font-weight: 700;
        margin: 0;
    }

    .welcome-stat-content small {
        opacity: 0.8;
        font-size: 0.85rem;
    }

    /* Data Peserta Styles */
    .data-peserta-section {
        margin-top: 2rem;
        animation: fadeIn 0.8s ease-out;
    }

    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #e2e8f0;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark-color);
    }

    .section-title i {
        color: var(--primary-color);
        background: rgba(26, 58, 108, 0.1);
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .section-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .btn-edit {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border: none;
        padding: 0.625rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        font-size: 0.95rem;
    }

    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(26, 58, 108, 0.2);
        color: white;
        text-decoration: none;
    }

    .btn-refresh {
        background: #f8fafc;
        color: var(--dark-color);
        border: 1px solid #e2e8f0;
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-refresh:hover {
        background: #f1f5f9;
        border-color: var(--primary-color);
    }

    .tab-container {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .tabs-nav {
        display: flex;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        overflow-x: auto;
        scrollbar-width: none;
    }

    .tabs-nav::-webkit-scrollbar {
        display: none;
    }

    .tab-button {
        padding: 1rem 2rem;
        background: none;
        border: none;
        font-weight: 600;
        color: #64748b;
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
        position: relative;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        min-width: 140px;
        justify-content: center;
    }

    .tab-button:hover {
        color: var(--primary-color);
        background: rgba(26, 58, 108, 0.05);
    }

    .tab-button.active {
        color: var(--primary-color);
        background: white;
    }

    .tab-button.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: var(--primary-color);
    }

    .tab-content {
        display: none;
        padding: 2rem;
        animation: fadeInUp 0.5s ease-out;
    }

    .tab-content.active {
        display: block;
    }

    .data-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .data-card {
        background: #f8fafc;
        border-radius: 12px;
        padding: 1.5rem;
        border-left: 4px solid var(--primary-color);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
    }

    .data-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }

    .data-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .data-card-title {
        font-weight: 600;
        color: var(--dark-color);
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .data-card-title i {
        color: var(--primary-color);
    }

    .edit-card-btn {
        background: rgba(26, 58, 108, 0.1);
        color: var(--primary-color);
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.875rem;
    }

    .edit-card-btn:hover {
        background: var(--primary-color);
        color: white;
        transform: rotate(15deg);
    }

    .data-item {
        margin-bottom: 1.25rem;
        display: flex;
        flex-direction: column;
    }

    .data-item:last-child {
        margin-bottom: 0;
    }

    .data-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.35rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .data-label i {
        font-size: 0.75rem;
        opacity: 0.7;
    }

    .data-value {
        font-size: 1rem;
        font-weight: 500;
        color: #1e293b;
        word-break: break-word;
        line-height: 1.5;
    }

    .data-value.empty {
        color: #94a3b8;
        font-style: italic;
    }

    /* File List Styles */
    .file-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .file-section {
        margin-bottom: 2rem;
    }

    .file-section h5 {
        color: var(--dark-color);
        font-weight: 600;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .file-section h5 i {
        color: var(--primary-color);
    }

    .file-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
        background: white;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .file-item:hover {
        border-color: var(--primary-color);
        background: #f8fafc;
        transform: translateX(5px);
    }

    .file-item.empty {
        background: #f8fafc;
        border-style: dashed;
        border-color: #cbd5e1;
    }

    .file-item.empty:hover {
        border-color: #94a3b8;
        background: #f1f5f9;
        transform: none;
    }

    .file-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex: 1;
    }

    .file-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .file-icon.has-file {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    }

    .file-icon.no-file {
        background: linear-gradient(135deg, #94a3b8, #cbd5e1);
    }

    .file-details {
        flex: 1;
        min-width: 0;
    }

    .file-name {
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 0.25rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .file-name.empty {
        color: #94a3b8;
        font-style: italic;
    }

    .file-size {
        font-size: 0.85rem;
        color: #64748b;
    }

    .file-size.empty {
        font-style: italic;
        color: #cbd5e1;
    }

    .file-action {
        display: flex;
        gap: 0.5rem;
        flex-shrink: 0;
    }

    .btn-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f1f5f9;
        color: #64748b;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-icon:hover {
        background: var(--primary-color);
        color: white;
        transform: scale(1.1);
    }

    .btn-icon.view {
        background: #dbeafe;
        color: var(--primary-color);
    }

    .btn-icon.view:hover {
        background: var(--primary-color);
        color: white;
    }

    .btn-icon.download {
        background: #dcfce7;
        color: var(--success-color);
    }

    .btn-icon.download:hover {
        background: var(--success-color);
        color: white;
    }

    .btn-icon.upload {
        background: #fef3c7;
        color: var(--warning-color);
    }

    .btn-icon.upload:hover {
        background: var(--warning-color);
        color: white;
    }

    .no-data {
        text-align: center;
        padding: 3rem;
        color: #94a3b8;
        background: #f8fafc;
        border-radius: 12px;
        border: 2px dashed #e2e8f0;
    }

    .no-data i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .no-data h4 {
        color: #64748b;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }

    .no-data p {
        color: #94a3b8;
        margin-bottom: 0;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.4rem 0.9rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .status-badge i {
        font-size: 0.6rem;
    }

    .status-badge.active {
        background: #d1fae5;
        color: #065f46;
    }

    .status-badge.inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-badge.pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-badge.success {
        background: #d1fae5;
        color: #065f46;
    }

    /* Table Styles */
    .table-responsive {
        overflow-x: auto;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        margin-top: 1rem;
    }

    .table {
        width: 100%;
        background: white;
        margin: 0;
    }

    .table thead th {
        background: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
        padding: 1rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    .table tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: background-color 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: #f8fafc;
    }

    .table tbody td {
        padding: 1rem;
        color: #475569;
        vertical-align: middle;
        white-space: nowrap;
    }

    /* Document Viewer Modal */
    .document-viewer {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        padding: 2rem;
    }

    .document-viewer.active {
        display: flex;
        animation: fadeIn 0.3s ease;
    }

    .document-container {
        background: white;
        border-radius: 12px;
        width: 100%;
        height: 100%;
        max-width: 1000px;
        max-height: 90vh;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .document-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }

    .document-title {
        font-weight: 600;
        color: var(--dark-color);
        font-size: 1.1rem;
    }

    .document-close {
        background: none;
        border: none;
        color: #64748b;
        font-size: 1.5rem;
        cursor: pointer;
        transition: color 0.3s ease;
        width: 36px;
        height: 36px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .document-close:hover {
        color: var(--danger-color);
        background: #fee2e2;
    }

    .document-content {
        flex: 1;
        padding: 1.5rem;
        overflow-y: auto;
        max-height: calc(90vh - 70px);
    }

    .document-preview {
        width: 100%;
        height: 100%;
        min-height: 400px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        overflow: hidden;
    }

    .document-preview iframe,
    .document-preview img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .document-notice {
        text-align: center;
        padding: 3rem;
        color: #94a3b8;
    }

    .document-notice i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    /* Animations */
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

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    /* Responsive Styles */
    @media (max-width: 1200px) {
        .data-grid {
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        }
        
        .tab-button {
            min-width: 130px;
            padding: 1rem 1.5rem;
        }
    }

    @media (max-width: 992px) {
        .welcome-card {
            padding: 2rem 1.5rem;
        }

        .welcome-card h2 {
            font-size: 1.6rem;
        }

        .welcome-stats {
            gap: 1rem;
        }

        .welcome-stat {
            flex: 1 1 calc(50% - 1rem);
        }

        .tab-content {
            padding: 1.5rem;
        }

        .section-title {
            font-size: 1.3rem;
        }

        .data-grid {
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }

        .document-container {
            width: 95%;
        }
    }

    @media (max-width: 768px) {
        .welcome-card {
            padding: 1.75rem 1.25rem;
        }

        .welcome-card h2 {
            font-size: 1.4rem;
        }

        .welcome-stats {
            flex-direction: column;
            gap: 1rem;
        }

        .welcome-stat {
            flex: none;
            width: 100%;
        }

        .tabs-nav {
            flex-wrap: nowrap;
            overflow-x: auto;
        }

        .tab-button {
            min-width: 120px;
            padding: 0.875rem 1rem;
            font-size: 0.9rem;
        }

        .section-title {
            font-size: 1.2rem;
        }

        .section-header {
            flex-direction: column;
            align-items: stretch;
            gap: 1rem;
        }

        .section-actions {
            width: 100%;
            justify-content: flex-start;
        }

        .btn-edit, .btn-refresh {
            flex: 1;
            justify-content: center;
            min-width: 140px;
        }

        .data-grid {
            grid-template-columns: 1fr;
        }

        .file-item {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .file-info {
            flex-direction: column;
            text-align: center;
            width: 100%;
        }

        .file-action {
            width: 100%;
            justify-content: center;
        }

        .table-responsive {
            margin: 0 -1rem;
            width: calc(100% + 2rem);
            border-left: none;
            border-right: none;
            border-radius: 0;
        }

        .table {
            min-width: 600px;
        }

        .document-viewer {
            padding: 1rem;
        }

        .document-container {
            width: 100%;
            max-height: 85vh;
        }

        .document-content {
            max-height: calc(85vh - 70px);
        }
    }

    @media (max-width: 576px) {
        .welcome-card h2 {
            font-size: 1.3rem;
        }

        .welcome-card p {
            font-size: 0.95rem;
        }

        .tab-content {
            padding: 1rem;
        }

        .data-card {
            padding: 1.25rem 1rem;
        }

        .section-title {
            font-size: 1.1rem;
        }

        .section-title i {
            width: 35px;
            height: 35px;
        }

        .btn-edit, .btn-refresh {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }

        .tab-button {
            min-width: 110px;
            padding: 0.75rem 0.875rem;
            font-size: 0.85rem;
        }

        .document-header {
            padding: 0.75rem 1rem;
        }

        .document-title {
            font-size: 1rem;
        }

        .document-content {
            padding: 1rem;
        }
    }

    @media (max-width: 380px) {
        .welcome-card {
            padding: 1.5rem 1rem;
        }

        .welcome-card h2 {
            font-size: 1.2rem;
        }

        .tab-button {
            min-width: 100px;
            padding: 0.75rem;
            font-size: 0.8rem;
        }

        .btn-edit, .btn-refresh {
            min-width: 120px;
        }

        .section-actions {
            flex-direction: column;
        }

        .btn-edit, .btn-refresh {
            width: 100%;
        }
    }
</style>
@endsection

@section('content')
    <!-- Welcome Card -->
    <div class="welcome-card">
        <div class="welcome-header">
            <h2>Selamat Datang, {{ auth()->user()->name }}! 👋</h2>
            <p>Sistem Manajemen Pembelajaran LAN Pusjar SKMP</p>
            <p><i class="fas fa-calendar-alt me-2"></i>Menyediakan platform terpadu untuk pengelolaan pembelajaran</p>
        </div>
        <div class="welcome-stats">
            <div class="welcome-stat">
                <div class="welcome-stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="welcome-stat-content">
                    <h5>1,248</h5>
                    <small>Total Peserta</small>
                </div>
            </div>
            <div class="welcome-stat">
                <div class="welcome-stat-icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="welcome-stat-content">
                    <h5>48</h5>
                    <small>Kelas Aktif</small>
                </div>
            </div>
            <div class="welcome-stat">
                <div class="welcome-stat-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="welcome-stat-content">
                    <h5>96%</h5>
                    <small>Tingkat Kelulusan</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Peserta Section -->
    @if ($peserta)
    <div class="data-peserta-section">
        <div class="section-header">
            <div class="section-title">
                <i class="fas fa-id-card"></i>
                <span>Data Peserta</span>
            </div>
            <div class="section-actions">
                <a href="{{ route('admin.dashboard.edit') }}" class="btn-edit" title="Edit Data Peserta">
                    <i class="fas fa-edit"></i>
                    Edit Data
                </a>
                <button class="btn-refresh" onclick="window.location.reload()">
                    <i class="fas fa-redo"></i>
                    Refresh
                </button>
                <div class="status-badge {{ $peserta->status_aktif ? 'active' : 'inactive' }}">
                    <i class="fas fa-circle"></i>
                    {{ $peserta->status_aktif ? 'Aktif' : 'Tidak Aktif' }}
                </div>
            </div>
        </div>

        <div class="tab-container">
            <!-- Tab Navigation -->
            <div class="tabs-nav">
                <button class="tab-button active" data-tab="tab-data-pribadi">
                    <i class="fas fa-user"></i>
                    Data Pribadi
                </button>
                <button class="tab-button" data-tab="tab-kepegawaian">
                    <i class="fas fa-briefcase"></i>
                    Data Kepegawaian
                </button>
                {{-- <button class="tab-button" data-tab="tab-pelatihan">
                    <i class="fas fa-chalkboard-teacher"></i>
                    Data Pelatihan
                </button> --}}
                <button class="tab-button" data-tab="tab-mentor">
                    <i class="fas fa-user-tie"></i>
                    Data Mentor
                </button>
                <button class="tab-button" data-tab="tab-dokumen">
                    <i class="fas fa-file-alt"></i>
                    Dokumen
                </button>
            </div>

            <!-- Tab 1: Data Pribadi -->
            <div id="tab-data-pribadi" class="tab-content active">
                <div class="data-grid">
                    <div class="data-card">
                        <div class="data-card-header">
                            <div class="data-card-title">
                                <i class="fas fa-id-card"></i>
                                Informasi Personal
                            </div>
                            
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-hashtag"></i>
                                NIP/NRP
                            </span>
                            <span class="data-value">{{ $peserta->nip_nrp ?? 'Belum diisi' }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-user"></i>
                                Nama Lengkap
                            </span>
                            <span class="data-value">{{ $peserta->nama_lengkap ?? 'Belum diisi' }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-user-circle"></i>
                                Nama Panggilan
                            </span>
                            <span class="data-value">{{ $peserta->nama_panggilan ?? 'Belum diisi' }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-venus-mars"></i>
                                Jenis Kelamin
                            </span>
                            <span class="data-value">{{ $peserta->jenis_kelamin ?? 'Belum diisi' }}</span>
                        </div>
                    </div>

                    <div class="data-card">
                        <div class="data-card-header">
                            <div class="data-card-title">
                                <i class="fas fa-info-circle"></i>
                                Data Kelahiran & Kontak
                            </div>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-mosque"></i>
                                Agama
                            </span>
                            <span class="data-value">{{ $peserta->agama ?? 'Belum diisi' }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-map-marker-alt"></i>
                                Tempat Lahir
                            </span>
                            <span class="data-value">{{ $peserta->tempat_lahir ?? 'Belum diisi' }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-birthday-cake"></i>
                                Tanggal Lahir
                            </span>
                            <span class="data-value">
                                {{ $peserta->tanggal_lahir ? \Carbon\Carbon::parse($peserta->tanggal_lahir)->format('d F Y') : 'Belum diisi' }}
                            </span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-ring"></i>
                                Status Perkawinan
                            </span>
                            <span class="data-value">{{ $peserta->status_perkawinan ?? 'Belum diisi' }}</span>
                        </div>
                    </div>

                    <div class="data-card">
                        <div class="data-card-header">
                            <div class="data-card-title">
                                <i class="fas fa-address-card"></i>
                                Kontak & Alamat
                            </div>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-envelope"></i>
                                Email Pribadi
                            </span>
                            <span class="data-value">{{ $peserta->email_pribadi ?? 'Belum diisi' }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-phone"></i>
                                Nomor HP
                            </span>
                            <span class="data-value">{{ $peserta->nomor_hp ?? 'Belum diisi' }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-home"></i>
                                Alamat Rumah
                            </span>
                            <span class="data-value">{{ $peserta->alamat_rumah ?? 'Belum diisi' }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-futbol"></i>
                                Olahraga & Hobi
                            </span>
                            <span class="data-value">{{ $peserta->olahraga_hobi ?? 'Belum diisi' }}</span>
                        </div>
                    </div>

                    <div class="data-card">
                        <div class="data-card-header">
                            <div class="data-card-title">
                                <i class="fas fa-graduation-cap"></i>
                                Pendidikan & Ukuran
                            </div>
                            
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-university"></i>
                                Pendidikan Terakhir
                            </span>
                            <span class="data-value">{{ $peserta->pendidikan_terakhir ?? 'Belum diisi' }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-book"></i>
                                Bidang Studi
                            </span>
                            <span class="data-value">{{ $peserta->bidang_studi ?? 'Belum diisi' }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-tools"></i>
                                Bidang Keahlian
                            </span>
                            <span class="data-value">{{ $peserta->bidang_keahlian ?? 'Belum diisi' }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-tshirt"></i>
                                Ukuran Kaos
                            </span>
                            <span class="data-value">{{ $peserta->ukuran_kaos ?? 'Belum diisi' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Data Kepegawaian -->
            <div id="tab-kepegawaian" class="tab-content">
                @if ($kepegawaian)
                <div class="data-grid">
                    <div class="data-card">
                        <div class="data-card-header">
                            <div class="data-card-title">
                                <i class="fas fa-building"></i>
                                Instansi & Unit Kerja
                            </div>
                            
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-landmark"></i>
                                Asal Instansi
                            </span>
                            <span class="data-value">{{ $kepegawaian->asal_instansi ?? 'Belum diisi' }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-sitemap"></i>
                                Unit Kerja
                            </span>
                            <span class="data-value">{{ $kepegawaian->unit_kerja ?? 'Belum diisi' }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-map"></i>
                                Provinsi
                            </span>
                            <span class="data-value">{{ $kepegawaian->provinsi->name ?? 'Belum diisi' }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-map-marked-alt"></i>
                                Kabupaten/Kota
                            </span>
                            <span class="data-value">{{ $kepegawaian->kabupaten->name ?? 'Belum diisi' }}</span>
                        </div>
                    </div>

                    <div class="data-card">
                        <div class="data-card-header">
                            <div class="data-card-title">
                                <i class="fas fa-user-tie"></i>
                                Jabatan & Pangkat
                            </div>
                            
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-briefcase"></i>
                                Jabatan
                            </span>
                            <span class="data-value">{{ $kepegawaian->jabatan ?? 'Belum diisi' }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-layer-group"></i>
                                Eselon
                            </span>
                            <span class="data-value">{{ $kepegawaian->eselon ?? 'Belum diisi' }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-medal"></i>
                                Pangkat
                            </span>
                            <span class="data-value">{{ $kepegawaian->pangkat ?? 'Belum diisi' }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-signal"></i>
                                Golongan Ruang
                            </span>
                            <span class="data-value">{{ $kepegawaian->golongan_ruang ?? 'Belum diisi' }}</span>
                        </div>
                    </div>

                    <div class="data-card">
                        <div class="data-card-header">
                            <div class="data-card-title">
                                <i class="fas fa-address-book"></i>
                                Kontak Kantor & SK
                            </div>
                            
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-envelope-open"></i>
                                Email Kantor
                            </span>
                            <span class="data-value">{{ $kepegawaian->email_kantor ?? 'Belum diisi' }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-phone-alt"></i>
                                Nomor Telepon Kantor
                            </span>
                            <span class="data-value">{{ $kepegawaian->nomor_telepon_kantor ?? 'Belum diisi' }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-file-signature"></i>
                                Nomor SK CPNS
                            </span>
                            <span class="data-value">{{ $kepegawaian->nomor_sk_cpns ?? 'Belum diisi' }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-file-contract"></i>
                                Nomor SK Terakhir
                            </span>
                            <span class="data-value">{{ $kepegawaian->nomor_sk_terakhir ?? 'Belum diisi' }}</span>
                        </div>
                    </div>

                    <div class="data-card">
                        <div class="data-card-header">
                            <div class="data-card-title">
                                <i class="fas fa-calendar-alt"></i>
                                Tanggal & Alamat Kantor
                            </div>
                            
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-calendar-check"></i>
                                Tanggal SK Jabatan
                            </span>
                            <span class="data-value">
                                {{ $kepegawaian->tanggal_sk_jabatan ? \Carbon\Carbon::parse($kepegawaian->tanggal_sk_jabatan)->format('d F Y') : 'Belum diisi' }}
                            </span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-calendar-day"></i>
                                Tanggal SK CPNS
                            </span>
                            <span class="data-value">
                                {{ $kepegawaian->tanggal_sk_cpns ? \Carbon\Carbon::parse($kepegawaian->tanggal_sk_cpns)->format('d F Y') : 'Belum diisi' }}
                            </span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-map-pin"></i>
                                Alamat Kantor
                            </span>
                            <span class="data-value">{{ $kepegawaian->alamat_kantor ?? 'Belum diisi' }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-award"></i>
                                Tahun Lulus PKP/PIM IV
                            </span>
                            <span class="data-value">{{ $kepegawaian->tahun_lulus_pkp_pim_iv ?? 'Belum diisi' }}</span>
                        </div>
                    </div>
                </div>
                @else
                <div class="no-data">
                    <i class="fas fa-briefcase"></i>
                    <h4>Data Kepegawaian Belum Tersedia</h4>
                    <p>Silakan lengkapi data kepegawaian Anda</p>
                    <a href="#" class="btn-edit mt-3">
                        <i class="fas fa-plus-circle"></i>
                        Tambah Data Kepegawaian
                    </a>
                </div>
                @endif
            </div>

            <!-- Tab 3: Data Pelatihan -->
            {{-- <div id="tab-pelatihan" class="tab-content">
                @if ($pendaftaranTerbaru)
                <div class="data-grid">
                    <!-- Data Jenis Pelatihan -->
                    <div class="data-card">
                        <div class="data-card-header">
                            <div class="data-card-title">
                                <i class="fas fa-chalkboard-teacher"></i>
                                Jenis Pelatihan
                            </div>
                            
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-code"></i>
                                Kode Pelatihan
                            </span>
                            <span class="data-value">{{ $jenisPelatihanData->kode_pelatihan ?? 'Belum diisi' }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-book"></i>
                                Nama Pelatihan
                            </span>
                            <span class="data-value">{{ $jenisPelatihanData->nama_pelatihan ?? 'Belum diisi' }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-align-left"></i>
                                Deskripsi
                            </span>
                            <span class="data-value">{{ $jenisPelatihanData->deskripsi ?? 'Belum diisi' }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-toggle-on"></i>
                                Status Pelatihan
                            </span>
                            <span class="data-value">
                                @if($jenisPelatihanData)
                                    <span class="status-badge {{ $jenisPelatihanData->aktif ? 'active' : 'inactive' }}">
                                        {{ $jenisPelatihanData->aktif ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                @else
                                    Belum diisi
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Data Angkatan -->
                    <div class="data-card">
                        <div class="data-card-header">
                            <div class="data-card-title">
                                <i class="fas fa-users"></i>
                                Angkatan
                            </div>
                            
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-user-friends"></i>
                                Nama Angkatan
                            </span>
                            <span class="data-value">{{ $angkatanData->nama_angkatan ?? 'Belum diisi' }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-calendar"></i>
                                Tahun
                            </span>
                            <span class="data-value">{{ $angkatanData->tahun ?? 'Belum diisi' }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-play-circle"></i>
                                Tanggal Mulai
                            </span>
                            <span class="data-value">
                                {{ $angkatanData->tanggal_mulai ? \Carbon\Carbon::parse($angkatanData->tanggal_mulai)->format('d F Y') : 'Belum diisi' }}
                            </span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-flag-checkered"></i>
                                Tanggal Selesai
                            </span>
                            <span class="data-value">
                                {{ $angkatanData->tanggal_selesai ? \Carbon\Carbon::parse($angkatanData->tanggal_selesai)->format('d F Y') : 'Belum diisi' }}
                            </span>
                        </div>
                    </div>

                    <!-- Data Pendaftaran -->
                    <div class="data-card">
                        <div class="data-card-header">
                            <div class="data-card-title">
                                <i class="fas fa-clipboard-check"></i>
                                Status Pendaftaran
                            </div>
                            
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-tasks"></i>
                                Status
                            </span>
                            <span class="data-value">
                                @php
                                    $statusPendaftaran = $pendaftaranTerbaru->status_pendaftaran ?? null;
                                    $statusClass = '';
                                    $statusText = 'Belum diisi';
                                    
                                    if ($statusPendaftaran) {
                                        switch (strtolower($statusPendaftaran)) {
                                            case 'terdaftar':
                                            case 'diverifikasi':
                                            case 'lulus':
                                                $statusClass = 'active';
                                                break;
                                            case 'ditolak':
                                                $statusClass = 'inactive';
                                                break;
                                            default:
                                                $statusClass = 'pending';
                                        }
                                        $statusText = ucfirst($statusPendaftaran);
                                    }
                                @endphp
                                @if($statusPendaftaran)
                                    <span class="status-badge {{ $statusClass }}">
                                        {{ $statusText }}
                                    </span>
                                @else
                                    {{ $statusText }}
                                @endif
                            </span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-calendar-plus"></i>
                                Tanggal Daftar
                            </span>
                            <span class="data-value">
                                {{ $pendaftaranTerbaru->tanggal_daftar ? \Carbon\Carbon::parse($pendaftaranTerbaru->tanggal_daftar)->format('d F Y H:i') : 'Belum diisi' }}
                            </span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-calendar-check"></i>
                                Tanggal Verifikasi
                            </span>
                            <span class="data-value">
                                {{ $pendaftaranTerbaru->tanggal_verifikasi ? \Carbon\Carbon::parse($pendaftaranTerbaru->tanggal_verifikasi)->format('d F Y H:i') : 'Belum diverifikasi' }}
                            </span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-sticky-note"></i>
                                Catatan Verifikasi
                            </span>
                            <span class="data-value">{{ $pendaftaranTerbaru->catatan_verifikasi ?? 'Tidak ada catatan' }}</span>
                        </div>
                    </div>

                    <!-- Informasi Tambahan -->
                    <div class="data-card">
                        <div class="data-card-header">
                            <div class="data-card-title">
                                <i class="fas fa-info-circle"></i>
                                Informasi Tambahan
                            </div>
                            
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-users"></i>
                                Kuota Angkatan
                            </span>
                            <span class="data-value">{{ $angkatanData->kuota ?? 'Tidak tersedia' }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-toggle-on"></i>
                                Status Angkatan
                            </span>
                            <span class="data-value">
                                @if($angkatanData && isset($angkatanData->status_angkatan))
                                    <span class="status-badge {{ $angkatanData->status_angkatan ? 'active' : 'inactive' }}">
                                        {{ $angkatanData->status_angkatan ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                @else
                                    Tidak tersedia
                                @endif
                            </span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-clock"></i>
                                Dibuat Pada
                            </span>
                            <span class="data-value">
                                {{ $angkatanData->dibuat_pada ? \Carbon\Carbon::parse($angkatanData->dibuat_pada)->format('d F Y H:i') : 'Tidak tersedia' }}
                            </span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-check-circle"></i>
                                Pelatihan Aktif
                            </span>
                            <span class="data-value">
                                @if($jenisPelatihanData)
                                    {{ $jenisPelatihanData->aktif ? 'Ya' : 'Tidak' }}
                                @else
                                    Tidak tersedia
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Tampilkan semua pendaftaran jika ada lebih dari satu -->
                @if($semuaPendaftaran && count($semuaPendaftaran) > 1)
                <div class="mt-4">
                    <div class="section-header mb-3">
                        <div class="section-title">
                            <i class="fas fa-history"></i>
                            <span>Riwayat Pendaftaran Lainnya</span>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Pelatihan</th>
                                    <th>Angkatan</th>
                                    <th>Tanggal Daftar</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($semuaPendaftaran->skip(1) as $index => $pendaftaran)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $pendaftaran->jenisPelatihan->nama_pelatihan ?? 'Tidak tersedia' }}</td>
                                    <td>{{ $pendaftaran->angkatan->nama_angkatan ?? 'Tidak tersedia' }}</td>
                                    <td>{{ $pendaftaran->tanggal_daftar ? \Carbon\Carbon::parse($pendaftaran->tanggal_daftar)->format('d/m/Y') : '-' }}</td>
                                    <td>
                                        @php
                                            $status = $pendaftaran->status_pendaftaran ?? null;
                                            $badgeClass = 'pending';
                                            if ($status === 'terdaftar' || $status === 'diverifikasi' || $status === 'lulus') {
                                                $badgeClass = 'success';
                                            } elseif ($status === 'ditolak') {
                                                $badgeClass = 'inactive';
                                            }
                                        @endphp
                                        @if($status)
                                            <span class="status-badge {{ $badgeClass }}">
                                                {{ ucfirst($status) }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
                @else
                <div class="no-data">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <h4>Belum Terdaftar dalam Pelatihan</h4>
                    <p>Silakan mendaftar pada pelatihan yang tersedia</p>
                    <a href="#" class="btn-edit mt-3">
                        <i class="fas fa-plus-circle"></i>
                        Daftar Pelatihan
                    </a>
                </div>
                @endif
            </div> --}}

            <!-- Tab 4: Data Mentor -->
            <div id="tab-mentor" class="tab-content">
                @if ($mentorData)
                <div class="data-grid">
                    <div class="data-card">
                        <div class="data-card-header">
                            <div class="data-card-title">
                                <i class="fas fa-user-tie"></i>
                                Data Mentor
                            </div>
                            
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-user"></i>
                                Nama Mentor
                            </span>
                            <span class="data-value">{{ $mentorData->nama_mentor ?? 'Belum diisi' }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-briefcase"></i>
                                Jabatan Mentor
                            </span>
                            <span class="data-value">{{ $mentorData->jabatan_mentor ?? 'Belum diisi' }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-envelope"></i>
                                Email Mentor
                            </span>
                            <span class="data-value">{{ $mentorData->email_mentor ?? 'Belum diisi' }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-phone"></i>
                                Nomor HP Mentor
                            </span>
                            <span class="data-value">{{ $mentorData->nomor_hp_mentor ?? 'Belum diisi' }}</span>
                        </div>
                    </div>

                    <div class="data-card">
                        <div class="data-card-header">
                            <div class="data-card-title">
                                <i class="fas fa-file-invoice-dollar"></i>
                                Data Keuangan
                            </div>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-credit-card"></i>
                                Nomor Rekening
                            </span>
                            <span class="data-value">{{ $mentorData->nomor_rekening ?? 'Belum diisi' }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-file-contract"></i>
                                NPWP Mentor
                            </span>
                            <span class="data-value">{{ $mentorData->npwp_mentor ?? 'Belum diisi' }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-toggle-on"></i>
                                Status Aktif Mentor
                            </span>
                            <span class="data-value">
                                <span class="status-badge {{ $mentorData->status_aktif ? 'active' : 'inactive' }}">
                                    {{ $mentorData->status_aktif ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </span>
                        </div>
                        @if($pendaftaranTerbaru && $pendaftaranTerbaru->pesertaMentor->isNotEmpty())
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-calendar-alt"></i>
                                Tanggal Penunjukan
                            </span>
                            <span class="data-value">
                                {{ $pendaftaranTerbaru->pesertaMentor->first()->tanggal_penunjukan ? \Carbon\Carbon::parse($pendaftaranTerbaru->pesertaMentor->first()->tanggal_penunjukan)->format('d F Y') : 'Belum diisi' }}
                            </span>
                        </div>
                        @endif
                    </div>

                    @if($pendaftaranTerbaru && $pendaftaranTerbaru->pesertaMentor->isNotEmpty())
                    <div class="data-card">
                        <div class="data-card-header">
                            <div class="data-card-title">
                                <i class="fas fa-comments"></i>
                                Status Mentoring
                            </div>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-tasks"></i>
                                Status Mentoring
                            </span>
                            <span class="data-value">
                                @php
                                    $statusMentoring = $pendaftaranTerbaru->pesertaMentor->first()->status_mentoring ?? null;
                                    $statusClass = $statusMentoring === 'aktif' ? 'active' : ($statusMentoring === 'selesai' ? 'success' : 'pending');
                                @endphp
                                @if($statusMentoring)
                                    <span class="status-badge {{ $statusClass }}">
                                        {{ ucfirst($statusMentoring) }}
                                    </span>
                                @else
                                    Belum diisi
                                @endif
                            </span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-sticky-note"></i>
                                Catatan Mentoring
                            </span>
                            <span class="data-value">{{ $pendaftaranTerbaru->pesertaMentor->first()->catatan ?? 'Tidak ada catatan' }}</span>
                        </div>
                    </div>
                    @endif
                </div>
                @else
                <div class="no-data">
                    <i class="fas fa-user-tie"></i>
                    <h4>Belum Memiliki Mentor</h4>
                    <p>Anda belum ditunjuk mentor untuk pelatihan ini</p>
                    <a href="#" class="btn-edit mt-3">
                        <i class="fas fa-user-plus"></i>
                        Minta Penunjukan Mentor
                    </a>
                </div>
                @endif
            </div>

            <!-- Tab 5: Dokumen -->
            <div id="tab-dokumen" class="tab-content">
                <div class="file-list">
                    <!-- Dokumen Peserta -->
                    <div class="file-section">
                        <h5 class="mb-3">
                            <i class="fas fa-user-circle"></i>
                            Dokumen Pribadi
                        </h5>
                        
                        <!-- KTP -->
                        <div class="file-item {{ !$peserta->file_ktp ? 'empty' : '' }}">
                            <div class="file-info">
                                <div class="file-icon {{ $peserta->file_ktp ? 'has-file' : 'no-file' }}">
                                    <i class="fas {{ $peserta->file_ktp ? 'fa-id-card' : 'fa-times' }}"></i>
                                </div>
                                <div class="file-details">
                                    <div class="file-name {{ !$peserta->file_ktp ? 'empty' : '' }}">
                                        KTP
                                    </div>
                                    <div class="file-size {{ !$peserta->file_ktp ? 'empty' : '' }}">
                                        {{ $peserta->file_ktp ? 'Dokumen Identitas' : 'Belum diunggah' }}
                                    </div>
                                </div>
                            </div>
                            <div class="file-action">
                                @if($peserta->file_ktp)
                                    <button class="btn-icon view" title="Lihat Dokumen" onclick="viewDocument('KTP', '{{ $peserta->file_ktp }}')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="{{ url($peserta->file_ktp) }}" class="btn-icon download" title="Unduh Dokumen" download>
                                        <i class="fas fa-download"></i>
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Pas Foto -->
                        <div class="file-item {{ !$peserta->file_pas_foto ? 'empty' : '' }}">
                            <div class="file-info">
                                <div class="file-icon {{ $peserta->file_pas_foto ? 'has-file' : 'no-file' }}">
                                    <i class="fas {{ $peserta->file_pas_foto ? 'fa-camera' : 'fa-times' }}"></i>
                                </div>
                                <div class="file-details">
                                    <div class="file-name {{ !$peserta->file_pas_foto ? 'empty' : '' }}">
                                        Pas Foto
                                    </div>
                                    <div class="file-size {{ !$peserta->file_pas_foto ? 'empty' : '' }}">
                                        {{ $peserta->file_pas_foto ? 'Foto Resmi' : 'Belum diunggah' }}
                                    </div>
                                </div>
                            </div>
                            <div class="file-action">
                                @if($peserta->file_pas_foto)
                                    <button class="btn-icon view" title="Lihat Dokumen" onclick="viewDocument('Pas Foto', '{{ $peserta->file_pas_foto }}')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="{{ url($peserta->file_pas_foto) }}" class="btn-icon download" title="Unduh Dokumen" download>
                                        <i class="fas fa-download"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Dokumen Kepegawaian -->
                    @if($kepegawaian)
                    <div class="file-section">
                        <h5 class="mb-3">
                            <i class="fas fa-briefcase"></i>
                            Dokumen Kepegawaian
                        </h5>
                        
                        @php
                            $dokumenKepegawaian = [
                                ['field' => 'file_sk_jabatan', 'name' => 'SK Jabatan', 'icon' => 'fa-file-contract', 'empty_icon' => 'fa-file'],
                                ['field' => 'file_sk_pangkat', 'name' => 'SK Pangkat', 'icon' => 'fa-medal', 'empty_icon' => 'fa-file'],
                                ['field' => 'file_sk_cpns', 'name' => 'SK CPNS', 'icon' => 'fa-file-signature', 'empty_icon' => 'fa-file'],
                                ['field' => 'file_spmt', 'name' => 'SPMT', 'icon' => 'fa-handshake', 'empty_icon' => 'fa-file'],
                                ['field' => 'file_skp', 'name' => 'SKP', 'icon' => 'fa-chart-line', 'empty_icon' => 'fa-file'],
                            ];
                        @endphp

                        @foreach($dokumenKepegawaian as $dokumen)
                            @php
                                $hasFile = !empty($kepegawaian->{$dokumen['field']});
                                $filePath = $hasFile ? $kepegawaian->{$dokumen['field']} : '';
                            @endphp
                            <div class="file-item {{ !$hasFile ? 'empty' : '' }}">
                                <div class="file-info">
                                    <div class="file-icon {{ $hasFile ? 'has-file' : 'no-file' }}">
                                        <i class="fas {{ $hasFile ? $dokumen['icon'] : $dokumen['empty_icon'] }}"></i>
                                    </div>
                                    <div class="file-details">
                                        <div class="file-name {{ !$hasFile ? 'empty' : '' }}">
                                            {{ $dokumen['name'] }}
                                        </div>
                                        <div class="file-size {{ !$hasFile ? 'empty' : '' }}">
                                            {{ $hasFile ? 'Dokumen Kepegawaian' : 'Belum diunggah' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="file-action">
                                    @if($hasFile)
                                        <button class="btn-icon view" title="Lihat Dokumen" onclick="viewDocument('{{ $dokumen['name'] }}', '{{ $filePath }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ url($filePath) }}" class="btn-icon download" title="Unduh Dokumen" download>
                                            <i class="fas fa-download"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @endif

                    <!-- Dokumen Pendaftaran -->
                    @if($pendaftaranTerbaru)
                    <div class="file-section">
                        <h5 class="mb-3">
                            <i class="fas fa-file-alt"></i>
                            Dokumen Pendaftaran
                        </h5>
                        
                        @php
                            $dokumenPendaftaran = [
                                ['field' => 'file_surat_tugas', 'name' => 'Surat Tugas', 'icon' => 'fa-envelope', 'empty_icon' => 'fa-envelope-open'],
                                ['field' => 'file_surat_kesediaan', 'name' => 'Surat Kesediaan', 'icon' => 'fa-hand-paper', 'empty_icon' => 'fa-hand'],
                                ['field' => 'file_pakta_integritas', 'name' => 'Pakta Integritas', 'icon' => 'fa-scroll', 'empty_icon' => 'fa-scroll'],
                                ['field' => 'file_surat_komitmen', 'name' => 'Surat Komitmen', 'icon' => 'fa-file-signature', 'empty_icon' => 'fa-file'],
                                ['field' => 'file_surat_kelulusan_seleksi', 'name' => 'Surat Kelulusan Seleksi', 'icon' => 'fa-graduation-cap', 'empty_icon' => 'fa-graduation-cap'],
                                ['field' => 'file_surat_sehat', 'name' => 'Surat Sehat', 'icon' => 'fa-heart', 'empty_icon' => 'fa-heart'],
                                ['field' => 'file_surat_bebas_narkoba', 'name' => 'Surat Bebas Narkoba', 'icon' => 'fa-ban', 'empty_icon' => 'fa-ban'],
                                ['field' => 'file_surat_pernyataan_administrasi', 'name' => 'Pernyataan Administrasi', 'icon' => 'fa-clipboard-check', 'empty_icon' => 'fa-clipboard'],
                                ['field' => 'file_sertifikat_penghargaan', 'name' => 'Sertifikat Penghargaan', 'icon' => 'fa-award', 'empty_icon' => 'fa-award'],
                                ['field' => 'file_persetujuan_mentor', 'name' => 'Persetujuan Mentor', 'icon' => 'fa-user-check', 'empty_icon' => 'fa-user'],
                            ];
                        @endphp

                        @foreach($dokumenPendaftaran as $dokumen)
                            @php
                                $hasFile = !empty($pendaftaranTerbaru->{$dokumen['field']});
                                $filePath = $hasFile ? $pendaftaranTerbaru->{$dokumen['field']} : '';
                            @endphp
                            <div class="file-item {{ !$hasFile ? 'empty' : '' }}">
                                <div class="file-info">
                                    <div class="file-icon {{ $hasFile ? 'has-file' : 'no-file' }}">
                                        <i class="fas {{ $hasFile ? $dokumen['icon'] : $dokumen['empty_icon'] }}"></i>
                                    </div>
                                    <div class="file-details">
                                        <div class="file-name {{ !$hasFile ? 'empty' : '' }}">
                                            {{ $dokumen['name'] }}
                                        </div>
                                        <div class="file-size {{ !$hasFile ? 'empty' : '' }}">
                                            {{ $hasFile ? 'Dokumen Pendaftaran' : 'Belum diunggah' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="file-action">
                                    @if($hasFile)
                                        <button class="btn-icon view" title="Lihat Dokumen" onclick="viewDocument('{{ $dokumen['name'] }}', '{{ $filePath }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ url($filePath) }}" class="btn-icon download" title="Unduh Dokumen" download>
                                            <i class="fas fa-download"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Document Viewer Modal -->
    <div id="documentViewer" class="document-viewer">
        <div class="document-container">
            <div class="document-header">
                <div class="document-title" id="documentTitle">Pratinjau Dokumen</div>
                <button class="document-close" onclick="closeDocumentViewer()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="document-content">
                <div id="documentPreview" class="document-preview">
                    <!-- Dokumen akan ditampilkan di sini -->
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Tab Functionality
    document.addEventListener('DOMContentLoaded', function() {
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const tabId = button.getAttribute('data-tab');
                
                // Remove active class from all buttons and contents
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabContents.forEach(content => {
                    content.classList.remove('active');
                    content.style.opacity = '0';
                    content.style.transform = 'translateY(20px)';
                });

                // Add active class to clicked button and corresponding content
                button.classList.add('active');
                const activeContent = document.getElementById(tabId);
                if (activeContent) {
                    activeContent.classList.add('active');
                    // Trigger animation
                    setTimeout(() => {
                        activeContent.style.opacity = '1';
                        activeContent.style.transform = 'translateY(0)';
                        activeContent.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    }, 10);
                }
            });
        });

        // Initialize first tab with animation
        const firstTabContent = document.querySelector('.tab-content.active');
        if (firstTabContent) {
            setTimeout(() => {
                firstTabContent.style.opacity = '1';
                firstTabContent.style.transform = 'translateY(0)';
                firstTabContent.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            }, 100);
        }

        // Edit card buttons
        const editCardButtons = document.querySelectorAll('.edit-card-btn');
        editCardButtons.forEach(button => {
            button.addEventListener('click', function() {
                const cardTitle = this.closest('.data-card').querySelector('.data-card-title').textContent;
                alert(`Akan mengedit: ${cardTitle}`);
            });
        });

        

        // Smooth scroll for tabs on mobile
        const tabsNav = document.querySelector('.tabs-nav');
        if (tabsNav) {
            const activeTab = tabsNav.querySelector('.tab-button.active');
            if (activeTab) {
                activeTab.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest',
                    inline: 'center'
                });
            }
        }
    });

    // Document Viewer Functions
    function viewDocument(title, filePath) {
        const viewer = document.getElementById('documentViewer');
        const preview = document.getElementById('documentPreview');
        const docTitle = document.getElementById('documentTitle');
        
        // Set document title
        docTitle.textContent = title;
        
        // Clear previous content
        preview.innerHTML = '';
        
        // Get file extension
        const fileExt = filePath.split('.').pop().toLowerCase();
        
        // Check file type and display accordingly
        if (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'].includes(fileExt)) {
            // Image file
            const img = document.createElement('img');
            img.src =   filePath;
            img.alt = title;
            preview.appendChild(img);
        } else if (['pdf'].includes(fileExt)) {
            // PDF file
            const iframe = document.createElement('iframe');
            iframe.src =  filePath + '#toolbar=0&navpanes=0';
            iframe.title = title;
            preview.appendChild(iframe);
        } else if (['doc', 'docx'].includes(fileExt)) {
            // Word document - show download link
            preview.innerHTML = `
                <div class="document-notice">
                    <i class="fas fa-file-word"></i>
                    <h4>Dokumen Word</h4>
                    <p>Format dokumen ini tidak dapat ditampilkan secara langsung.</p>
                    <p>Silakan unduh untuk membuka.</p>
                    <a href="${filePath}" class="btn-edit mt-3" download>
                        <i class="fas fa-download"></i> Unduh Dokumen
                    </a>
                </div>
            `;
        } else {
            // Other file types
            preview.innerHTML = `
                <div class="document-notice">
                    <i class="fas fa-file"></i>
                    <h4>Format Dokumen Tidak Didukung</h4>
                    <p>Dokumen ini tidak dapat ditampilkan secara langsung.</p>
                    <p>Silakan unduh untuk membuka.</p>
                    <a href="${filePath}" class="btn-edit mt-3" download>
                        <i class="fas fa-download"></i> Unduh Dokumen
                    </a>
                </div>
            `;
        }
        
        // Show viewer
        viewer.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeDocumentViewer() {
        const viewer = document.getElementById('documentViewer');
        viewer.classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    // Close viewer when clicking outside
    document.getElementById('documentViewer').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDocumentViewer();
        }
    });

    // Close viewer with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDocumentViewer();
        }
    });

    // Upload document function
    function uploadDocument(fieldName) {
        alert(`Akan mengunggah dokumen untuk: ${fieldName}`);
        // Di sini Anda bisa menambahkan modal untuk upload
        // Contoh: showUploadModal(fieldName);
    }

    // Update time and date
    function updateDateTime() {
        const now = new Date();
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        };
        const timeString = now.toLocaleTimeString('id-ID', { 
            hour: '2-digit', 
            minute: '2-digit' 
        });
        const dateString = now.toLocaleDateString('id-ID', options);
        
        // If you want to display time somewhere
        const timeElement = document.getElementById('dashboardTime');
        const dateElement = document.getElementById('dashboardDate');
        
        if (timeElement) timeElement.textContent = timeString;
        if (dateElement) dateElement.textContent = dateString;
    }

    // Update time every minute
    setInterval(updateDateTime, 60000);
    updateDateTime();
</script>
@endsection