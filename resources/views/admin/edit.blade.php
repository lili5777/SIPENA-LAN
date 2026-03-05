{{-- resources/views/admin/edit.blade.php --}}
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

            * { box-sizing: border-box; margin: 0; padding: 0; }

            body {
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                background: var(--gray-50);
                color: var(--gray-800);
                line-height: 1.6;
            }

            .form-input.capitalize, .form-textarea.capitalize { text-transform: capitalize; }
            .form-input.uppercase { text-transform: uppercase; }
            .form-input.lowercase { text-transform: lowercase; }

            /* Fixed Notification Container */
            .notification-container {
                position: fixed; top: 20px; right: 20px; z-index: 9999;
                width: 400px; max-width: calc(100vw - 40px);
                display: flex; flex-direction: column; gap: 10px;
            }

            .notification {
                background: white; border-radius: 12px; padding: 1rem 1.25rem;
                box-shadow: var(--shadow-xl); border-left: 4px solid;
                display: flex; align-items: flex-start; gap: 0.75rem;
                animation: slideInRight 0.3s ease-out; transform: translateX(0); transition: all 0.3s ease;
            }
            .notification.hiding { transform: translateX(100%); opacity: 0; }

            @keyframes slideInRight { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
            @keyframes slideInDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }

            .notification-success { border-left-color: var(--success-color); background: linear-gradient(90deg, rgba(16,185,129,0.05) 0%, white 10%); }
            .notification-error   { border-left-color: var(--danger-color);  background: linear-gradient(90deg, rgba(239,68,68,0.05)  0%, white 10%); }
            .notification-warning { border-left-color: var(--warning-color); background: linear-gradient(90deg, rgba(245,158,11,0.05) 0%, white 10%); }
            .notification-info    { border-left-color: var(--info-color);    background: linear-gradient(90deg, rgba(59,130,246,0.05)  0%, white 10%); }

            .notification i { margin-top: 0.125rem; font-size: 1.25rem; }
            .notification-success i { color: var(--success-color); }
            .notification-error   i { color: var(--danger-color); }
            .notification-warning i { color: var(--warning-color); }
            .notification-info    i { color: var(--info-color); }

            .notification-content { flex: 1; min-width: 0; }
            .notification-title   { font-weight: 700; margin-bottom: 0.25rem; font-size: 0.95rem; color: var(--gray-800); }
            .notification-message { font-size: 0.875rem; color: var(--gray-600); }
            .notification-close   { background: none; border: none; color: var(--gray-400); cursor: pointer; padding: 0.25rem; opacity: 0.7; transition: opacity 0.2s; }
            .notification-close:hover { opacity: 1; }

            /* Container */
            .edit-container { max-width: 1400px; margin: 0 auto; padding: 2rem; }

            /* Header */
            .edit-header {
                background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
                color: white; border-radius: 16px; padding: 2.5rem; margin-bottom: 2rem;
                position: relative; overflow: hidden; box-shadow: var(--shadow-lg);
            }
            .edit-header::before {
                content: ''; position: absolute; top: -50%; right: -10%;
                width: 400px; height: 400px;
                background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%); border-radius: 50%;
            }
            .edit-header::after {
                content: ''; position: absolute; bottom: -30%; left: -5%;
                width: 300px; height: 300px;
                background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%); border-radius: 50%;
            }
            .header-content { position: relative; z-index: 1; }
            .edit-header h1 { font-size: 2rem; font-weight: 800; margin-bottom: 0.5rem; letter-spacing: -0.025em; }
            .edit-header p  { opacity: 0.95; font-size: 1.05rem; margin-bottom: 1.5rem; }

            .back-button {
                display: inline-flex; align-items: center; gap: 0.5rem; color: white; text-decoration: none;
                padding: 0.625rem 1.25rem; background: rgba(255,255,255,0.2); border-radius: 10px;
                transition: all 0.3s cubic-bezier(0.4,0,0.2,1); font-weight: 600;
                backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1);
            }
            .back-button:hover { background: rgba(255,255,255,0.3); transform: translateX(-5px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }

            /* Alert Messages */
            .alert-container { margin-bottom: 1.5rem; }
            .alert {
                padding: 1rem 1.25rem; border-radius: 12px; margin-bottom: 1rem;
                display: flex; align-items: flex-start; gap: 0.75rem; border: 1px solid;
                animation: slideInDown 0.3s ease-out;
            }
            .alert-success { background: #d1fae5; color: #065f46; border-color: #6ee7b7; }
            .alert-danger  { background: #fee2e2; color: #991b1b; border-color: #fca5a5; }
            .alert-warning { background: #fef3c7; color: #92400e; border-color: #fcd34d; }
            .alert-info    { background: #dbeafe; color: #1e40af; border-color: #93c5fd; }
            .alert i { margin-top: 0.125rem; font-size: 1.25rem; }
            .alert-content { flex: 1; }
            .alert-title   { font-weight: 700; margin-bottom: 0.25rem; font-size: 1rem; }
            .alert-message { font-size: 0.95rem; opacity: 0.95; }
            .alert-close   { background: none; border: none; color: inherit; cursor: pointer; padding: 0.25rem; opacity: 0.6; transition: opacity 0.2s; }
            .alert-close:hover { opacity: 1; }

            /* Error summary */
            .error-summary { background: #fee2e2; border: 1px solid #fca5a5; border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem; animation: slideInDown 0.3s ease-out; }
            .error-summary h4 { color: #991b1b; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; }
            .error-summary ul { list-style: none; padding: 0; margin: 0; }
            .error-summary li { margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.5rem; border-radius: 8px; transition: background-color 0.2s; }
            .error-summary li:hover { background-color: rgba(239,68,68,0.1); }
            .error-summary li:last-child { margin-bottom: 0; }
            .error-summary .error-count { background: var(--danger-color); color: white; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.875rem; font-weight: 600; }

            /* Form */
            .edit-form { background: white; border-radius: 16px; box-shadow: var(--shadow-xl); overflow: hidden; }

            /* Tabs */
            .form-tabs {
                display: flex; background: var(--gray-50); border-bottom: 2px solid var(--gray-200);
                overflow-x: auto; scrollbar-width: thin; scrollbar-color: var(--gray-400) var(--gray-100);
            }
            .form-tabs::-webkit-scrollbar { height: 6px; }
            .form-tabs::-webkit-scrollbar-track { background: var(--gray-100); }
            .form-tabs::-webkit-scrollbar-thumb { background: var(--gray-400); border-radius: 3px; }

            .form-tab {
                padding: 1.25rem 2rem; background: none; border: none; font-weight: 600;
                color: var(--gray-600); cursor: pointer; transition: all 0.3s ease; white-space: nowrap;
                position: relative; display: flex; align-items: center; gap: 0.75rem;
                min-width: 180px; justify-content: center; font-size: 0.95rem;
            }
            .form-tab i { font-size: 1.1rem; }
            .form-tab:hover:not(.active) { color: var(--primary-color); background: rgba(26,58,108,0.05); }
            .form-tab.active { color: var(--primary-color); background: white; }
            .form-tab.active::after {
                content: ''; position: absolute; bottom: -2px; left: 0; width: 100%; height: 3px;
                background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            }
            .form-tab.error::before {
                content: ''; position: absolute; top: 0.75rem; right: 0.75rem;
                width: 8px; height: 8px; background: var(--danger-color); border-radius: 50%; animation: pulse 2s infinite;
            }
            @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }

            /* Tab Content */
            .form-tab-content { display: none; padding: 2.5rem; animation: fadeInUp 0.4s ease-out; }
            .form-tab-content.active { display: block; }
            @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

            /* Section Header */
            .form-section-header {
                display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem;
                padding-bottom: 1rem; border-bottom: 2px solid var(--gray-200);
            }
            .form-section-header i {
                color: var(--primary-color);
                background: linear-gradient(135deg, rgba(26,58,108,0.1), rgba(37,99,235,0.1));
                width: 56px; height: 56px; border-radius: 12px;
                display: flex; align-items: center; justify-content: center; font-size: 1.5rem;
            }
            .form-section-header h3 { font-size: 1.5rem; font-weight: 700; color: var(--dark-color); letter-spacing: -0.025em; }

            /* Form Layout */
            .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem; }
            .form-row-3 { grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); }
            .form-group { margin-bottom: 1.5rem; }

            /* Foto Styles */
            .foto-container { display: flex; gap: 2rem; align-items: flex-start; }
            .foto-upload-section { flex: 1; }
            .foto-example-section { flex: 0 0 180px; display: flex; flex-direction: column; gap: 0.75rem; }
            .example-photo-label { font-size: 0.875rem; color: var(--gray-600); font-weight: 600; text-align: center; }
            .example-photo-container { width: 120px; height: 160px; border: 2px solid var(--primary-color); border-radius: 8px; overflow: hidden; box-shadow: var(--shadow-sm); margin: 0 auto; }
            .example-photo-container img { width: 100%; height: 100%; object-fit: cover; }
            .example-photo-note { font-size: 0.75rem; color: var(--gray-500); text-align: center; font-style: italic; }

            @media (max-width: 768px) {
                .foto-container { flex-direction: column-reverse; gap: 1.5rem; }
                .foto-example-section { flex: none; width: 100%; align-items: center; }
            }

            .form-group.full-width { grid-column: 1 / -1; }

            /* Form Labels */
            .form-label { display: block; font-weight: 600; margin-bottom: 0.625rem; color: var(--gray-700); font-size: 0.95rem; }
            .form-label.required::after { content: ' *'; color: var(--danger-color); font-weight: 700; }

            /* Form Inputs */
            .form-input, .form-select, .form-textarea {
                width: 100%; padding: 0.875rem 1rem; border: 2px solid var(--gray-300);
                border-radius: 10px; font-size: 1rem; transition: all 0.2s ease; background: white; font-family: inherit;
            }
            .form-input:focus, .form-select:focus, .form-textarea:focus {
                outline: none; border-color: var(--primary-color); box-shadow: 0 0 0 4px rgba(26,58,108,0.1);
            }
            .form-input:disabled, .form-select:disabled, .form-textarea:disabled {
                background: var(--gray-100); color: var(--gray-500); cursor: not-allowed;
            }
            .form-input.error, .form-select.error, .form-textarea.error { border-color: var(--danger-color); background: #fef2f2; }
            .form-input.error:focus, .form-select.error:focus, .form-textarea.error:focus { box-shadow: 0 0 0 4px rgba(239,68,68,0.1); }
            .form-input.success { border-color: var(--success-color); }
            .form-textarea { min-height: 120px; resize: vertical; line-height: 1.6; }
            .form-hint { display: block; margin-top: 0.5rem; font-size: 0.875rem; color: var(--gray-500); }
            .form-hint i { margin-right: 0.25rem; }

            /* Error Messages */
            .error-message {
                color: var(--danger-color); font-size: 0.875rem; margin-top: 0.5rem;
                display: flex; align-items: center; gap: 0.375rem; font-weight: 500;
            }
            .error-message i { font-size: 0.875rem; }
            .text-danger { color: var(--danger-color); }
            .text-success { color: var(--success-color); }

            /* File Upload */
            .form-file { position: relative; margin-top: 0.5rem; }
            .form-file-input { position: absolute; width: 0.1px; height: 0.1px; opacity: 0; overflow: hidden; z-index: -1; }
            .form-file-label {
                display: flex; flex-direction: column; align-items: center; justify-content: center;
                padding: 2.5rem; border: 2px dashed var(--gray-300); border-radius: 12px;
                background: var(--gray-50); color: var(--gray-600); cursor: pointer; transition: all 0.3s ease; text-align: center;
            }
            .form-file-label:hover { border-color: var(--primary-color); background: #f0f4ff; }
            .form-file-label i { font-size: 2.5rem; margin-bottom: 0.75rem; color: var(--primary-color); }
            .form-file-label-text { font-weight: 600; color: var(--gray-700); margin-bottom: 0.25rem; }
            .form-file-label-hint { font-size: 0.875rem; color: var(--gray-500); }
            .form-file-name { margin-top: 1rem; }
            .file-info { display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: var(--gray-50); border-radius: 10px; border: 1px solid var(--gray-200); }
            .file-info i.fa-check-circle { color: var(--success-color); font-size: 1.25rem; }
            .file-info i.fa-file-pdf    { color: var(--danger-color);  font-size: 1.25rem; }
            .file-info i.fa-file-image  { color: var(--info-color);    font-size: 1.25rem; }
            .file-info-content { flex: 1; min-width: 0; }
            .file-name { font-weight: 600; color: var(--gray-700); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
            .file-size { font-size: 0.875rem; color: var(--gray-500); }

            .btn-change-file {
                background: var(--primary-color); color: white; border: none; padding: 0.5rem 1rem;
                border-radius: 8px; font-size: 0.875rem; cursor: pointer; transition: all 0.2s ease;
                display: flex; align-items: center; gap: 0.375rem; font-weight: 600; white-space: nowrap;
            }
            .btn-change-file:hover { background: var(--secondary-color); transform: translateY(-1px); box-shadow: var(--shadow-md); }
            .btn-remove-file { background: var(--danger-color); color: white; border: none; padding: 0.5rem; border-radius: 8px; cursor: pointer; transition: all 0.2s ease; }
            .btn-remove-file:hover { background: #dc2626; }
            .no-file { color: var(--gray-400); font-style: italic; font-size: 0.9rem; }

            /* Mentor Container */
            #mentor-container { margin-top: 1.5rem; padding: 2rem; background: var(--gray-50); border-radius: 12px; border: 1px solid var(--gray-200); }

            /* Form Actions */
            .form-actions {
                display: flex; justify-content: space-between; align-items: center;
                padding: 2rem 2.5rem; background: var(--gray-50); border-top: 2px solid var(--gray-200);
                gap: 1rem; flex-wrap: wrap;
            }
            .form-actions-left { display: flex; gap: 1rem; align-items: center; }

            .btn {
                padding: 0.875rem 2rem; border-radius: 10px; font-weight: 600; text-decoration: none;
                display: inline-flex; align-items: center; gap: 0.625rem;
                transition: all 0.3s cubic-bezier(0.4,0,0.2,1); cursor: pointer; font-size: 1rem; border: none;
            }
            .btn-cancel { background: white; color: var(--gray-700); border: 2px solid var(--gray-300); }
            .btn-cancel:hover { background: var(--gray-100); border-color: var(--gray-400); }
            .btn-submit { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; box-shadow: var(--shadow-md); }
            .btn-submit:hover:not(:disabled) { transform: translateY(-2px); box-shadow: var(--shadow-lg); }
            .btn-submit:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

            .spinner { display: inline-block; width: 1rem; height: 1rem; border: 2px solid rgba(255,255,255,0.3); border-radius: 50%; border-top-color: white; animation: spin 0.8s linear infinite; }
            @keyframes spin { to { transform: rotate(360deg); } }

            .form-progress { display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: var(--gray-600); }
            .progress-text { font-weight: 600; }
            .progress-bar-container { width: 120px; height: 6px; background: var(--gray-200); border-radius: 3px; overflow: hidden; }
            .progress-bar { height: 100%; background: linear-gradient(90deg, var(--primary-color), var(--secondary-color)); transition: width 0.3s ease; }

            /* Crop Styles */
            .crop-container { margin-bottom: 1.5rem; border: 2px solid var(--primary-color); border-radius: 12px; padding: 1.5rem; background: white; box-shadow: var(--shadow-md); }
            .crop-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid var(--gray-200); }
            .crop-header h4 { margin: 0; color: var(--primary-color); font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; }
            .btn-close-crop { background: var(--gray-100); border: none; width: 32px; height: 32px; border-radius: 50%; color: var(--gray-600); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s ease; }
            .btn-close-crop:hover { background: var(--danger-color); color: white; }
            .crop-preview-container { display: flex; gap: 2rem; margin-bottom: 1.5rem; }
            .crop-preview-wrapper { flex: 0 0 120px; }
            .crop-preview-label { font-size: 0.875rem; color: var(--gray-600); margin-bottom: 0.5rem; text-align: center; font-weight: 600; }
            .crop-preview { width: 120px; height: 160px; overflow: hidden; border: 2px solid var(--primary-color); border-radius: 8px; background: white; display: flex; align-items: center; justify-content: center; box-shadow: var(--shadow-sm); }
            .crop-main-wrapper { flex: 1; min-height: 300px; max-height: 400px; overflow: hidden; border: 1px solid var(--gray-300); border-radius: 8px; background: white; display: flex; align-items: center; justify-content: center; position: relative; }
            .crop-image { max-width: 100%; max-height: 100%; }
            .crop-controls { display: flex; justify-content: center; gap: 0.75rem; padding: 1rem; background: var(--gray-50); border-radius: 8px; border: 1px solid var(--gray-200); margin-bottom: 1.5rem; }
            .btn-crop-action { width: 44px; height: 44px; border: 2px solid var(--gray-300); background: white; border-radius: 8px; color: var(--gray-700); cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; justify-content: center; font-size: 1rem; }
            .btn-crop-action:hover { background: var(--primary-color); color: white; border-color: var(--primary-color); transform: translateY(-2px); }
            .crop-action-buttons { display: flex; justify-content: flex-end; gap: 1rem; padding-top: 1rem; border-top: 1px solid var(--gray-200); }
            .btn-crop-primary, .btn-crop-secondary { padding: 0.75rem 1.5rem; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; gap: 0.5rem; font-size: 0.95rem; }
            .btn-crop-primary { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; box-shadow: var(--shadow-sm); }
            .btn-crop-primary:hover { transform: translateY(-2px); box-shadow: var(--shadow); }
            .btn-crop-secondary { background: var(--gray-100); color: var(--gray-700); }
            .btn-crop-secondary:hover { background: var(--gray-200); transform: translateY(-2px); }
            .file-actions { display: flex; gap: 0.5rem; flex-shrink: 0; }
            .file-actions .btn-change-file { padding: 0.5rem 0.75rem; font-size: 0.85rem; white-space: nowrap; }

            /* Mentor Search */
            #mentor-search {
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='11' cy='11' r='8'%3E%3C/circle%3E%3Cpath d='m21 21-4.35-4.35'%3E%3C/path%3E%3C/svg%3E");
                background-repeat: no-repeat; background-position: right 1rem center; background-size: 1rem; padding-right: 3rem;
            }
            #mentor-search:focus { border-color: var(--primary-color); box-shadow: 0 0 0 4px rgba(26,58,108,0.1); }
            #mentor-search-info, #mentor-loading, #mentor-not-found { animation: fadeIn 0.3s ease; }
            @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }

            /* ===== INSTANSI SEARCHABLE SELECT ===== */
            .instansi-select-wrapper #instansi_trigger:hover { border-color: var(--primary-color); }
            #instansi_list::-webkit-scrollbar { width: 6px; }
            #instansi_list::-webkit-scrollbar-track { background: var(--gray-100); }
            #instansi_list::-webkit-scrollbar-thumb { background: var(--gray-400); border-radius: 3px; }
            #instansi_list::-webkit-scrollbar-thumb:hover { background: var(--gray-500); }
            #asal_instansi_search:focus { border-color: var(--primary-color) !important; box-shadow: 0 0 0 3px rgba(26,58,108,0.1); }

            /* Highlight error fields */
            .error-field { animation: pulseError 1.5s ease-in-out; border-color: var(--danger-color) !important; box-shadow: 0 0 0 4px rgba(239,68,68,0.2) !important; }
            @keyframes pulseError { 0%, 100% { background-color: transparent; } 50% { background-color: rgba(239,68,68,0.1); } }

            /* Responsive */
            @media (max-width: 1200px) { .edit-container { padding: 1.5rem; } }
            @media (max-width: 992px) {
                .edit-container { padding: 1rem; }
                .edit-header { padding: 2rem; }
                .edit-header h1 { font-size: 1.75rem; }
                .form-tab { min-width: 160px; padding: 1rem 1.5rem; font-size: 0.9rem; }
                .form-tab-content { padding: 2rem; }
                .form-row { grid-template-columns: 1fr; }
                .form-section-header { flex-direction: column; text-align: center; gap: 0.75rem; }
                .form-section-header i { width: 50px; height: 50px; }
                .form-section-header h3 { font-size: 1.3rem; }
            }
            @media (max-width: 768px) {
                .edit-header h1 { font-size: 1.5rem; }
                .form-tab { min-width: 140px; padding: 0.875rem 1rem; font-size: 0.875rem; }
                .form-tab-content { padding: 1.5rem; }
                .form-actions { flex-direction: column; align-items: stretch; padding: 1.5rem; }
                .form-actions-left { flex-direction: column; width: 100%; }
                .btn-cancel, .btn-submit { width: 100%; justify-content: center; }
                .notification-container { width: 350px; right: 10px; top: 10px; }
                .crop-preview-container { flex-direction: column; gap: 1rem; }
                .crop-preview-wrapper { align-self: center; }
                .crop-main-wrapper { min-height: 250px; }
                .crop-action-buttons { flex-direction: column; }
                .file-actions { flex-direction: column; width: 100%; }
                .file-actions .btn-change-file { width: 100%; justify-content: center; }
            }
            @media (max-width: 480px) {
                .edit-header { padding: 1.5rem; }
                .edit-header h1 { font-size: 1.25rem; }
                .form-tabs { flex-wrap: nowrap; overflow-x: auto; }
                .form-tab { flex: 0 0 auto; min-width: 120px; padding: 0.75rem 0.5rem; font-size: 0.8rem; flex-direction: column; gap: 0.25rem; }
                .form-tab i { font-size: 1.25rem; }
                .form-file-label { padding: 2rem 1rem; }
                .notification-container { width: 300px; right: 10px; left: 10px; margin: 0 auto; }
            }
            html { scroll-behavior: smooth; }
            *:focus-visible { outline: 2px solid var(--primary-color); outline-offset: 2px; }

        </style>
@endsection
@section('content')
    <!-- Notification Container -->
    <div class="notification-container" id="notificationContainer"></div>

    <div class="edit-container">
        <!-- Header -->
        <div class="edit-header">
            <div class="header-content">
                <h1><i class="fas fa-user-edit"></i> Edit Data Peserta</h1>
                <p>Perbarui informasi pribadi, kepegawaian, dan dokumen Anda dengan lengkap dan akurat</p>
                <a href="{{ route('dashboard') }}" class="back-button">
                    <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
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
                    <button type="button" class="alert-close" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <div class="alert-content">
                        <div class="alert-title">Terjadi Kesalahan!</div>
                        <div class="alert-message">{{ session('error') }}</div>
                    </div>
                    <button type="button" class="alert-close" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
                </div>
            @endif
            @if($errors->any())
                <div class="error-summary" id="errorSummary">
                    <h4>
                        <i class="fas fa-exclamation-triangle"></i>
                        Terdapat <span class="error-count">{{ $errors->count() }}</span> kesalahan yang perlu diperbaiki
                    </h4>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li onclick="scrollToFieldError('{{ $error }}')">
                                <i class="fas fa-exclamation-circle text-danger"></i> {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- Form -->
        <form id="editForm" class="edit-form" method="POST" action="{{ route('admin.dashboard.update') }}"
            enctype="multipart/form-data" novalidate>
            @csrf

            <!-- Tab Navigation -->
            <div class="form-tabs">
                <button type="button" class="form-tab active" data-tab="tab-personal" id="tab-btn-personal">
                    <i class="fas fa-user"></i><span>Data Pribadi</span>
                </button>
                <button type="button" class="form-tab" data-tab="tab-employment" id="tab-btn-employment">
                    <i class="fas fa-briefcase"></i><span>Kepegawaian</span>
                </button>
                <button type="button" class="form-tab" data-tab="tab-mentor" id="tab-btn-mentor">
                    <i class="fas fa-user-tie"></i><span>Mentor</span>
                </button>
                <button type="button" class="form-tab" data-tab="tab-documents" id="tab-btn-documents">
                    <i class="fas fa-file-alt"></i><span>Dokumen</span>
                </button>
            </div>

            {{-- ============================================
                            TAB 1: DATA PRIBADI
            ============================================= --}}
            <div id="tab-personal" class="form-tab-content active">
                <div class="form-section-header">
                    <i class="fas fa-user-circle"></i><h3>Informasi Pribadi</h3>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">NIP/NRP</label>
                        <input type="text" name="nip_nrp" class="form-input @error('nip_nrp') error @enderror"
                            value="{{ old('nip_nrp', $peserta->nip_nrp) }}" required readonly>
                        <small class="form-hint"><i class="fas fa-info-circle"></i> NIP/NRP tidak dapat diubah</small>
                        @error('nip_nrp')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label required">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-input @error('nama_lengkap') error @enderror"
                            value="{{ old('nama_lengkap', $peserta->nama_lengkap) }}" required placeholder="Contoh: Muhammad Ali, S.H., M.H.">
                        @error('nama_lengkap')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nama Panggilan</label>
                        <input type="text" name="nama_panggilan" class="form-input capitalize @error('nama_panggilan') error @enderror"
                            value="{{ old('nama_panggilan', $peserta->nama_panggilan) }}" placeholder="Contoh: Rudi">
                        @error('nama_panggilan')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label required">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') error @enderror" required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin', $peserta->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin', $peserta->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Agama</label>
                        <select name="agama" class="form-select @error('agama') error @enderror" required>
                            <option value="">-- Pilih Agama --</option>
                            @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $agama)
                                <option value="{{ $agama }}" {{ old('agama', $peserta->agama) == $agama ? 'selected' : '' }}>{{ $agama }}</option>
                            @endforeach
                        </select>
                        @error('agama')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label required">Status Perkawinan</label>
                        <select name="status_perkawinan" id="status_perkawinan" class="form-select @error('status_perkawinan') error @enderror" required>
                            <option value="">-- Pilih Status --</option>
                            @foreach(['Belum Menikah','Menikah','Duda','Janda'] as $status)
                                <option value="{{ $status }}" {{ old('status_perkawinan', $peserta->status_perkawinan) == $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                        @error('status_perkawinan')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-row" id="nama-pasangan-container"
                    style="{{ old('status_perkawinan', $peserta->status_perkawinan) == 'Menikah' ? 'display: grid;' : 'display: none;' }}">
                    <div class="form-group">
                        <label class="form-label">Nama Pasangan</label>
                        <input type="text" name="nama_pasangan" id="nama_pasangan"
                            class="form-input capitalize @error('nama_pasangan') error @enderror"
                            value="{{ old('nama_pasangan', $peserta->nama_pasangan) }}"
                            {{ old('status_perkawinan', $peserta->status_perkawinan) == 'Menikah' ? '' : 'disabled' }}
                            placeholder="Contoh: Siti Fatimah">
                        <small class="form-hint"><i class="fas fa-info-circle"></i> Diisi hanya jika status "Menikah"</small>
                        @error('nama_pasangan')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>
                    <div class="form-group"></div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="form-input capitalize @error('tempat_lahir') error @enderror"
                            value="{{ old('tempat_lahir', $peserta->tempat_lahir) }}" required placeholder="Contoh: Jakarta">
                        @error('tempat_lahir')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label required">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-input @error('tanggal_lahir') error @enderror"
                            value="{{ old('tanggal_lahir', $peserta->tanggal_lahir) }}" required>
                        @error('tanggal_lahir')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Email Pribadi</label>
                        <input type="email" name="email_pribadi" class="form-input lowercase @error('email_pribadi') error @enderror"
                            value="{{ old('email_pribadi', $peserta->email_pribadi) }}" required placeholder="Contoh: muhammad.ali@example.com">
                        @error('email_pribadi')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label required">Nomor HP</label>
                        <input type="tel" name="nomor_hp" class="form-input @error('nomor_hp') error @enderror"
                            value="{{ old('nomor_hp', $peserta->nomor_hp) }}" required placeholder="08xxxxxxxxxx">
                        @error('nomor_hp')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group full-width">
                    <label class="form-label required">Alamat Rumah</label>
                    <textarea name="alamat_rumah" class="form-textarea capitalize @error('alamat_rumah') error @enderror" required
                        placeholder="Contoh: Jalan Merdeka No. 123, Kelurahan Menteng, Jakarta">{{ old('alamat_rumah', $peserta->alamat_rumah) }}</textarea>
                    @error('alamat_rumah')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Olahraga & Hobi</label>
                        <input type="text" name="olahraga_hobi" class="form-input capitalize @error('olahraga_hobi') error @enderror"
                            value="{{ old('olahraga_hobi', $peserta->olahraga_hobi) }}" placeholder="Contoh: Sepak Bola, Badminton">
                        @error('olahraga_hobi')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label required">Status Perokok</label>
                        <select name="perokok" class="form-select @error('perokok') error @enderror" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="Ya"    {{ old('perokok', $peserta->perokok) == 'Ya'    ? 'selected' : '' }}>Ya</option>
                            <option value="Tidak" {{ old('perokok', $peserta->perokok) == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                        </select>
                        @error('perokok')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-row form-row-3">
                    @foreach([['ukuran_kaos','Ukuran Baju Kaos'],['ukuran_celana','Ukuran Celana'],['ukuran_training','Ukuran Baju Taktikal']] as [$field, $label])
                        <div class="form-group">
                            <label class="form-label required">{{ $label }}</label>
                            <select name="{{ $field }}" class="form-select @error($field) error @enderror">
                                <option value="">-- Pilih Ukuran --</option>
                                @foreach(['S','M','L','XL','XXL','XXXL'] as $size)
                                    <option value="{{ $size }}" {{ old($field, $peserta->$field) == $size ? 'selected' : '' }}>{{ $size }}</option>
                                @endforeach
                            </select>
                            @error($field)<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>
                    @endforeach
                </div>

                <div class="form-section-header">
                    <i class="fas fa-graduation-cap"></i><h3>Pendidikan</h3>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Pendidikan Terakhir (Sesuai SK)</label>
                        <select name="pendidikan_terakhir" class="form-select @error('pendidikan_terakhir') error @enderror" required>
                            <option value="">-- Pilih Pendidikan --</option>
                            @foreach(['SD','SMP','SMU','D3','D4','S1','S2','S3'] as $edu)
                                <option value="{{ $edu }}" {{ old('pendidikan_terakhir', $peserta->pendidikan_terakhir) == $edu ? 'selected' : '' }}>{{ $edu }}</option>
                            @endforeach
                        </select>
                        @error('pendidikan_terakhir')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label required">Bidang Studi</label>
                        <input type="text" name="bidang_studi" class="form-input capitalize @error('bidang_studi') error @enderror"
                            value="{{ old('bidang_studi', $peserta->bidang_studi) }}" placeholder="Contoh: Ilmu Komputer">
                        @error('bidang_studi')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Bidang Keahlian</label>
                        <input type="text" name="bidang_keahlian" class="form-input capitalize @error('bidang_keahlian') error @enderror"
                            value="{{ old('bidang_keahlian', $peserta->bidang_keahlian) }}" placeholder="Contoh: Data Science">
                        @error('bidang_keahlian')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kondisi Peserta</label>
                        <textarea name="kondisi_peserta" class="form-textarea capitalize @error('kondisi_peserta') error @enderror"
                            placeholder="Contoh: Sehat, Tidak Memiliki Alergi">{{ old('kondisi_peserta', $peserta->kondisi_peserta) }}</textarea>
                        @error('kondisi_peserta')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
            {{-- ============================================
                            TAB 2: DATA KEPEGAWAIAN
            ============================================= --}}
            <div id="tab-employment" class="form-tab-content">
                <div class="form-section-header">
                    <i class="fas fa-building"></i><h3>Data Kepegawaian</h3>
                </div>

                <div class="form-row">
                    {{-- ===== ASAL INSTANSI — SEARCHABLE SELECT ===== --}}
                    <div class="form-group">
                        <label class="form-label required">Asal Instansi</label>

                        {{-- Hidden input dikirim ke server --}}
                        <input type="hidden" name="asal_instansi" id="asal_instansi_hidden"
                            value="{{ old('asal_instansi', $kepegawaian->asal_instansi ?? '') }}">

                        {{-- Custom select trigger --}}
                        <div class="instansi-select-wrapper" style="position:relative;">
                            <div id="instansi_trigger"
                                class="form-input @error('asal_instansi') error @enderror"
                                style="cursor:pointer; display:flex; align-items:center; justify-content:space-between; gap:8px; min-height:50px; user-select:none;">
                                <span id="instansi_trigger_label" style="flex:1; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;
                                      color:{{ old('asal_instansi', $kepegawaian->asal_instansi ?? '') ? 'var(--gray-800)' : 'var(--gray-400)' }};">
                                    {{ old('asal_instansi', $kepegawaian->asal_instansi ?? '') ?: 'Pilih asal instansi...' }}
                                </span>
                                <span style="display:flex; align-items:center; gap:6px; flex-shrink:0;">
                                    <span id="instansi_clear_btn"
                                        style="display:{{ old('asal_instansi', $kepegawaian->asal_instansi ?? '') ? 'flex' : 'none' }};
                                               align-items:center; color:#ef4444; cursor:pointer; font-size:1rem; padding:2px 4px;"
                                        title="Hapus pilihan">
                                        <i class="fas fa-times-circle"></i>
                                    </span>
                                    <i class="fas fa-chevron-down" id="instansi_chevron"
                                       style="color:var(--gray-400); font-size:0.85rem; transition:transform 0.2s;"></i>
                                </span>
                            </div>

                            {{-- Dropdown panel --}}
                            <div id="instansi_dropdown"
                                style="display:none; position:absolute; z-index:9999; width:100%;
                                       background:white; border:2px solid var(--primary-color, #1a3a6c); border-top:none;
                                       border-radius:0 0 10px 10px; box-shadow:0 8px 24px rgba(0,0,0,0.12);">

                                {{-- Search box sticky di atas list --}}
                                <div style="padding:10px 12px; border-bottom:1px solid var(--gray-200); background:var(--gray-50); position:sticky; top:0; z-index:1;">
                                    <div style="position:relative;">
                                        <input type="text" id="asal_instansi_search"
                                            placeholder="Cari instansi..."
                                            autocomplete="off"
                                            style="width:100%; padding:9px 36px 9px 12px; border:1.5px solid var(--gray-300);
                                                   border-radius:8px; font-size:0.9rem; outline:none; box-sizing:border-box; font-family:inherit;">
                                        <i class="fas fa-search" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); color:var(--gray-400); font-size:0.85rem; pointer-events:none;"></i>
                                    </div>
                                    <div id="instansi_count" style="font-size:0.75rem; color:var(--gray-400); margin-top:5px; padding-left:2px;">
                                        Menampilkan {{ count(config('instansi')) }} instansi
                                    </div>
                                </div>

                                {{-- List instansi --}}
                                <div id="instansi_list" style="max-height:260px; overflow-y:auto;"></div>
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
                            value="{{ old('unit_kerja', $kepegawaian->unit_kerja ?? '') }}"
                            placeholder="Contoh: Direktorat Jenderal Pelayanan Kesehatan">
                        @error('unit_kerja')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
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
                        @error('id_provinsi')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label required">Kabupaten/Kota</label>
                        <select name="id_kabupaten_kota" id="id_kabupaten_kota"
                            class="form-select @error('id_kabupaten_kota') error @enderror"
                            {{ !$kepegawaian?->id_provinsi ? 'disabled' : '' }}>
                            <option value="">-- Pilih Kabupaten/Kota --</option>
                            @if($kepegawaian && $kepegawaian->id_kabupaten_kota)
                                @php $currentKabupaten = $kabupatenList->firstWhere('id', $kepegawaian->id_kabupaten_kota); @endphp
                                @if($currentKabupaten)
                                    <option value="{{ $currentKabupaten->id }}" selected>{{ $currentKabupaten->name }}</option>
                                @endif
                            @endif
                        </select>
                        @error('id_kabupaten_kota')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group full-width">
                    <label class="form-label required">Alamat Kantor</label>
                    <textarea name="alamat_kantor" class="form-textarea capitalize @error('alamat_kantor') error @enderror" required
                        placeholder="Contoh: Jalan HR Rasuna Said Kaveling 5, Kuningan, Jakarta Selatan">{{ old('alamat_kantor', $kepegawaian->alamat_kantor ?? '') }}</textarea>
                    @error('alamat_kantor')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nomor Telepon Kantor</label>
                        <input type="tel" name="nomor_telepon_kantor" class="form-input @error('nomor_telepon_kantor') error @enderror"
                            value="{{ old('nomor_telepon_kantor', $kepegawaian->nomor_telepon_kantor ?? '') }}" placeholder="021xxxxxxxx">
                        @error('nomor_telepon_kantor')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email Kantor</label>
                        <input type="email" name="email_kantor" class="form-input lowercase @error('email_kantor') error @enderror"
                            value="{{ old('email_kantor', $kepegawaian->email_kantor ?? '') }}" placeholder="Contoh: perencana@kemenkes.go.id">
                        @error('email_kantor')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Jabatan</label>
                        <input type="text" name="jabatan" class="form-input capitalize @error('jabatan') error @enderror"
                            value="{{ old('jabatan', $kepegawaian->jabatan ?? '') }}" required placeholder="Contoh: Perencana Ahli Pertama">
                        @error('jabatan')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>
                    @if (!($jenisPelatihanData->kode_pelatihan == "LATSAR"))
                        <div class="form-group">
                            <label class="form-label required">Eselon</label>
                            <select name="eselon" class="form-select @error('eselon') error @enderror">
                                <option value="">-- Pilih Eselon --</option>
                                @foreach(['II','III/Pejabat Fungsional','IV'] as $es)
                                    <option value="{{ $es }}" {{ old('eselon', $kepegawaian->eselon ?? '') == $es ? 'selected' : '' }}>{{ $es }}</option>
                                @endforeach
                            </select>
                            @error('eselon')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                            <small class="form-hint"><i class="fas fa-info-circle"></i> Pilih eselon sesuai dengan jabatan Anda</small>
                        </div>
                    @endif
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Golongan Ruang</label>
                        <select name="golongan_ruang" id="golongan_ruang" class="form-select @error('golongan_ruang') error @enderror" required>
                            <option value="">-- Pilih Golongan Ruang --</option>
                            @foreach(['II/a','II/b','II/c','II/d','III/a','III/b','III/c','III/d','IV/a','IV/b','IV/c','IV/d'] as $gr)
                                <option value="{{ $gr }}" {{ old('golongan_ruang', $kepegawaian->golongan_ruang ?? '') == $gr ? 'selected' : '' }}>{{ $gr }}</option>
                            @endforeach
                        </select>
                        @error('golongan_ruang')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label required">Pangkat</label>
                        <input type="text" name="pangkat" id="pangkat" class="form-input capitalize @error('pangkat') error @enderror"
                            value="{{ old('pangkat', $kepegawaian->pangkat ?? '') }}" readonly
                            placeholder="Akan terisi otomatis berdasarkan golongan ruang">
                        <div id="pangkat_description" class="form-hint" style="display:none;">
                            <i class="fas fa-info-circle"></i> <span id="pangkat_desc_text"></span>
                        </div>
                        @error('pangkat')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>
                </div>

                @if (!($jenisPelatihanData->kode_pelatihan == "PKN_TK_II"))
                    <div class="form-section-header">
                        <i class="fas fa-file-contract"></i><h3>Data SK</h3>
                    </div>

                    @if (!($jenisPelatihanData->kode_pelatihan == "PKA" || $jenisPelatihanData->kode_pelatihan == "PKP"))
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Nomor SK CPNS</label>
                                <input type="text" name="nomor_sk_cpns" class="form-input uppercase @error('nomor_sk_cpns') error @enderror"
                                    value="{{ old('nomor_sk_cpns', $kepegawaian->nomor_sk_cpns ?? '') }}" placeholder="Contoh: 820/KPTS/2023">
                                @error('nomor_sk_cpns')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Tanggal SK CPNS</label>
                                <input type="date" name="tanggal_sk_cpns" class="form-input @error('tanggal_sk_cpns') error @enderror"
                                    value="{{ old('tanggal_sk_cpns', $kepegawaian->tanggal_sk_cpns ?? '') }}">
                                @error('tanggal_sk_cpns')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                            </div>
                        </div>
                    @endif

                    <div class="form-row">
                        @if (!($jenisPelatihanData->kode_pelatihan == "LATSAR"))
                            <div class="form-group">
                                <label class="form-label required">Nomor SK Terakhir</label>
                                <input type="text" name="nomor_sk_terakhir" class="form-input uppercase @error('nomor_sk_terakhir') error @enderror"
                                    value="{{ old('nomor_sk_terakhir', $kepegawaian->nomor_sk_terakhir ?? '') }}" placeholder="Contoh: 123/SE/2023">
                                @error('nomor_sk_terakhir')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Tanggal SK Jabatan</label>
                                <input type="date" name="tanggal_sk_jabatan" class="form-input @error('tanggal_sk_jabatan') error @enderror"
                                    value="{{ old('tanggal_sk_jabatan', $kepegawaian->tanggal_sk_jabatan ?? '') }}">
                                @error('tanggal_sk_jabatan')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                            </div>
                        @endif
                    </div>

                    @if (!($jenisPelatihanData->kode_pelatihan == "LATSAR"))
                        <div class="form-group">
                            <label class="form-label">Tahun Lulus PKP/PIM IV</label>
                            <input type="number" name="tahun_lulus_pkp_pim_iv" class="form-input @error('tahun_lulus_pkp_pim_iv') error @enderror"
                                value="{{ old('tahun_lulus_pkp_pim_iv', $kepegawaian->tahun_lulus_pkp_pim_iv ?? '') }}"
                                min="1900" max="{{ date('Y') }}" placeholder="{{ date('Y') }}">
                            @error('tahun_lulus_pkp_pim_iv')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>
                    @endif
                @endif
            </div>
            {{-- ============================================
                            TAB 3: DATA MENTOR
            ============================================= --}}
            <div id="tab-mentor" class="form-tab-content">
                <div class="form-section-header">
                    <i class="fas fa-user-tie"></i><h3>Data Mentor</h3>
                </div>

                @if($pendaftaranTerbaru)
                    <div class="form-group">
                        <label class="form-label required">Sudah Ada Penunjukan Mentor?</label>
                        <select name="sudah_ada_mentor" id="sudah_ada_mentor" class="form-select @error('sudah_ada_mentor') error @enderror" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="Ya"    {{ old('sudah_ada_mentor', $pendaftaranTerbaru->id_mentor ? 'Ya' : 'Tidak') == 'Ya'    ? 'selected' : '' }}>Ya</option>
                            <option value="Tidak" {{ old('sudah_ada_mentor', $pendaftaranTerbaru->id_mentor ? 'Ya' : 'Tidak') == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                        </select>
                        @error('sudah_ada_mentor')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    <div id="mentor-container"
                        style="{{ old('sudah_ada_mentor', $pendaftaranTerbaru->id_mentor ? 'Ya' : 'Tidak') == 'Ya' ? 'display: block;' : 'display: none;' }}">

                        <div class="form-group">
                            <label class="form-label required">Pilih Menu Mentor</label>
                            <select name="mentor_mode" id="mentor_mode" class="form-select @error('mentor_mode') error @enderror">
                                <option value="">-- Pilih Menu --</option>
                                @if(count($mentorList) > 0)
                                    <option value="pilih" {{ old('mentor_mode', $pendaftaranTerbaru->id_mentor ? 'pilih' : 'tambah') == 'pilih' ? 'selected' : '' }}>Pilih dari Daftar Mentor</option>
                                @endif
                                <option value="tambah" {{ old('mentor_mode', !$pendaftaranTerbaru->id_mentor ? 'tambah' : '') == 'tambah' ? 'selected' : '' }}>Tambah Mentor Baru</option>
                            </select>
                            @error('mentor_mode')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>

                        @if(count($mentorList) > 0)
                            <div id="select-mentor-form"
                                style="{{ old('mentor_mode', $pendaftaranTerbaru->id_mentor ? 'pilih' : '') == 'pilih' ? 'display: block;' : 'display: none;' }}">

                                <div class="form-group">
                                    <label class="form-label">Cari Mentor</label>
                                    <input type="text" id="mentor-search" class="form-input"
                                        placeholder="Cari berdasarkan nama atau NIP mentor...">
                                </div>
                                <div id="mentor-loading" style="display:none; padding:1rem; text-align:center; color:var(--warning-color);">
                                    <i class="fas fa-spinner fa-spin"></i> Mencari mentor...
                                </div>
                                <div id="mentor-not-found" style="display:none; padding:1rem; text-align:center; color:var(--danger-color);">
                                    <i class="fas fa-exclamation-circle"></i> Tidak ada mentor ditemukan
                                </div>
                                <div id="mentor-search-info" style="display:none; padding:0.75rem; background:#e3f2fd; border-left:3px solid var(--info-color); border-radius:4px; margin-bottom:1rem;">
                                    <small id="mentor-search-stats" style="color:var(--info-color); font-weight:600;"></small>
                                </div>

                                <div class="form-group">
                                    <label class="form-label required">Pilih Mentor</label>
                                    <select name="id_mentor" id="id_mentor" class="form-select @error('id_mentor') error @enderror">
                                        <option value="">-- Pilih Mentor --</option>
                                        @foreach($mentorList as $mentor)
                                            <option value="{{ $mentor->id }}"
                                                data-nama="{{ $mentor->nama_mentor }}"
                                                data-nip="{{ $mentor->nip_mentor }}"
                                                data-jabatan="{{ $mentor->jabatan_mentor }}"
                                                data-golongan="{{ $mentor->golongan }}"
                                                data-pangkat="{{ $mentor->pangkat }}"
                                                data-rekening="{{ $mentor->nomor_rekening }}"
                                                data-npwp="{{ $mentor->npwp_mentor }}"
                                                data-nomorhp="{{ $mentor->nomor_hp_mentor }}"
                                                {{ old('id_mentor', $pendaftaranTerbaru->id_mentor) == $mentor->id ? 'selected' : '' }}>
                                                {{ $mentor->nama_mentor }} - {{ $mentor->nip_mentor ?? 'Tanpa NIP' }} - {{ $mentor->jabatan_mentor }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_mentor')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label">Nama Mentor</label>
                                        <input type="text" name="nama_mentor" id="nama_mentor_select" class="form-input capitalize" readonly
                                            value="{{ old('nama_mentor', $pendaftaranTerbaru->mentor->nama_mentor ?? '') }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">NIP Mentor</label>
                                        <input type="text" name="nip_mentor" id="nip_mentor_select" class="form-input" readonly
                                            value="{{ old('nip_mentor', $pendaftaranTerbaru->mentor->nip_mentor ?? '') }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Jabatan Mentor</label>
                                        <input type="text" name="jabatan_mentor" id="jabatan_mentor_select" class="form-input capitalize" readonly
                                            value="{{ old('jabatan_mentor', $pendaftaranTerbaru->mentor->jabatan_mentor ?? '') }}">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label">Golongan Mentor</label>
                                        <input type="text" name="golongan_mentor" id="golongan_mentor_select" class="form-input" readonly
                                            value="{{ old('golongan_mentor', $pendaftaranTerbaru->mentor->golongan ?? '') }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Pangkat Mentor</label>
                                        <input type="text" name="pangkat_mentor" id="pangkat_mentor_select" class="form-input" readonly
                                            value="{{ old('pangkat_mentor', $pendaftaranTerbaru->mentor->pangkat ?? '') }}">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label">Nama Bank & Nomor Rekening Mentor & Atas Nama</label>
                                        <input type="text" name="nomor_rekening_mentor" id="nomor_rekening_mentor_select" class="form-input" readonly
                                            placeholder="Contoh: BRI 9797XXXXXX , Muhammad Ali"
                                            value="{{ old('nomor_rekening_mentor', $pendaftaranTerbaru->mentor->nomor_rekening ?? '') }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">NPWP Mentor</label>
                                        <input type="text" name="npwp_mentor" id="npwp_mentor_select" class="form-input" readonly
                                            value="{{ old('npwp_mentor', $pendaftaranTerbaru->mentor->npwp_mentor ?? '') }}">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label">Nomor Telepon Mentor</label>
                                        <input type="text" name="nomor_hp_mentor" id="nomor_hp_mentor_select"
                                            class="form-input @error('nomor_hp_mentor') error @enderror"
                                            value="{{ old('nomor_hp_mentor', $pendaftaranTerbaru->mentor->nomor_hp_mentor ?? '') }}"
                                            placeholder="Akan terisi otomatis saat memilih mentor" readonly>
                                        @error('nomor_hp_mentor')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        @else
                            <input type="hidden" name="mentor_mode" value="tambah">
                        @endif

                        <div id="add-mentor-form"
                            style="{{ old('mentor_mode', !$pendaftaranTerbaru->id_mentor ? 'tambah' : '') == 'tambah' ? 'display: block;' : 'display: none;' }}">
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
                                        class="form-input capitalize @error('nama_mentor_baru') error @enderror"
                                        value="{{ old('nama_mentor_baru', $pendaftaranTerbaru->mentor->nama_mentor ?? '') }}"
                                        placeholder="Contoh: Dr. Ahmad Supriyadi, M.Si.">
                                    @error('nama_mentor_baru')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label required">NIP Mentor</label>
                                    <input type="text" name="nip_mentor_baru" id="nip_mentor_baru"
                                        class="form-input @error('nip_mentor_baru') error @enderror"
                                        value="{{ old('nip_mentor_baru', $pendaftaranTerbaru->mentor->nip_mentor ?? '') }}"
                                        placeholder="Contoh: 196504151987031001">
                                    @error('nip_mentor_baru')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label required">Jabatan Mentor</label>
                                    <input type="text" name="jabatan_mentor_baru" id="jabatan_mentor_baru"
                                        class="form-input capitalize @error('jabatan_mentor_baru') error @enderror"
                                        value="{{ old('jabatan_mentor_baru', $pendaftaranTerbaru->mentor->jabatan_mentor ?? '') }}"
                                        placeholder="Contoh: Kepala Bagian Perencanaan">
                                    @error('jabatan_mentor_baru')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Golongan Ruang Mentor</label>
                                    <select name="golongan_mentor_baru" id="golongan_mentor_baru" class="form-select @error('golongan_mentor_baru') error @enderror">
                                        <option value="">-- Pilih Golongan --</option>
                                        @foreach(['II/a','II/b','II/c','II/d','III/a','III/b','III/c','III/d','IV/a','IV/b','IV/c','IV/d'] as $gr)
                                            <option value="{{ $gr }}" {{ old('golongan_mentor_baru', $pendaftaranTerbaru->mentor->golongan ?? '') == $gr ? 'selected' : '' }}>{{ $gr }}</option>
                                        @endforeach
                                    </select>
                                    @error('golongan_mentor_baru')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Pangkat Mentor</label>
                                    <input type="text" name="pangkat_mentor_baru" id="pangkat_mentor_baru"
                                        class="form-input capitalize @error('pangkat_mentor_baru') error @enderror"
                                        value="{{ old('pangkat_mentor_baru', $pendaftaranTerbaru->mentor->pangkat ?? '') }}" readonly
                                        placeholder="Terisi otomatis berdasarkan golongan">
                                    <small class="form-hint"><i class="fas fa-info-circle"></i> Terisi otomatis berdasarkan golongan yang dipilih</small>
                                    @error('pangkat_mentor_baru')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Nama Bank & Nomor Rekening Mentor & Atas Nama</label>
                                    <input type="text" name="nomor_rekening_mentor_baru" id="nomor_rekening_mentor_baru"
                                        class="form-input @error('nomor_rekening_mentor_baru') error @enderror"
                                        placeholder="Contoh: BRI 9797XXXXXX , Muhammad Ali"
                                        value="{{ old('nomor_rekening_mentor_baru', $pendaftaranTerbaru->mentor->nomor_rekening ?? '') }}">
                                    @error('nomor_rekening_mentor_baru')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">NPWP Mentor</label>
                                    <input type="text" name="npwp_mentor_baru" id="npwp_mentor_baru"
                                        class="form-input @error('npwp_mentor_baru') error @enderror"
                                        value="{{ old('npwp_mentor_baru', $pendaftaranTerbaru->mentor->npwp_mentor ?? '') }}">
                                    @error('npwp_mentor_baru')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Nomor Telepon Mentor</label>
                                    <input type="tel" name="nomor_hp_mentor_baru" id="nomor_hp_mentor_baru"
                                        class="form-input @error('nomor_hp_mentor_baru') error @enderror"
                                        value="{{ old('nomor_hp_mentor_baru', $pendaftaranTerbaru->mentor->nomor_hp_mentor ?? '') }}"
                                        placeholder="Contoh: 081234567890">
                                    <small class="form-hint"><i class="fas fa-info-circle"></i> Format: +62812-3456-7890 atau 081234567890</small>
                                    @error('nomor_hp_mentor_baru')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
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
            {{-- ============================================
                            TAB 4: DOKUMEN
            ============================================= --}}
            <div id="tab-documents" class="form-tab-content">
                <div class="form-section-header">
                    <i class="fas fa-file-upload"></i><h3>Dokumen Pendukung</h3>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <div class="alert-content">
                        <div class="alert-title">Perhatian</div>
                        <div class="alert-message">Upload file hanya jika ingin mengganti file yang sudah ada. Format: PDF, JPG, JPEG, PNG (maks. 1MB).</div>
                    </div>
                </div>

                <div class="form-section-header">
                    <i class="fas fa-id-card"></i><h3>Dokumen Pribadi</h3>
                </div>

                <!-- KTP -->
                <div class="form-group">
                    <label class="form-label required">KTP</label>
                    <div class="form-file">
                        <input type="file" name="file_ktp" id="file_ktp"
                            class="form-file-input @error('file_ktp') error @enderror" accept=".pdf,.jpg,.jpeg,.png">
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
                    @error('file_ktp')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                </div>

                <!-- Pas Foto -->
                <div class="form-group">
                    <label class="form-label required">Pas Foto</label>
                    <div class="foto-container">
                        <div class="foto-upload-section">
                            <!-- Crop container -->
                            <div class="crop-container" id="crop-container" style="display:none;">
                                <div class="crop-header">
                                    <h4><i class="fas fa-crop-alt"></i> Crop Foto</h4>
                                    <button type="button" class="btn-close-crop" id="close-crop"><i class="fas fa-times"></i></button>
                                </div>
                                <div class="crop-preview-container">
                                    <div class="crop-preview-wrapper">
                                        <div class="crop-preview-label">Preview</div>
                                        <div class="crop-preview" id="crop-preview"></div>
                                    </div>
                                    <div class="crop-main-wrapper">
                                        <img id="crop-image" class="crop-image" style="max-width:100%;">
                                    </div>
                                </div>
                                <div class="crop-controls">
                                    <button type="button" class="btn-crop-action" id="rotate-left"  title="Rotate Left"><i class="fas fa-undo"></i></button>
                                    <button type="button" class="btn-crop-action" id="rotate-right" title="Rotate Right"><i class="fas fa-redo"></i></button>
                                    <button type="button" class="btn-crop-action" id="zoom-in"      title="Zoom In"><i class="fas fa-search-plus"></i></button>
                                    <button type="button" class="btn-crop-action" id="zoom-out"     title="Zoom Out"><i class="fas fa-search-minus"></i></button>
                                    <button type="button" class="btn-crop-action" id="reset-crop"   title="Reset"><i class="fas fa-sync-alt"></i></button>
                                </div>
                                <div class="crop-action-buttons">
                                    <button type="button" class="btn-crop-secondary" id="cancel-crop"><i class="fas fa-times"></i> Batal</button>
                                    <button type="button" class="btn-crop-primary"   id="save-crop"><i class="fas fa-check"></i> Simpan Crop</button>
                                </div>
                            </div>

                            <div class="form-file" id="form-file-pasfoto">
                                <input type="file" name="file_pas_foto_input" id="file_pas_foto_input"
                                    class="form-file-input @error('file_pas_foto') error @enderror" accept=".jpg,.jpeg,.png">
                                <label for="file_pas_foto_input" class="form-file-label" id="file-pas-foto-label">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <div class="form-file-label-text">Klik untuk mengunggah pas foto</div>
                                    <div class="form-file-label-hint">JPG, JPEG, PNG (Maks. 1MB)</div>
                                    <div class="form-file-label-hint">Akan dicrop ke ukuran 3x4</div>
                                </label>
                                <input type="hidden" name="file_pas_foto" id="file_pas_foto_cropped"
                                    value="{{ old('file_pas_foto', $peserta->file_pas_foto) }}">
                                <div class="form-file-name" id="file-pas-foto-info">
                                    @if($peserta->file_pas_foto)
                                        <div class="file-info">
                                            <i class="fas fa-file-image"></i>
                                            <div class="file-info-content">
                                                <div class="file-name">{{ basename($peserta->file_pas_foto) }}</div>
                                                <div class="file-size">File tersedia</div>
                                            </div>
                                            <div class="file-actions">
                                                <button type="button" class="btn-change-file" id="crop-existing-photo"><i class="fas fa-crop-alt"></i> Crop</button>
                                                <button type="button" class="btn-change-file" id="change-pas-foto"><i class="fas fa-exchange-alt"></i> Ganti</button>
                                            </div>
                                        </div>
                                    @else
                                        <span class="no-file">Belum ada file diupload</span>
                                    @endif
                                </div>
                            </div>
                            @error('file_pas_foto')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>
                        <div class="foto-example-section">
                            <div class="example-photo-label"><i class="fas fa-image"></i> Contoh Foto</div>
                            <div class="example-photo-container">
                                <img src="{{ asset('gambar/contohfoto2.jpeg') }}" alt="Contoh Pas Foto">
                            </div>
                            <div class="example-photo-note">* Gunakan foto formal</div>
                        </div>
                    </div>
                </div>

                @if($kepegawaian)
                    <div class="form-section-header">
                        <i class="fas fa-file-contract"></i><h3>Dokumen Kepegawaian</h3>
                    </div>

                    @php
                        $kepegawaianDocs = [
                            ['name' => 'file_sk_jabatan', 'label' => 'SK Jabatan',                                             'wajib' => 'required'],
                            ['name' => 'file_sk_pangkat', 'label' => 'SK Pangkat',                                             'wajib' => 'required'],
                            ['name' => 'file_sk_cpns',    'label' => 'SK CPNS',                                                'wajib' => 'required'],
                            ['name' => 'file_spmt',       'label' => 'Surat Pernyataan Melaksanakan Tugas (SPMT)',             'wajib' => 'required'],
                            ['name' => 'file_skp',        'label' => 'Sasaran Kinerja Pegawai (SKP)',                          'wajib' => '-'],
                        ];
                        if ($jenisPelatihanData->kode_pelatihan == "LATSAR") {
                            $kepegawaianDocs = array_filter($kepegawaianDocs, fn($d) => !in_array($d['name'], ['file_sk_jabatan','file_sk_pangkat']));
                        }
                        if (in_array($jenisPelatihanData->kode_pelatihan, ["PKN_TK_II","PKA","PKP"])) {
                            $kepegawaianDocs = array_filter($kepegawaianDocs, fn($d) => !in_array($d['name'], ['file_sk_cpns','file_spmt','file_skp']));
                        }
                    @endphp

                    @foreach($kepegawaianDocs as $doc)
                        <div class="form-group">
                            <label class="form-label {{ $doc['wajib'] == 'required' ? 'required' : '' }}">{{ $doc['label'] }}</label>
                            <div class="form-file">
                                <input type="file" name="{{ $doc['name'] }}" id="{{ $doc['name'] }}"
                                    class="form-file-input @error($doc['name']) error @enderror" accept=".pdf">
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
                            @error($doc['name'])<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>
                    @endforeach
                @endif

                @if($pendaftaranTerbaru)
                    <div class="form-section-header">
                        <i class="fas fa-file-alt"></i><h3>Dokumen Pendaftaran</h3>
                    </div>

                    @php
                        $pendaftaranDocs = [
                            ['name' => 'file_surat_tugas',                         'label' => 'Surat Tugas',                                                                        'wajib' => '-'],
                            ['name' => 'file_surat_kesediaan',                     'label' => 'Surat Kesediaan',                                                                    'wajib' => 'required'],
                            ['name' => 'file_pakta_integritas',                    'label' => 'Pakta Integritas',                                                                   'wajib' => 'required'],
                            ['name' => 'file_surat_sehat',                         'label' => 'Surat Sehat',                                                                        'wajib' => '-'],
                            ['name' => 'file_surat_komitmen',                      'label' => 'Surat Komitmen',                                                                     'wajib' => '-'],
                            ['name' => 'file_surat_kelulusan_seleksi',             'label' => 'Surat Kelulusan Seleksi',                                                            'wajib' => '-'],
                            ['name' => 'file_surat_bebas_narkoba',                 'label' => 'Surat Bebas Narkoba',                                                                'wajib' => '-'],
                            ['name' => 'file_surat_pernyataan_administrasi',       'label' => 'Surat Pernyataan Tidak Sedang Mempertanggungjawabkan Penyelesaian Administrasi',     'wajib' => 'required'],
                            ['name' => 'file_persetujuan_mentor',                  'label' => 'Surat Persetujuan Mentor',                                                           'wajib' => 'required'],
                        ];
                        if ($jenisPelatihanData->kode_pelatihan == "LATSAR") {
                            $pendaftaranDocs = array_filter($pendaftaranDocs, fn($d) => !in_array($d['name'], ['file_pakta_integritas','file_surat_komitmen','file_surat_kelulusan_seleksi','file_surat_bebas_narkoba','file_surat_pernyataan_administrasi','file_persetujuan_mentor']));
                        }
                        if ($jenisPelatihanData->kode_pelatihan == "PKN_TK_II") {
                            $pendaftaranDocs = array_filter($pendaftaranDocs, fn($d) => !in_array($d['name'], ['file_surat_pernyataan_administrasi','file_persetujuan_mentor','file_surat_kesediaan']));
                        }
                        if (in_array($jenisPelatihanData->kode_pelatihan, ["PKA","PKP"])) {
                            $pendaftaranDocs = array_filter($pendaftaranDocs, fn($d) => !in_array($d['name'], ['file_surat_komitmen','file_sertifikat_penghargaan']));
                        }
                    @endphp

                    @foreach($pendaftaranDocs as $doc)
                        <div class="form-group">
                            <label class="form-label {{ $doc['wajib'] == 'required' ? 'required' : '' }}">{{ $doc['label'] }}</label>
                            <div class="form-file">
                                <input type="file" name="{{ $doc['name'] }}" id="{{ $doc['name'] }}"
                                    class="form-file-input @error($doc['name']) error @enderror" accept=".pdf">
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
                            @error($doc['name'])<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <div class="form-actions-left">
                    <a href="{{ route('dashboard') }}" class="btn btn-cancel">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <div class="form-progress">
                        <span class="progress-text">Progress:</span>
                        <div class="progress-bar-container">
                            <div class="progress-bar" id="formProgress" style="width:0%"></div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-submit" id="submitBtn">
                    <i class="fas fa-save"></i> Simpan Perubahan
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
        const hasErrorsOnLoad  = validationFailed;

        // ===== DATA INSTANSI — dari config/instansi.php via JS embed =====
        const DAFTAR_INSTANSI = @json(config('instansi'));

        // ===== INSTANSI SEARCHABLE SELECT =====
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

            if (!trigger || !hiddenInput) return;

            let selectedValue = hiddenInput.value || '';
            let isOpen = false;
            let debounceTimer = null;

            if (selectedValue) setTriggerSelected(selectedValue);

            trigger.addEventListener('click', function (e) {
                if (e.target.closest('#instansi_clear_btn')) return;
                isOpen ? closeDropdown() : openDropdown();
            });

            function openDropdown() {
                isOpen = true;
                dropdown.style.display = 'block';
                trigger.style.borderRadius = '10px 10px 0 0';
                trigger.style.borderColor  = 'var(--primary-color, #1a3a6c)';
                trigger.style.boxShadow    = '0 0 0 4px rgba(26,58,108,0.1)';
                if (chevron) chevron.style.transform = 'rotate(180deg)';
                renderList(DAFTAR_INSTANSI, '');
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
                if (countEl) countEl.textContent = 'Menampilkan ' + DAFTAR_INSTANSI.length + ' instansi';
            }

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

                searchInput.addEventListener('keydown', function (e) {
                    const items  = listEl.querySelectorAll('.instansi-item');
                    const active = listEl.querySelector('.instansi-item.iactive');
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
                if (items[idx]) { items[idx].classList.add('iactive'); items[idx].style.background = '#dbeafe'; items[idx].scrollIntoView({ block: 'nearest' }); }
            }

            function renderList(data, q) {
                if (!listEl) return;
                if (data.length === 0) {
                    listEl.innerHTML = `<div style="padding:20px;text-align:center;color:var(--gray-500);font-size:0.9rem;">
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
                    return `<div class="instansi-item${isSel ? ' iactive' : ''}" data-value="${escH(item)}"
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
                if (countEl) countEl.textContent = q ? (data.length + ' dari ' + DAFTAR_INSTANSI.length + ' instansi') : ('Menampilkan ' + data.length + ' instansi');
                const selEl = listEl.querySelector('.iactive');
                if (selEl) setTimeout(() => selEl.scrollIntoView({ block: 'nearest' }), 10);
                listEl.querySelectorAll('.instansi-item').forEach(el => {
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
                    selectedValue = ''; hiddenInput.value = '';
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

        // ===== NOTIFICATION SYSTEM =====
        class NotificationSystem {
            constructor() {
                this.container = document.getElementById('notificationContainer');
                this.notifications = new Map();
                this.nextId = 1;
            }
            show(type, title, message, duration = 5000) {
                const id = this.nextId++;
                const el = document.createElement('div');
                el.className = `notification notification-${type}`;
                el.id = `notification-${id}`;
                const icons = { success:'fa-check-circle', error:'fa-exclamation-circle', warning:'fa-exclamation-triangle', info:'fa-info-circle' };
                el.innerHTML = `<i class="fas ${icons[type]}"></i>
                    <div class="notification-content">
                        <div class="notification-title">${title}</div>
                        <div class="notification-message">${message}</div>
                    </div>
                    <button type="button" class="notification-close" onclick="notificationSystem.remove(${id})"><i class="fas fa-times"></i></button>`;
                this.container.appendChild(el);
                this.notifications.set(id, el);
                if (duration > 0) setTimeout(() => this.remove(id), duration);
                return id;
            }
            remove(id) {
                const el = this.notifications.get(id);
                if (el) { el.classList.add('hiding'); setTimeout(() => { if (el.parentNode) el.remove(); this.notifications.delete(id); }, 300); }
            }
            clearAll() { this.notifications.forEach((el, id) => this.remove(id)); }
        }
        const notificationSystem = new NotificationSystem();

        // ===== FORM VALIDATOR =====
        class FormValidator {
            constructor(form) { this.form = form; this.errorFields = new Set(); this.errorTabs = new Set(); }

            validateField(field) {
                let isValid = true, errorMessage = '';
                field.classList.remove('error');
                const errorElement = field.parentElement.querySelector('.error-message');
                if (errorElement) errorElement.remove();
                if (field.hasAttribute('required') && !field.value.trim()) { isValid = false; errorMessage = 'Field ini wajib diisi'; }
                if (field.type === 'email' && field.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(field.value)) { isValid = false; errorMessage = 'Format email tidak valid'; }
                if (field.name === 'nomor_hp' && field.value && !/^[0-9+\-\s()]{10,20}$/.test(field.value)) { isValid = false; errorMessage = 'Format nomor HP tidak valid'; }
                if (field.type === 'file' && field.files.length > 0 && field.files[0].size > 1024*1024) { isValid = false; errorMessage = 'Ukuran file maksimal 1MB'; }
                if (!isValid) {
                    field.classList.add('error');
                    this.errorFields.add(field);
                    const div = document.createElement('div');
                    div.className = 'error-message';
                    div.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${errorMessage}`;
                    field.parentElement.appendChild(div);
                    this.markErrorTab(field);
                } else { this.errorFields.delete(field); }
                this.updateErrorIndicators();
                return isValid;
            }

            markErrorTab(field) {
                const tabContent = field.closest('.form-tab-content');
                if (tabContent) {
                    const tabBtn = document.querySelector(`.form-tab[data-tab="${tabContent.id}"]`);
                    if (tabBtn) { tabBtn.classList.add('error'); this.errorTabs.add(tabBtn); }
                }
            }

            updateErrorIndicators() {
                document.querySelectorAll('.form-tab.error').forEach(t => { if (!this.errorTabs.has(t)) t.classList.remove('error'); });
                this.updateProgress();
            }

            updateProgress() {
                const fields = this.form.querySelectorAll('[required]');
                let filled = 0;
                fields.forEach(f => { if (f.value && f.value.trim()) filled++; });
                const bar = document.getElementById('formProgress');
                if (bar) bar.style.width = Math.min((filled / fields.length) * 100, 100) + '%';
            }

            validateForm() {
                let isValid = true;
                this.errorFields.clear(); this.errorTabs.clear();
                this.form.querySelectorAll('input, select, textarea').forEach(f => { if (!this.validateField(f)) isValid = false; });
                return isValid;
            }

            scrollToFirstError() {
                if (this.errorFields.size > 0) {
                    const first = Array.from(this.errorFields)[0];
                    const tabContent = first.closest('.form-tab-content');
                    if (tabContent) { const tabBtn = document.querySelector(`.form-tab[data-tab="${tabContent.id}"]`); if (tabBtn) tabBtn.click(); }
                    setTimeout(() => {
                        first.classList.add('error-field');
                        first.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        if (['INPUT','SELECT','TEXTAREA'].includes(first.tagName)) first.focus();
                        setTimeout(() => first.classList.remove('error-field'), 3000);
                    }, 300);
                    notificationSystem.show('error', 'Validasi Gagal', `Terdapat ${this.errorFields.size} kesalahan yang perlu diperbaiki.`, 5000);
                }
            }
        }

        // ===== CROPPER.JS =====
        let cropper = null;

        function initializeCropper(imageSrc) {
            const imageElement = document.getElementById('crop-image');
            if (!imageElement) return;
            imageElement.src = imageSrc;
            imageElement.onload = function () {
                if (cropper) cropper.destroy();
                cropper = new Cropper(imageElement, {
                    aspectRatio: 3/4, viewMode: 2, dragMode: 'move', autoCropArea: 1,
                    restore: false, guides: true, center: true, highlight: false,
                    cropBoxMovable: true, cropBoxResizable: true, toggleDragModeOnDblclick: false,
                    minCropBoxWidth: 100, minCropBoxHeight: 133,
                    ready: updatePreview, crop: updatePreview
                });
                setupCropControls();
            };
        }

        function updatePreview() {
            if (!cropper) return;
            const preview = document.getElementById('crop-preview');
            const canvas  = cropper.getCroppedCanvas({ width: 120, height: 160 });
            if (canvas && preview) { preview.innerHTML = ''; preview.appendChild(canvas); }
        }

        function setupCropControls() {
            document.getElementById('rotate-left')?.addEventListener('click',  () => cropper?.rotate(-90));
            document.getElementById('rotate-right')?.addEventListener('click', () => cropper?.rotate(90));
            document.getElementById('zoom-in')?.addEventListener('click',      () => cropper?.zoom(0.1));
            document.getElementById('zoom-out')?.addEventListener('click',     () => cropper?.zoom(-0.1));
            document.getElementById('reset-crop')?.addEventListener('click',   () => cropper?.reset());
        }

        function showCropModal(imageSrc) {
            const cropContainer = document.getElementById('crop-container');
            const formFile      = document.getElementById('form-file-pasfoto');
            if (formFile) formFile.style.display = 'none';
            if (cropContainer) { cropContainer.style.display = 'block'; initializeCropper(imageSrc); cropContainer.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
        }

        function hideCropModal() {
            const cropContainer = document.getElementById('crop-container');
            const formFile      = document.getElementById('form-file-pasfoto');
            if (cropContainer) { cropContainer.style.display = 'none'; if (cropper) { cropper.destroy(); cropper = null; } }
            if (formFile) formFile.style.display = 'block';
        }

        function saveCroppedImage() {
            if (!cropper) { notificationSystem.show('error', 'Error', 'Cropper tidak terinisialisasi.', 3000); return; }
            const canvas = cropper.getCroppedCanvas({ width: 354, height: 472 });
            if (!canvas) { notificationSystem.show('error', 'Error', 'Gagal mendapatkan gambar yang dicrop.', 3000); return; }
            const dataUrl     = canvas.toDataURL('image/jpeg', 0.95);
            const hiddenInput = document.getElementById('file_pas_foto_cropped');
            if (hiddenInput) {
                hiddenInput.value = dataUrl;
                updatePhotoPreview(dataUrl);
                hideCropModal();
                notificationSystem.show('success', 'Berhasil', 'Foto berhasil dicrop. Klik "Simpan Perubahan" untuk menyimpan.', 3000);
            }
        }

        function updatePhotoPreview(dataUrl) {
            const fileInfo = document.getElementById('file-pas-foto-info');
            if (!fileInfo) return;
            fileInfo.innerHTML = `<div class="file-info">
                <i class="fas fa-file-image"></i>
                <div class="file-info-content"><div class="file-name">Pas Foto (Hasil Crop)</div><div class="file-size">Siap disimpan</div></div>
                <div class="file-actions">
                    <button type="button" class="btn-change-file" id="crop-existing-photo"><i class="fas fa-crop-alt"></i> Crop Ulang</button>
                    <button type="button" class="btn-change-file" id="change-pas-foto"><i class="fas fa-exchange-alt"></i> Ganti</button>
                </div>
            </div>`;
            attachFileActionListeners();
        }

        function attachFileActionListeners() {
            document.getElementById('crop-existing-photo')?.addEventListener('click', function (e) {
                e.preventDefault();
                const hiddenInput = document.getElementById('file_pas_foto_cropped');
                if (hiddenInput && hiddenInput.value) showCropModal(hiddenInput.value);
                else document.getElementById('file_pas_foto_input').click();
            });
            document.getElementById('change-pas-foto')?.addEventListener('click', function (e) {
                e.preventDefault();
                document.getElementById('file_pas_foto_cropped').value = '';
                const fileInput = document.getElementById('file_pas_foto_input');
                if (fileInput) {
                    fileInput.value = '';
                    const fi = document.getElementById('file-pas-foto-info');
                    if (fi) fi.innerHTML = '<span class="no-file">Belum ada file diupload</span>';
                    const fl = document.getElementById('file-pas-foto-label');
                    if (fl) fl.style.display = 'flex';
                    fileInput.click();
                }
            });
        }

        function setupAutoCapitalization() {
            function capitalizeWords(s) { return s.replace(/\b\w/g, c => c.toUpperCase()); }
            document.querySelectorAll('.form-input.capitalize, .form-textarea.capitalize').forEach(el => {
                el.addEventListener('input',  function () { if (this.value) this.value = capitalizeWords(this.value); });
                el.addEventListener('change', function () { if (this.value) this.value = capitalizeWords(this.value); });
                if (el.value) el.value = capitalizeWords(el.value);
            });
            document.querySelectorAll('.form-input.lowercase').forEach(el => {
                el.addEventListener('input',  function () { if (this.value) this.value = this.value.toLowerCase(); });
                el.addEventListener('change', function () { if (this.value) this.value = this.value.toLowerCase(); });
                if (el.value) el.value = el.value.toLowerCase();
            });
            document.querySelectorAll('.form-input.uppercase').forEach(el => {
                el.addEventListener('input',  function () { if (this.value) this.value = this.value.toUpperCase(); });
                el.addEventListener('change', function () { if (this.value) this.value = this.value.toUpperCase(); });
                if (el.value) el.value = el.value.toUpperCase();
            });
        }

        function setupMentorSearch() {
            const searchInput      = document.getElementById('mentor-search');
            const mentorSelect     = document.getElementById('id_mentor');
            const loadingIndicator = document.getElementById('mentor-loading');
            const notFoundEl       = document.getElementById('mentor-not-found');
            const searchInfo       = document.getElementById('mentor-search-info');
            const searchStats      = document.getElementById('mentor-search-stats');
            let searchTimeout;
            if (!searchInput || !mentorSelect) return;
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                const val = this.value.trim();
                notFoundEl.style.display = 'none'; searchInfo.style.display = 'none';
                if (!val) { loadAllMentors(); return; }
                loadingIndicator.style.display = 'block';
                searchTimeout = setTimeout(() => loadMentors(val), 500);
            });
            async function loadMentors(term = '') {
                try {
                    let url = '{{ route("admin.dashboard.getMentors") }}';
                    if (term) url += '?search=' + encodeURIComponent(term);
                    const res    = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } });
                    const result = await res.json();
                    loadingIndicator.style.display = 'none';
                    if (result.success) {
                        mentorSelect.innerHTML = '<option value="">-- Pilih Mentor --</option>';
                        if (result.data.length > 0) {
                            result.data.forEach(m => {
                                const opt = document.createElement('option');
                                opt.value = m.id;
                                opt.textContent = `${m.nama_mentor} - ${m.nip_mentor || 'Tanpa NIP'} - ${m.jabatan_mentor}`;
                                Object.assign(opt.dataset, { nama: m.nama_mentor||'', nip: m.nip_mentor||'', jabatan: m.jabatan_mentor||'', rekening: m.nomor_rekening||'', npwp: m.npwp_mentor||'', nomorhp: m.nomor_hp_mentor||'', golongan: m.golongan||'', pangkat: m.pangkat||'' });
                                mentorSelect.appendChild(opt);
                            });
                            if (term) { searchInfo.style.display = 'block'; searchStats.textContent = `Ditemukan ${result.total} mentor yang sesuai dengan "${term}"`; }
                            notFoundEl.style.display = 'none';
                        } else if (term) {
                            notFoundEl.style.display = 'block';
                            notFoundEl.innerHTML = `<i class="fas fa-exclamation-circle"></i> Tidak ada mentor yang sesuai dengan "${term}"`;
                        }
                    } else { notFoundEl.style.display = 'block'; notFoundEl.innerHTML = '<i class="fas fa-exclamation-circle"></i> Gagal memuat data mentor'; }
                } catch (err) {
                    loadingIndicator.style.display = 'none';
                    notFoundEl.style.display = 'block';
                    notFoundEl.innerHTML = '<i class="fas fa-exclamation-circle"></i> Terjadi kesalahan saat memuat data';
                }
            }
            function loadAllMentors() { loadMentors(''); }
        }

        // ===== MAIN INIT =====
        document.addEventListener('DOMContentLoaded', function () {
            const editForm = document.getElementById('editForm');
            const submitBtn = document.getElementById('submitBtn');
            let formValidator;

            setupAutoCapitalization();
            attachFileActionListeners();

            // Mentor phone validation
            function validatePhoneNumber(input) {
                const ok = /^[0-9\-\+]+$/.test(input.value);
                if (input.value && !ok) {
                    input.classList.add('error');
                    if (!input.closest('.form-group').querySelector('.phone-format-error')) {
                        const msg = document.createElement('small');
                        msg.className = 'text-danger phone-format-error';
                        msg.innerHTML = '<i class="fas fa-exclamation-circle"></i> Format nomor telepon tidak valid.';
                        input.closest('.form-group').appendChild(msg);
                    }
                    return false;
                }
                input.classList.remove('error');
                input.closest('.form-group').querySelector('.phone-format-error')?.remove();
                return true;
            }

            document.getElementById('nomor_hp_mentor_baru')?.addEventListener('input', e => validatePhoneNumber(e.target));
            document.getElementById('nomor_hp_mentor_baru')?.addEventListener('blur',  e => validatePhoneNumber(e.target));

            const nipMentorInput = document.getElementById('nip_mentor_baru');
            if (nipMentorInput) {
                nipMentorInput.addEventListener('input', e => { e.target.value = e.target.value.replace(/[\s\.]/g, ''); });
                nipMentorInput.addEventListener('paste', e => { setTimeout(() => { e.target.value = e.target.value.replace(/[\s\.]/g, ''); }, 10); });
            }

            // Golongan mentor baru → Pangkat auto-fill
            const golonganMentorSelect = document.getElementById('golongan_mentor_baru');
            const pangkatMentorInput   = document.getElementById('pangkat_mentor_baru');
            const pangkatMentorMap = { 'II/a':'Pengatur Muda','II/b':'Pengatur Muda Tingkat I','II/c':'Pengatur','II/d':'Pengatur Tingkat I','III/a':'Penata Muda','III/b':'Penata Muda Tingkat I','III/c':'Penata','III/d':'Penata Tingkat I','IV/a':'Pembina','IV/b':'Pembina Tingkat I','IV/c':'Pembina Muda','IV/d':'Pembina Madya' };
            if (golonganMentorSelect && pangkatMentorInput) {
                golonganMentorSelect.addEventListener('change', function () { pangkatMentorInput.value = pangkatMentorMap[this.value] || ''; });
                if (golonganMentorSelect.value) golonganMentorSelect.dispatchEvent(new Event('change'));
            }

            // Form validator
            if (editForm) {
                formValidator = new FormValidator(editForm);
                editForm.querySelectorAll('input, select, textarea').forEach(input => {
                    input.addEventListener('blur',  function () { formValidator.validateField(this); });
                    input.addEventListener('input', function () { if (this.classList.contains('error')) formValidator.validateField(this); });
                });
                editForm.querySelectorAll('input[type="file"]').forEach(input => {
                    input.addEventListener('change', function () { formValidator.validateField(this); updateFileDisplay(this); });
                });
            }

            // Tab navigation
            const formTabs    = document.querySelectorAll('.form-tab');
            const tabContents = document.querySelectorAll('.form-tab-content');
            formTabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    const tabId = tab.getAttribute('data-tab');
                    formTabs.forEach(t => t.classList.remove('active'));
                    tabContents.forEach(c => c.classList.remove('active'));
                    tab.classList.add('active');
                    document.getElementById(tabId)?.classList.add('active');
                    document.querySelector('.edit-form').scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
            });

            // File upload display
            function updateFileDisplay(input) {
                const fileInfo = input.closest('.form-file')?.querySelector('.form-file-name');
                if (input.files && input.files[0] && fileInfo) {
                    const file    = input.files[0];
                    const ext     = file.name.split('.').pop().toLowerCase();
                    const icon    = ['jpg','jpeg','png'].includes(ext) ? 'fa-file-image' : (ext === 'pdf' ? 'fa-file-pdf' : 'fa-file');
                    fileInfo.innerHTML = `<div class="file-info">
                        <i class="fas ${icon}"></i>
                        <div class="file-info-content"><div class="file-name">${file.name}</div><div class="file-size">${(file.size/1024).toFixed(2)} KB</div></div>
                        <button type="button" class="btn-change-file" data-target="${input.name}"><i class="fas fa-exchange-alt"></i> Ganti</button>
                    </div>`;
                    fileInfo.querySelector('.btn-change-file')?.addEventListener('click', () => input.click());
                }
            }

            document.querySelectorAll('.btn-change-file').forEach(btn => {
                btn.addEventListener('click', function () {
                    const target = this.getAttribute('data-target');
                    document.querySelector(`input[name="${target}"]`)?.click();
                });
            });

            // Status perkawinan ↔ Nama pasangan
            const statusSelect = document.getElementById('status_perkawinan');
            const pasanganBox  = document.getElementById('nama-pasangan-container');
            const pasanganInput = document.getElementById('nama_pasangan');
            function togglePasangan() {
                if (!statusSelect || !pasanganBox || !pasanganInput) return;
                if (statusSelect.value === 'Menikah') {
                    pasanganBox.style.display = 'grid'; pasanganInput.disabled = false; pasanganInput.setAttribute('required','required');
                } else {
                    pasanganBox.style.display = 'none'; pasanganInput.disabled = true; pasanganInput.removeAttribute('required'); pasanganInput.value = '';
                }
            }
            statusSelect?.addEventListener('change', togglePasangan);
            togglePasangan();

            // Golongan ruang → Pangkat
            const golonganSelect  = document.getElementById('golongan_ruang');
            const pangkatInput    = document.getElementById('pangkat');
            const pangkatDesc     = document.getElementById('pangkat_description');
            const pangkatDescText = document.getElementById('pangkat_desc_text');
            const pangkatMap = { 'II/a':{p:'Pengatur Muda',d:'Golongan IIa - Pengatur Muda'},'II/b':{p:'Pengatur Muda Tingkat I',d:'Golongan IIb'},'II/c':{p:'Pengatur',d:'Golongan IIc - Pengatur'},'II/d':{p:'Pengatur Tingkat I',d:'Golongan IId'},'III/a':{p:'Penata Muda',d:'Golongan IIIa'},'III/b':{p:'Penata Muda Tingkat I',d:'Golongan IIIb'},'III/c':{p:'Penata',d:'Golongan IIIc - Penata'},'III/d':{p:'Penata Tingkat I',d:'Golongan IIId'},'IV/a':{p:'Pembina',d:'Golongan IVa - Pembina'},'IV/b':{p:'Pembina Tingkat I',d:'Golongan IVb'},'IV/c':{p:'Pembina Muda',d:'Golongan IVc - Pembina Muda'},'IV/d':{p:'Pembina Madya',d:'Golongan IVd - Pembina Madya'} };
            function updatePangkat() {
                if (!golonganSelect || !pangkatInput) return;
                const data = pangkatMap[golonganSelect.value];
                if (data) { pangkatInput.value = data.p; if (pangkatDescText) pangkatDescText.textContent = data.d; if (pangkatDesc) pangkatDesc.style.display = 'block'; }
                else { pangkatInput.value = ''; if (pangkatDesc) pangkatDesc.style.display = 'none'; }
            }
            golonganSelect?.addEventListener('change', updatePangkat);
            updatePangkat();

            // Provinsi → Kabupaten cascade
            const provinceSelect = document.getElementById('id_provinsi');
            const citySelect     = document.getElementById('id_kabupaten_kota');
            if (provinceSelect && citySelect) {
                provinceSelect.addEventListener('change', function () {
                    const pid = this.value;
                    if (!pid) { citySelect.innerHTML = '<option value="">Pilih Kabupaten/Kota (Pilih Provinsi Dahulu)</option>'; citySelect.disabled = true; return; }
                    citySelect.innerHTML = '<option value="">Memuat...</option>'; citySelect.disabled = true;
                    const filtered = (window.allKabupatenData || []).filter(k => k.province_id == pid);
                    citySelect.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
                    citySelect.disabled  = false;
                    filtered.forEach(k => { const o = document.createElement('option'); o.value = k.id; o.textContent = k.name; citySelect.appendChild(o); });
                    const cur = @json($kepegawaian && $kepegawaian->id_kabupaten_kota ? $kepegawaian->id_kabupaten_kota : null);
                    if (cur) citySelect.value = cur;
                    if (window.oldValues?.id_kabupaten_kota) citySelect.value = window.oldValues.id_kabupaten_kota;
                });
                if (provinceSelect.value) setTimeout(() => provinceSelect.dispatchEvent(new Event('change')), 300);
            }

            // Mentor handling
            const sudahAdaMentor = document.getElementById('sudah_ada_mentor');
            const mentorContainer = document.getElementById('mentor-container');
            const mentorMode = document.getElementById('mentor_mode');
            const selectMentorForm = document.getElementById('select-mentor-form');
            const addMentorForm = document.getElementById('add-mentor-form');
            const mentorSelect = document.getElementById('id_mentor');

            sudahAdaMentor?.addEventListener('change', function () {
                mentorContainer.style.display = this.value === 'Ya' ? 'block' : 'none';
                if (this.value !== 'Ya') { if (mentorMode) mentorMode.value = ''; if (selectMentorForm) selectMentorForm.style.display='none'; if (addMentorForm) addMentorForm.style.display='none'; }
            });

            if (mentorMode && selectMentorForm && addMentorForm) {
                mentorMode.addEventListener('change', function () {
                    selectMentorForm.style.display = this.value === 'pilih' ? 'block' : 'none';
                    addMentorForm.style.display    = this.value === 'tambah' ? 'block' : 'none';
                    if (this.value === 'pilih') setTimeout(() => setupMentorSearch(), 100);
                });
                if (mentorMode.value === 'pilih') setTimeout(() => setupMentorSearch(), 200);
            }

            if (mentorSelect) {
                mentorSelect.addEventListener('change', function () {
                    const o = this.options[this.selectedIndex];
                    if (o.dataset.nama) {
                        document.getElementById('nama_mentor_select').value     = o.dataset.nama    || '';
                        document.getElementById('nip_mentor_select').value      = o.dataset.nip     || '';
                        document.getElementById('jabatan_mentor_select').value  = o.dataset.jabatan || '';
                        document.getElementById('nomor_rekening_mentor_select').value = o.dataset.rekening || '';
                        document.getElementById('npwp_mentor_select').value     = o.dataset.npwp    || '';
                        document.getElementById('nomor_hp_mentor_select').value = o.dataset.nomorhp || '';
                        document.getElementById('golongan_mentor_select').value = o.dataset.golongan || '';
                        document.getElementById('pangkat_mentor_select').value  = o.dataset.pangkat  || '';
                    } else {
                        ['nama_mentor_select','nip_mentor_select','jabatan_mentor_select','nomor_rekening_mentor_select','npwp_mentor_select','nomor_hp_mentor_select','golongan_mentor_select','pangkat_mentor_select']
                            .forEach(id => { const el = document.getElementById(id); if (el) el.value = ''; });
                    }
                });
                if (mentorSelect.value) mentorSelect.dispatchEvent(new Event('change'));
            }

            // Cropper event listeners
            document.getElementById('file_pas_foto_input')?.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (!file) return;
                if (!['image/jpeg','image/jpg','image/png'].includes(file.type)) { notificationSystem.show('error','Format Tidak Valid','Hanya JPG, JPEG, PNG.', 5000); this.value=''; return; }
                if (file.size > 1024*1024) { notificationSystem.show('error','File Terlalu Besar','Maksimal 1MB.', 5000); this.value=''; return; }
                const reader = new FileReader();
                reader.onload = e => { showCropModal(e.target.result); const fl = document.getElementById('file-pas-foto-label'); if (fl) fl.style.display='none'; };
                reader.readAsDataURL(file);
            });
            document.getElementById('close-crop')?.addEventListener('click',  hideCropModal);
            document.getElementById('cancel-crop')?.addEventListener('click', hideCropModal);
            document.getElementById('save-crop')?.addEventListener('click',   saveCroppedImage);

            // Form submission
            if (editForm) {
                editForm.onsubmit = function (e) {
                    e.preventDefault();
                    if (!formValidator.validateForm()) { formValidator.scrollToFirstError(); return false; }
                    const hiddenInput = document.getElementById('file_pas_foto_cropped');
                    if (hiddenInput && hiddenInput.value && hiddenInput.value.startsWith('data:')) {
                        fetch(hiddenInput.value).then(r => r.blob()).then(blob => {
                            const formData = new FormData(editForm);
                            formData.delete('file_pas_foto_cropped');
                            formData.append('file_pas_foto', new File([blob], 'pas_foto_3x4.jpg', { type:'image/jpeg', lastModified: Date.now() }));
                            formData.delete('file_pas_foto_input');
                            submitFormWithData(formData);
                        }).catch(() => { notificationSystem.show('error','Error','Gagal memproses foto.',5000); submitBtn.disabled=false; submitBtn.innerHTML='<i class="fas fa-save"></i> Simpan Perubahan'; });
                        return false;
                    }
                    submitBtn.disabled = true; submitBtn.innerHTML = '<span class="spinner"></span> Menyimpan...';
                    notificationSystem.show('info','Menyimpan Data','Mohon tunggu...',3000);
                    setTimeout(() => editForm.submit(), 500);
                };
            }

            // Auto-scroll to errors on load
            if (hasErrorsOnLoad && formValidator) {
                setTimeout(() => {
                    editForm.querySelectorAll('.error').forEach(f => formValidator.markErrorTab(f));
                    formValidator.scrollToFirstError();
                }, 1000);
            }

            if (formValidator) formValidator.updateProgress();

            // Error summary click handler
            window.scrollToFieldError = function (errorText) {
                const fields = editForm.querySelectorAll('input, select, textarea');
                let target = null;
                fields.forEach(f => { if (f.name && errorText.toLowerCase().includes(f.name.replace('_',' ').toLowerCase())) target = f; });
                if (target) {
                    const tabContent = target.closest('.form-tab-content');
                    if (tabContent) document.querySelector(`.form-tab[data-tab="${tabContent.id}"]`)?.click();
                    setTimeout(() => { target.classList.add('error-field'); target.scrollIntoView({ behavior:'smooth', block:'center' }); target.focus(); setTimeout(() => target.classList.remove('error-field'), 3000); }, 300);
                }
            };

            // Auto-dismiss alerts
            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(a => {
                    a.style.transition = 'opacity 0.5s ease, transform 0.5s ease'; a.style.opacity='0'; a.style.transform='translateY(-10px)';
                    setTimeout(() => a.remove(), 500);
                });
            }, 5000);

            // Warn before unload
            let formChanged = false;
            editForm.querySelectorAll('input, select, textarea').forEach(i => i.addEventListener('change', () => formChanged = true));
            window.addEventListener('beforeunload', e => { if (formChanged && !editForm.classList.contains('submitted')) { e.preventDefault(); e.returnValue=''; } });
            editForm.addEventListener('submit', () => editForm.classList.add('submitted'));
        });

        // Submit with FormData
        function submitFormWithData(formData) {
            const submitBtn = document.getElementById('submitBtn');
            if (!submitBtn) return;
            submitBtn.disabled = true; submitBtn.innerHTML = '<span class="spinner"></span> Menyimpan...';
            notificationSystem.show('info','Menyimpan Data','Mohon tunggu...',3000);
            fetch(editForm.action, {
                method: 'POST', body: formData,
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
            })
            .then(response => {
                if (response.redirected) { window.location.href = response.url; return; }
                return response.ok ? response.json() : response.json().then(err => { throw err; });
            })
            .then(data => {
                if (!data) return;
                if (data.redirect) { window.location.href = data.redirect; }
                else if (data.message) {
                    notificationSystem.show('success','Berhasil!', data.message, 5000);
                    submitBtn.disabled = false; submitBtn.innerHTML = '<i class="fas fa-save"></i> Simpan Perubahan';
                }
            })
            .catch(err => {
                console.error('Error:', err);
                notificationSystem.show('error','Gagal Menyimpan', err.message || 'Terjadi kesalahan.', 5000);
                submitBtn.disabled = false; submitBtn.innerHTML = '<i class="fas fa-save"></i> Simpan Perubahan';
            });
        }
    </script>
@endsection