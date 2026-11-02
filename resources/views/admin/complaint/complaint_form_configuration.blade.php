@extends('admin.datatable')
@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Remix: Unified Complaint System</title>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
            rel="stylesheet">
        <link rel="stylesheet" href="src/style.css">
        <style>
            :root {
                --bg-color: #f8fafc;
                --text-primary: #1e293b;
                --text-secondary: #64748b;
                --text-muted: #94a3b8;
                --btn-color: #25499c;
                --btn-background-color: #25499C3D;
                --primary-color: #5179d6;
                --primary-light-color: #e7edfb;
                --indigo-primary: #4f46e5;
                --indigo-hover: #4338ca;
                --indigo-light: #eef2ff;
                --border-color: #e2e8f0;
                --white: #ffffff;
                --emerald: #10b981;
                --emerald-light: #ecfdf5;
                --rose: #f43f5e;
                --mellow-red: #af1e1e;
                --cosmos: #f8d4d4;
                --linen: #fbeaea;
                --rose-light: #fff1f2;
                --amber: #f59e0b;
                --amber-light: #fffbeb;
                --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
                --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
                --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
                --radius-lg: 0.75rem;
                --radius-xl: 1rem;
                --radius-2xl: 1.5rem;
                --radius-3xl: 2rem;
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Inter', sans-serif;
                background-color: var(--bg-color);
                color: var(--text-primary);
                line-height: 1.5;
            }

            .container {
                max-width: 1152px;
                margin: 0 auto;
                padding: 0 1.5rem;
            }

            /* Header */
            .header-wrapper {
                position: fixed;
                top: 60;
                left: 0;
                right: 0;
                width: 100%;
                z-index: 1000;
            }
            .header {
                background: var(--white);
                border-bottom: 1px solid var(--border-color);
                position: sticky;
                top: 0;
                z-index: 100;
                box-shadow: var(--shadow-sm);
            }

            .header-content {
                height: 4rem;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .logo-section {
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }

            .logo-icon {
                background: var(--primary-color);
                padding: 0.5rem;
                border-radius: 0.5rem;
                color: var(--white);
                display: flex;
                box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.2);
            }

            .logo-text h1 {
                font-size: 1.125rem;
                font-weight: 700;
                color: #1e293b;
            }

            .breadcrumb {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                font-size: 0.75rem;
                color: var(--text-muted);
            }

            .header-actions {
                display: flex;
                align-items: center;
                gap: 1rem;
            }

            /* .btn-save {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                background: var(--primary-light-color);
                color: var(--btn-color);
                padding: 0.5rem 1.25rem;
                border-radius: 0.5rem;
                font-weight: 600;
                border: none;
                cursor: pointer;
                transition: all 0.2s;
                box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.1);
            }

            .btn-save:hover {
                background: var(--indigo-hover);
            } */

            /* Main Content */
            .main-layout {
                padding: 2rem 0;
            }

            /* Template Choice Chips */
            .template-selector {
                display: flex;
                flex-direction: column;
                gap: 1rem;
                margin-bottom: 2.5rem;
            }

            @media (min-width: 640px) {
                .template-selector {
                    flex-direction: row;
                }
            }

            .template-chip {
                flex: 1;
                cursor: pointer;
            }

            .chip-content {
                padding: 1.5rem;
                border-radius: 2rem;
                border: 2px solid var(--border-color);
                background: #f8fafc;
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
                gap: 1rem;
                transition: all 0.3s;
                box-shadow: var(--shadow-sm);
                opacity: 0.7;
            }

            .template-chip:hover .chip-content {
                opacity: 1;
            }

            .template-chip.active .chip-content {
                background: var(--white);
                /* border-color: var(--primary-color); */
                box-shadow: 0 20px 25px -5px rgba(79, 70, 229, 0.1);
                opacity: 1;
                transform: scale(1.02);
            }

            .chip-icon {
                padding: .5rem;
                border-radius: 0.5rem;
                background: #e2e8f0;
                color: var(--text-secondary);
                transition: all 0.3s;
            }

            .small-icon svg {
                width: 28px;
                height: 28px;
            }

            .template-chip.active .chip-icon {
                background: var(--primary-light-color);
                color: var(--primary-color);
            }

            .chip-info h3 {
                font-size: 1rem;
                font-weight: 600;
                color: var(--text-secondary);
            }

            .template-chip.active .chip-info h3 {
                color: var(--text-primary);
            }

            .chip-info p {
                font-size: 0.75rem;
                color: var(--text-muted);
                font-weight: 500;
                margin-top: 0.25rem;
            }

            .chip-status {
                font-size: 0.625rem;
                font-weight: 900;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                padding: 0.25rem 0.75rem;
                border-radius: 9999px;
            }

            .chip-status.active {
                background: var(--btn-background-color);
                color: var(--btn-color);
            }

            .chip-status.inactive {
                color: var(--text-muted);
            }

            /* Grid Layout */
            .grid-container {
                display: flex;
                grid-template-columns: 1fr;
                gap: 2rem;
                justify-content: center;
                align-items: center;
            }

            @media (min-width: 1024px) {
                .grid-container {
                    grid-template-columns: repeat(4, 1fr);
                }
            }

            /* Sidebar */
            .sidebar {
                grid-column: span 1;
            }

            .rules-card {
                background: #0f172a;
                padding: 1.5rem;
                border-radius: 2rem;
                color: var(--white);
                box-shadow: var(--shadow-lg);
                position: relative;
                overflow: hidden;
            }

            .rules-card h3 {
                font-size: 1.125rem;
                font-weight: 700;
                margin-bottom: 0.5rem;
            }

            .rules-list {
                list-style: none;
                font-size: 0.75rem;
                color: #94a3b8;
            }

            .rules-list li {
                display: flex;
                gap: 0.5rem;
                margin-bottom: 0.75rem;
            }

            .rules-list svg {
                color: var(--emerald);
                flex-shrink: 0;
            }

            /* Config Area */
            .config-area {
                /* grid-column: span 1; */
                width: 100%;
            }

            @media (min-width: 1024px) {
                .config-area {
                    /* grid-column: span 3; */
                    width: 85%;
                }
            }

            .template-view {
                display: none;
                background: var(--white);
                border-radius: 2rem;
                border: 1px solid var(--border-color);
                box-shadow: var(--shadow-sm);
                overflow: hidden;
            }

            .template-view.active {
                display: block;
            }

            .view-header {
                padding: 2rem;
                border-bottom: 1px solid #f1f5f9;
                background: #f8fafc;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .view-title h2 {
                font-size: 1.5rem;
                font-weight: 600;
                color: var(--text-primary);
            }

            .view-title p {
                font-size: 0.875rem;
                color: var(--text-secondary);
                margin-top: 0.25rem;
            }

            .mode-badge {
                padding: 0.5rem 1rem;
                border-radius: 1rem;
                border: 1px solid #dbeafe;
                background: #eff6ff;
                color: #1d4ed8;
                display: flex;
                align-items: center;
                gap: 0.5rem;
                font-size: 0.75rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }

            .view-content {
                padding: 2rem;
            }

            .section-group {
                margin-bottom: 3rem;
            }

            .section-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 1.5rem;
            }

            .section-info {
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }

            .section-icon {
                padding: 0.5rem;
                border-radius: 0.75rem;
                background: #f1f5f9;
                color: var(--text-secondary);
                display: flex;
            }

            .section-info h3 {
                font-size: 1.125rem;
                font-weight: 700;
                color: var(--text-primary);
            }

            .section-info .badge {
                font-size: 0.625rem;
                font-weight: 900;
                color: var(--text-muted);
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }

            /* Toggles */
            .toggle-btn {
                padding: 0.5rem 1rem;
                border-radius: 0.75rem;
                font-weight: 700;
                font-size: 0.75rem;
                display: flex;
                align-items: center;
                gap: 0.5rem;
                border: 1px solid var(--border-color);
                background: #f1f5f9;
                color: var(--text-secondary);
                cursor: pointer;
                transition: all 0.2s;
            }

            .toggle-btn.active {
                background: var(--emerald-light);
                color: #047857;
                border-color: #d1fae5;
            }

            /* Tables */
            .table-container {
                background: #f8fafc;
                border-radius: 1.5rem;
                border: 1px solid #f1f5f9;
                overflow: hidden;
            }

            .data-table {
                width: 100%;
                border-collapse: collapse;
                text-align: left;
            }

            .data-table th {
                padding: 1rem 1.5rem;
                font-size: 0.625rem;
                font-weight: 900;
                color: var(--text-muted);
                text-transform: uppercase;
                letter-spacing: 0.05em;
                border-bottom: 1px solid #f1f5f9;
            }

            .data-table td {
                padding: 1rem 1.5rem;
                border-bottom: 1px solid #f1f5f9;
            }

            .data-table tr:hover {
                background: var(--white);
            }

            .data-table td:has(.field-input) {
                position: relative;
            }

            .field-input {
                background: transparent;
                border: none;
                font-weight: 700;
                color: #334155;
                padding: 0.25rem;
                width: 100%;
                border-radius: 0.25rem;
                transition: all 0.2s;
            }

            .field-input:hover {
                background: #f1f5f9;
            }

            .field-input:focus {
                outline: none;
                background: #f1f5f9;
            }

            .field-meta {
                font-size: 0.625rem;
                color: var(--text-muted);
                font-weight: 500;
            }

            .visibility-icon {
                display: inline-flex;
                width: 1.25rem;
                height: 1.25rem;
                border-radius: 0.375rem;
                border: 2px solid var(--border-color);
                background: var(--white);
                cursor: pointer;
                transition: all 0.2s;
                align-items: center;
                justify-content: center;
                color: transparent;
            }

            .visibility-icon.active {
                background: var(--primary-color);
                border-color: var(--primary-color);
                color: var(--white);
            }

            .type-badge {
                padding: 0.375rem 0.75rem;
                border-radius: 0.75rem;
                font-size: 0.625rem;
                font-weight: 900;
                cursor: pointer;
                transition: all 0.2s;
                border: 1px solid transparent;
                background: var(--indigo-light);
                border-color: var(--border-color);
                color: #475569;
            }

            .mandatory-badge {
                padding: 0.375rem 0.75rem;
                border-radius: 0.75rem;
                font-size: 0.625rem;
                font-weight: 900;
                cursor: pointer;
                transition: all 0.2s;
                border: 1px solid transparent;
            }

            .mandatory-badge.required {
                background: var(--cosmos);
                color: var(--mellow-red);
                border-color: var(--linen);
            }

            .mandatory-badge.optional {
                background: #e2e8f0;
                color: #475569;
            }

            /* Table Section Specific */
            .table-section {
                padding-top: 2rem;
                border-top: 1px solid #f1f5f9;
            }

            .table-card {
                background: var(--white);
                border: 1px solid var(--border-color);
                border-radius: 2rem;
                overflow: hidden;
                box-shadow: var(--shadow-sm);
            }

            .table-card .data-table th {
                background: #f8fafc;
            }

            /* Utilities */
            .hidden {
                display: none;
            }

            .text-center {
                text-align: center;
            }

            /* View Button */
            .view-btn {
                background: transparent;
                border: 1px solid var(--border-color);
                color: var(--text-secondary);
                width: 32px;
                height: 32px;
                border-radius: 0.5rem;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.2s ease;
            }

            .view-btn:hover {
                background: var(--indigo-light);
                color: var(--primary-color);
                border-color: var(--primary-color);
            }

            /* Toothpick Message (Inline Alert) */
            .toothpick-message {
                position: absolute;
                top: -20px;
                left: 1rem;
                background: #1e293b;
                color: white;
                padding: 6px 12px;
                border-radius: 6px;
                font-size: 11px;
                font-weight: 500;
                white-space: nowrap;
                z-index: 100;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
                opacity: 0;
                transform: translateY(10px);
                transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
                pointer-events: none;
                display: flex;
                align-items: center;
                gap: 6px;
            }

            .toothpick-message.active {
                opacity: 1;
                transform: translateY(0);
                top: -32px;
                pointer-events: auto;
            }

            .toothpick-close {
                margin-left: 4px;
                padding: 2px;
                border-radius: 4px;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                color: rgba(255, 255, 255, 0.4);
                transition: all 0.2s;
            }

            .toothpick-close:hover {
                background: rgba(255, 255, 255, 0.1);
                color: white;
            }

            .toothpick-message::after {
                content: '';
                position: absolute;
                bottom: -4px;
                left: 12px;
                width: 8px;
                height: 8px;
                background: #1e293b;
                transform: rotate(45deg);
            }

            .toothpick-message svg {
                color: var(--amber);
                flex-shrink: 0;
            }
            /* new model */
            .table thead th {
                vertical-align: middle;
            }
            .titleheading {
                font-size: 12px !important;
                text-transform: capitalize !important;
                position: sticky !important;
                position: relative !important;
                font-weight:bold !important;
            }
            .scrollable {
                height: 100%;
                overflow-x: scroll;
                overflow-y: scroll;
            }
        </style>
    </head>

    <body>
        <!-- Header -->
        <div class="header-wrapper">
            <header class="header">
                <div class="container header-content">
                    <div class="logo-section">
                        <div class="logo-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path
                                    d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </div>
                        <div class="logo-text">
                            <h1 style="margin: 0px">Complaint Form Configuration</h1>
                        </div>
                    </div>
                    <div class="header-actions">
                        <button type="button" class="btn-save btn btn-sm btn-alt-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                                <polyline points="17 21 17 13 7 13 7 21" />
                                <polyline points="7 3 7 8 15 8" />
                            </svg>
                            <span>Save Config</span>
                            <input type="hidden" name="template_config" id="template_config">
                            <input type="hidden" id="template_config_synced">
                        </button>
                    </div>
                </div>
            </header>
        </div>

        <main class="container main-layout">
            <!-- Template Choice Chips -->
            <div style="height: 60px"></div>
            <div id="flashMessageBox"></div>
            <div class="template-selector">
                <?php
                    $icons = [
                        1 => [
                            'title' => 'product',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="m7.5 4.27 9 5.15" />
                                <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z" />
                                <path d="m3.3 7 8.7 5 8.7-5" />
                                <path d="M12 22V12" />
                            </svg>'
                        ],
                        2 => [
                            'title' => 'project',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <rect width="20" height="14" x="2" y="7" rx="2" ry="2"/>
                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                            </svg>'
                        ]
                    ];
                ?>

                @foreach ($complaintTemplates as $complaintTemplate)
                    <div class="template-chip {{ $complaintTemplate->is_active ? 'active' : '' }}"
                        data-template-id="{{ $complaintTemplate->id }}"
                        data-template="{{ 'template' . $complaintTemplate->id }}">
                        <div class="chip-content">
                            <div style="display: flex; flex-direction:row; align-items: center; gap:8px;">
                                <div class="chip-icon small-icon">
                                    {!! $icons[$complaintTemplate->id]['icon'] ?? '' !!}
                                </div>
                                <div style="display: flex; align-items: center; justify-content:center;" class="chip-info">
                                    <h3 style="margin: 0px">{{ $complaintTemplate->title }}</h3>
                                </div>
                            </div>
                            <span
                                data-template-id="{{ $complaintTemplate->id }}"
                                data-template="{{ 'template' . $complaintTemplate->id }}"
                                class="chip-status {{ $complaintTemplate->is_active ? 'active' : 'inactive' }}">{{ $complaintTemplate->is_active ? 'Active System Template' : 'Set as Active' }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="grid-container">
                <!-- Config Area -->
                <div class="config-area">
                    @foreach ($complaintTemplates as $complaintTemplate)
                        <div id="{{ 'template' . $complaintTemplate->id . '-view' }}"
                            class="template-view {{ $complaintTemplate->is_active ? 'active' : 'inactive' }}">
                            <div class="view-header">
                                <div class="view-title">
                                    <h2>{{ $complaintTemplate->title }}</h2>
                                    <p>Configure visibility, mandatory rules, and display labels for this template.</p>
                                </div>
                            </div>

                            <div class="view-content">
                                @foreach ($complaintTemplate->complaintTemplateSections as $complaintTemplateSection)
                                    <div class="section-group">
                                        <div class="section-header">
                                            <div class="section-info">
                                                <div class="section-icon">
                                                </div>
                                                <div>
                                                    <h3 style="margin: 0">{{ $complaintTemplateSection->title }}</h3>
                                                </div>
                                            </div>
                                            {{-- @if ($complaintTemplateSection->show_subfield_question)
                                                <button type="button" class="toggle-btn section-toggle active">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="eye-on">
                                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                                        <circle cx="12" cy="12" r="3" />
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="eye-off" style="display: none;">
                                                        <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24" />
                                                        <path
                                                            d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68" />
                                                        <path
                                                            d="M6.61 6.61A13.52 13.52 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61" />
                                                        <line x1="2" y1="2" x2="22"
                                                            y2="22" />
                                                    </svg>
                                                    <span class="label-text">TABLE ENABLED</span>
                                                </button>
                                            @endif --}}
                                        </div>

                                    <div class="table-responsive">
                                        <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                                        <table class="table table-bordered table-striped table-vcenter">
                                            <thead>
                                                <tr>
                                                    <th style="width:1%;"></th>
                                                    <th class="text-center" style="width:30%;font-size:12px;text-transform:capitalize">Field Name</th>
                                                    <th class="text-center" style="width:15%;font-size:12px;text-transform:capitalize">Field Type</th>
                                                    <th class="text-center" style="width:40%;font-size:12px;text-transform:capitalize">Display Label</th>
                                                    <th class="text-center" style="width:9%;font-size:12px;text-transform:capitalize">Mandatory?</th>
                                                    <th class="text-center" style="width:5%;font-size:12px;text-transform:capitalize">View</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($complaintTemplateSection->complaintTemplateSectionFields as $complaintTemplateSectionField)
                                                    <tr>
                                                        <td style="font-size:12px;" class="text-center">
                                                            <div class="custom-control custom-checkbox d-inline-block"
                                                                @if(isset($complaintTemplateSectionField->lock_enable_field) && $complaintTemplateSectionField->lock_enable_field)
                                                                    data-toggle="tooltip"
                                                                    title="This is a system field and cannot be disabled."
                                                                @endif
                                                            >
                                                                <input
                                                                    {{ isset($complaintTemplateSectionField->lock_enable_field) && $complaintTemplateSectionField->lock_enable_field ? 'disabled' : ''}}
                                                                    data-template-id="{{ $complaintTemplate->id }}"
                                                                    data-template-section-id="{{ $complaintTemplateSection->id }}"
                                                                    data-template-section-field-id="{{ $complaintTemplateSectionField->id }}"
                                                                    type="checkbox"
                                                                    class="custom-control-input select-field"
                                                                    id="selectField{{$complaintTemplateSectionField->id}}"
                                                                    name="select_field[]"
                                                                    value="{{$complaintTemplateSectionField->enable_field}}"
                                                                    {{$complaintTemplateSectionField->enable_field ? 'checked' : ''}}>
                                                                <label class="custom-control-label" for="selectField{{$complaintTemplateSectionField->id}}"></label>
                                                            </div>
                                                        </td>
                                                        <td style="font-size:12px;" class="text-center">
                                                            <span>{{ $complaintTemplateSectionField->default_display_label }}</span>
                                                        </td>
                                                        <td style="font-size:12px;" class="text-center p-0">
                                                            <span>{{ ucfirst($complaintTemplateSectionField->field_type) }}</span>
                                                        </td>
                                                        <td style="font-size:12px;" class="text-center p-0">
                                                            <div class="col-sm-12 p-1">
                                                                <input
                                                                    data-template-id="{{ $complaintTemplate->id }}"
                                                                    data-template-section-id="{{ $complaintTemplateSection->id }}"
                                                                    data-template-section-field-id="{{ $complaintTemplateSectionField->id }}"
                                                                    required
                                                                    {{$complaintTemplateSectionField->enable_field ? '' : 'disabled'}}
                                                                    type="text"
                                                                    placeholder="Enter Display Label"
                                                                    class="form-control display-label"
                                                                    style="font-size: 12px;width:100%;"
                                                                    id="display-label-{{$complaintTemplateSectionField->id}}"
                                                                    data-old-value="{{$complaintTemplateSectionField->field_display_label}}"
                                                                    value="{{$complaintTemplateSectionField->field_display_label}}">
                                                            </div>
                                                            <div class="warning-message d-none" style="font-size:10px;">
                                                                <span class="text-danger" style="font-size:10px;">This change updates only the display name. Field purpose and existing data remain unchanged.</span>
                                                            </div>
                                                        </td>
                                                        <td style="font-size:12px;" class="text-center p-0">
                                                            <div class="custom-control custom-switch custom-control-success mb-1"
                                                                @if(isset($complaintTemplateSectionField->lock_is_mandatory) && $complaintTemplateSectionField->lock_is_mandatory)
                                                                    data-toggle="tooltip"
                                                                    title="This field is required and cannot be made optional."
                                                                @endif
                                                            >
                                                                <input
                                                                    {{ (isset($complaintTemplateSectionField->lock_is_mandatory) && $complaintTemplateSectionField->lock_is_mandatory) || !$complaintTemplateSectionField->enable_field  ? 'disabled' : ''}}
                                                                    data-template-id="{{ $complaintTemplate->id }}"
                                                                    data-template-section-id="{{ $complaintTemplateSection->id }}"
                                                                    data-template-section-field-id="{{ $complaintTemplateSectionField->id }}"
                                                                    type="checkbox"
                                                                    name="mandatory[]"
                                                                    id="mandatory-{{ $complaintTemplateSectionField->id }}"
                                                                    class="custom-control-input mandatory-field mandatory-toggle"
                                                                    value="{{$complaintTemplateSectionField->field_is_mandatory}}"
                                                                    {{$complaintTemplateSectionField->field_is_mandatory ? 'checked' : '' }} >
                                                                <label class="custom-control-label" for="mandatory-{{ $complaintTemplateSectionField->id }}"></label>
                                                            </div>
                                                        </td>
                                                        <td style="font-size:12px;" class="text-center p-0">
                                                            <button href="#"
                                                                data-template-id="{{ $complaintTemplate->id }}"
                                                                data-template-section-id="{{ $complaintTemplateSection->id }}"
                                                                data-template-section-field-id="{{ $complaintTemplateSectionField->id }}"
                                                                class="btn btn-sm btn-alt-primary view-history view-btn"
                                                                data-toggle="tooltip"
                                                                data-placement="top"
                                                                title="View Field History">
                                                                    <i class="fa fa-eye" style="font-size:11px;cursor:pointer"></i>
                                                        </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </main>

        <div id="viewHistory" class="modal fadebd-example-modal-lg scrollable" tabindex="-1" role="dialog" aria-hidden="false" >
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">History</h3>
                    </div>
                    <div class="table-responsive">
                        <div class="p-3 bg-light rounded border">
                            <table class="table table-sm table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th class="titleheading text-center" style="width: 5%;font-size:12px;;" data-orderable="false">Version</th>
                                        <th class="titleheading text-center" style="width: 30%;font-size:12px;;" data-orderable="false">Previous Dispaly Label</th>
                                        <th class="titleheading text-center" style="width: 30%;font-size:12px;;" data-orderable="false">Updated Display Label</th>
                                        <th class="titleheading text-center" style="width: 20%;font-size:12px;;" data-orderable="false">Modified By</th>
                                        <th class="titleheading text-center" style="width: 15%;font-size:12px;;" data-orderable="false">Modified On</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark btn-sm" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script>
            function isDisplayLabelChanged(config, synced) {
                for (let i = 0; i < config.length; i++) {
                    let configSections = config[i].complaint_template_sections || [];
                    let syncedSections = synced[i]?.complaint_template_sections || [];

                    for (let j = 0; j < configSections.length; j++) {
                        let configFields = configSections[j].complaint_template_section_fields || [];
                        let syncedFields = syncedSections[j]?.complaint_template_section_fields || [];

                        for (let k = 0; k < configFields.length; k++) {
                            let configLabel = configFields[k].field_display_label;
                            let syncedLabel = syncedFields[k]?.field_display_label;

                            if (configLabel !== syncedLabel) {
                                return true; // 🔥 change detected
                            }
                        }
                    }
                }

                return false;
            }

            function isMandatoryFieldChanged(config, synced) {
                for (let i = 0; i < config.length; i++) {
                    let configSections = config[i].complaint_template_sections || [];
                    let syncedSections = synced[i]?.complaint_template_sections || [];

                    for (let j = 0; j < configSections.length; j++) {
                        let configFields = configSections[j].complaint_template_section_fields || [];
                        let syncedFields = syncedSections[j]?.complaint_template_section_fields || [];

                        for (let k = 0; k < configFields.length; k++) {
                            let configMandatory = configFields[k].field_is_mandatory;
                            let syncedMandatory = syncedFields[k]?.field_is_mandatory;

                            if (configMandatory !== syncedMandatory) {
                                return true; // 🔥 change detected
                            }
                        }
                    }
                }

                return false;
            }

            function submitForm() {

            }
        </script>
        <script>
            $(document).ready(function() {
                // checking whether flash message stored
                let message = localStorage.getItem('flash_success');
                if (message) {
                    $('#flashMessageBox').html(
                        `<div class="alert alert-success">${message}</div>`
                    );
                    localStorage.removeItem('flash_success'); // 🧹 cleanup
                    // ⏳ Auto remove after 3 seconds
                    setTimeout(function() {
                        $('#flashMessageBox .alert').fadeOut(500, function() {
                            $(this).remove();
                        });
                    }, 3000);
                }
                //

                let config = @json($complaintTemplates);
                $('#template_config').val(JSON.stringify(config));
                $('#template_config_synced').val(JSON.stringify(config));

                $(document).on('click', 'a', function (e) {
                    let config = JSON.parse($('#template_config').val());
                    let synced = JSON.parse($('#template_config_synced').val());

                    if (JSON.stringify(config) !== JSON.stringify(synced)) {
                        if (isDisplayLabelChanged(config, synced) || isMandatoryFieldChanged(config, synced)) {
                            e.preventDefault();
                            let url = $(this).attr('href');
                            Swal.fire({
                                title: "Unsaved Changes",
                                text: "You have unsaved changes. Do you want to leave?",
                                icon: "warning",
                                showCancelButton: true,
                                confirmButtonText: "Leave",
                                cancelButtonText: "Stay"
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    isFormChanged = false;
                                    window.location.href = url;
                                }
                            });
                        }
                    }
                });

                history.pushState(null, null, location.href);

                window.addEventListener('popstate', function () {
                    let config = JSON.parse($('#template_config').val());
                    let synced = JSON.parse($('#template_config_synced').val());

                    if (JSON.stringify(config) !== JSON.stringify(synced)) {
                        Swal.fire({
                            title: "Unsaved Changes",
                            text: "You have unsaved changes. Go back?",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: "Leave",
                            cancelButtonText: "Stay"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                isFormChanged = false;
                                window.location.href = history.back();
                            } else {
                                history.pushState(null, null, location.href);
                            }
                        });
                    }
                });

                // Template Switching
                $('.template-chip').on('click', function() {
                    let config = JSON.parse($('#template_config').val());
                    const templateId = $(this).data('templateId');
                    const template = $(this).data('template');

                    config.forEach(function(item) {
                        if (item.id === templateId) {
                            item.is_active = 1;
                        } else {
                            item.is_active = 0;
                        }
                    });

                    $('#template_config').val(JSON.stringify(config));

                    // Update chips
                    $('.template-chip').removeClass('active');
                    $(this).addClass('active');


                    // Update chip status text
                    $('.template-chip .chip-status').text('Set as Active').removeClass('active').addClass(
                        'inactive');
                    $(this).find('.chip-status').text('Active System Template').removeClass('inactive')
                        .addClass('active');

                    // Switch views
                    $('.template-view').removeClass('active');
                    $(`#${template}-view`).addClass('active');
                });

                // Section Visibility Toggle
                $('.section-toggle').on('click', function() {
                    $(this).toggleClass('active');
                    const isActive = $(this).hasClass('active');

                    // Find the content to toggle
                    const $sectionGroup = $(this).closest('.section-group, .table-section');
                    const $content = $sectionGroup.find('.table-responsive, .table-card');

                    if (isActive) {
                        const currentText = $(this).find('.label-text').text();
                        if (currentText === 'SECTION HIDDEN') $(this).find('.label-text').text(
                            'SECTION VISIBLE');
                        if (currentText === 'TABLE DISABLED') $(this).find('.label-text').text('TABLE ENABLED');

                        $(this).find('.eye-on').show();
                        $(this).find('.eye-off').hide();
                        $content.show();
                    } else {
                        const currentText = $(this).find('.label-text').text();
                        if (currentText === 'SECTION VISIBLE') $(this).find('.label-text').text(
                            'SECTION HIDDEN');
                        if (currentText === 'TABLE ENABLED') $(this).find('.label-text').text('TABLE DISABLED');

                        $(this).find('.eye-on').hide();
                        $(this).find('.eye-off').show();
                        $content.hide();
                    }
                });

                // Field Visibility Toggle
                $('.select-field').on('click', function() {
                    let config = JSON.parse($('#template_config').val());
                    const templateId = $(this).data('templateId');
                    const templateSectionId = $(this).data('templateSectionId');
                    const templateSectionFieldId = $(this).data('templateSectionFieldId');

                    let value = $(this).is(':checked');

                    if(value) {
                        $('#display-label-'+templateSectionFieldId).prop('disabled', false);
                        $('#mandatory-'+templateSectionFieldId).prop('disabled', false);
                    } else {
                        $('#display-label-'+templateSectionFieldId).prop('disabled', true);
                        $('#mandatory-'+templateSectionFieldId).prop('disabled', true);
                        $('#mandatory-'+templateSectionFieldId).prop('checked', false);
                    }

                    config.forEach(function(item) {
                        if (item.id === templateId) {
                            item.complaint_template_sections.forEach(function(section) {
                                if (section.id === templateSectionId) {
                                    section.complaint_template_section_fields.forEach(function(
                                        field) {
                                        if (field.id === templateSectionFieldId) {
                                            field.enable_field = !field.enable_field;
                                            if (!value) {
                                                field.field_is_mandatory = false;
                                            }
                                        }
                                    });
                                }
                            });
                        }
                    });

                    $('#template_config').val(JSON.stringify(config));
                });

                // Mandatory Toggle
                $('.mandatory-toggle').on('click', function() {
                    let config = JSON.parse($('#template_config').val());
                    const templateId = $(this).data('templateId');
                    const templateSectionId = $(this).data('templateSectionId');
                    const templateSectionFieldId = $(this).data('templateSectionFieldId');

                    // $(this).toggleClass('required optional');
                    const isRequired = $(this).is(':checked') ? 1 : 0;

                    config.forEach(function(item) {
                        if (item.id === templateId) {
                            item.complaint_template_sections.forEach(function(section) {
                                if (section.id === templateSectionId) {
                                    section.complaint_template_section_fields.forEach(function(
                                        field) {
                                        if (field.id === templateSectionFieldId) {
                                            field.field_is_mandatory = isRequired;
                                        }
                                    });
                                }
                            });
                        }
                    });

                    $('#template_config').val(JSON.stringify(config));
                    console.log(config);

                    if (isRequired) {
                        $(this).text('REQUIRED');
                    } else {
                        $(this).text('OPTIONAL');
                    }
                });

                let editTimeout;
                // Field Input
                $('.display-label').on('input', function() {
                    let config = JSON.parse($('#template_config').val());
                    const templateId = $(this).data('templateId');
                    const templateSectionId = $(this).data('templateSectionId');
                    const templateSectionFieldId = $(this).data('templateSectionFieldId');
                    let value = $(this).val();

                    if(value.length > 0) {
                        $(this).css('border', "");
                    } else {
                        $(this).css('border', "1px solid red");
                    }

                    const $input = $(this);
                    const oldValue = (($input.data('old-value') || '').trim()).toLowerCase();
                    const currentValue = (($input.val() || '').trim()).toLowerCase();

                    const $warning = $input
                        .closest('td')
                        .find('.warning-message');

                    if (currentValue !== oldValue) {
                        $warning.removeClass('d-none');
                        fieldLabelChanged = true;
                    } else {
                        $warning.addClass('d-none');
                        fieldLabelChanged = false;
                    }

                    config.forEach(function(item) {
                        if (item.id === templateId) {
                            item.complaint_template_sections.forEach(function(section) {
                                if (section.id === templateSectionId) {
                                    section.complaint_template_section_fields.forEach(function(
                                        field) {
                                        if (field.id === templateSectionFieldId) {
                                            field.field_display_label = value;
                                        }
                                    });
                                }
                            });
                        }
                    });

                    $('#template_config').val(JSON.stringify(config));
                });

                // Save Button Alert
                $('.btn-save').on('click', async function() {
                    let btn = $(this);
                    let isValid = true;
                    let templateChangeChecked = true;
                    let fieldValidationChecked = true;

                    let config = JSON.parse($('#template_config').val());
                    let synced = JSON.parse($('#template_config_synced').val());

                    if (JSON.stringify(config) !== JSON.stringify(synced)) {
                        if(config[0].is_active !== synced[0].is_active){

                            let result = await Swal.fire({
                                title: `Are you sure you want to switch the template from ${config[0].is_active ? config[1].title : config[0].title} to ${config[0].is_active ? config[0].title : config[1].title}?`,
                                icon: 'question',
                                showCancelButton: true,
                                cancelButtonColor: '#6c757d',
                                confirmButtonText: 'Yes',
                                cancelButtonText: 'Cancel'
                            });

                            if(result.dismiss) {
                                templateChangeChecked = false;
                            }
                        }

                        if (await isDisplayLabelChanged(config, synced)) {
                            let result = await Swal.fire({
                                title: '<span style="font-size:20px;color:red;">Are you sure you want to update these fields?</span>',
                                html: `
                                    <ul class="mb-0 pl-4" style="font-size:13px; line-height:1.6; text-align:left;">
                                        <li><strong>New names</strong> apply only to newly created records</li>
                                        <li><strong>Existing records</strong> continue to display the old name in details and printed outputs</li>
                                        <li><strong>Register page and exports</strong> will show the updated field name</li>
                                        <li><strong>Functionality</strong> remains unchanged</li>
                                    </ul>
                                `,
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: '#0d6efd',
                                cancelButtonColor: '#6c757d',
                                confirmButtonText: 'Yes, Update it!',
                                cancelButtonText: 'Cancel'
                            });

                            if (result.dismiss) {
                                fieldValidationChecked = false;
                            }
                        }

                        if (templateChangeChecked && fieldValidationChecked) {
                            document.querySelectorAll(".display-label").forEach(function(input){

                                if(input.value.trim() === ""){
                                    isValid = false;
                                    input.style.border = "1px solid red";
                                }else{
                                    input.style.border = "";
                                }

                            });

                            if(!isValid){
                                alert("Please fill all field labels");
                                return;
                            }

                            let span = btn.find('span');

                            span.text('Saving...');
                            btn.prop('disabled', true);

                            $.ajax({
                                url: 'complaint-form-configuration',
                                type: 'POST',
                                data: {
                                    template_config: $('#template_config').val()
                                },
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(response) {
                                    console.log(response.message);
                                    console.log(response.data);
                                    localStorage.setItem('flash_success', "Template updated and changes saved.");
                                    $('#template_config').val(JSON.stringify(response.data));
                                    $('#template_config_synced').val(JSON.stringify(response.data));

                                    setTimeout(function() {
                                        window.location.reload();
                                    }, 1000);
                                },
                                error: function() {
                                    console.log(error)
                                    alert('Error')
                                    span.text('Save Config');
                                },
                                complete: function() {
                                    btn.prop('disabled', false);
                                }
                            });

                        }
                    }
                });

                function formatDate(dateString) {
                    let formatted = new Date(dateString).toLocaleString();
                    return formatted;
                }
                // Modal Logic
                $('.view-btn').on('click', function() {
                    let config = JSON.parse($('#template_config').val());
                    const templateId = $(this).data('templateId');
                    const templateSectionId = $(this).data('templateSectionId');
                    const templateSectionFieldId = $(this).data('templateSectionFieldId');

                    let history = [];

                    config.forEach(function(item) {
                        if (item.id === templateId) {
                            item.complaint_template_sections.forEach(function(section) {
                                if (section.id === templateSectionId) {
                                    section.complaint_template_section_fields.forEach(function(
                                        field) {
                                        if (field.id === templateSectionFieldId) {
                                            history = field
                                                .complaint_template_section_field_histories;
                                        }
                                    });
                                }
                            });
                        }
                    });
                    console.log(history);

                    const $row = $(this).closest('tr');
                    const fieldLabel = $row.find('.field-input').val();
                    const defaultLabel = $row.find('.field-meta').text().replace('Default: ', '');

                    let historyHtml = `
                                ${history.map((item, index) => `
                                            <tr class="text-center" style="font-size:12px;">
                                                <td class="text-center" style="padding-top: 13px !important;">${index+1}</td>
                                                <td class="text-center" style="padding-top: 13px !important;">${item.previous_display_label}</td>
                                                <td class="text-center" style="padding-top: 13px !important;">${item.updated_display_label}</td>
                                                <td class="text-center" style="padding-top: 13px !important;">${item.user ? item.user.name : ''}</td>
                                                <td class="text-center" style="padding-top: 13px !important;">${item.created_at ? formatDate(item.created_at) : ''}</td>
                                            </tr>
                                        `).join('')}
                                ${history.length == 0 ? `<tr><td colspan="5" class="text-center no-changes-made">No Changes Made</td></tr>` : ''}
                    `;

                    $("#viewHistory table tbody").html(historyHtml);
                    const modalEle = $(`#viewHistory`);

                    modalEle.modal('show');

                });

                $('.close-modal').on('click', function() {
                    $('#preview-modal').removeClass('active');
                });

                // Close modal on background click
                $('#preview-modal').on('click', function(e) {
                    if (e.target === this) {
                        $(this).removeClass('active');
                    }
                });
            });
        </script>
    </body>

    </html>
@endsection
