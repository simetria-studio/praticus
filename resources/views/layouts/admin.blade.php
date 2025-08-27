<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistema Escolar') }} - @yield('title', 'Dashboard')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    @stack('styles')

    <style>
        :root {
            --primary-color: #058D4F;
            --secondary-color: #C82222;
            --light-color: #FFF;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: var(--gray-50);
            color: var(--gray-800);
            line-height: 1.6;
        }

        /* Admin Panel Layout */
        .admin-panel {
            display: flex;
            min-height: 100vh;
        }

        /* Modern Sidebar */
        .modern-sidebar {
            width: 280px;
            background: linear-gradient(135deg, #058D4F 0%, #046a3f 100%);
            color: white;
            padding: 0;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 1000;
            transition: width 0.3s ease;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Collapsed Sidebar */
        .modern-sidebar.collapsed {
            width: 80px;
        }

        /* Hide text elements when collapsed */
        .modern-sidebar.collapsed .logo-text,
        .modern-sidebar.collapsed .profile-info,
        .modern-sidebar.collapsed .nav-text,
        .modern-sidebar.collapsed .logout-btn span {
            display: none;
        }

        .sidebar-logo {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: padding 0.3s ease;
        }

        .modern-sidebar.collapsed .sidebar-logo {
            padding: 1.5rem 1rem;
            text-align: center;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: justify-content 0.3s ease;
        }

        .modern-sidebar.collapsed .logo-container {
            justify-content: center;
            gap: 0;
        }

        .logo-img {
            width: 32px;
            height: 32px;
            flex-shrink: 0;
        }

        .logo-text {
            font-size: 1.25rem;
            font-weight: 700;
            color: white;
        }

        .profile-section {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .modern-sidebar.collapsed .profile-section {
            padding: 1rem;
            justify-content: center;
        }

        .profile-avatar {
            position: relative;
        }

        .avatar-circle {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .status-indicator {
            position: absolute;
            bottom: 2px;
            right: 2px;
            width: 12px;
            height: 12px;
            background: #10b981;
            border-radius: 50%;
            border: 2px solid var(--primary-color);
        }

        .profile-info {
            flex: 1;
        }

        .profile-name {
            font-weight: 600;
            font-size: 0.95rem;
            margin: 0;
        }

        .profile-role {
            font-size: 0.8rem;
            opacity: 0.8;
        }

        /* Navigation */
        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-section {
            padding: 0 1rem;
        }

        .modern-sidebar.collapsed .nav-section {
            padding: 0 0.5rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: 0.75rem;
            margin-bottom: 0.25rem;
            transition: all 0.2s ease;
            position: relative;
        }

        .modern-sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 1rem 0.5rem;
            gap: 0;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(4px);
        }

        .modern-sidebar.collapsed .nav-link:hover {
            transform: none;
            background: rgba(255, 255, 255, 0.15);
        }

        .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            font-weight: 600;
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 60%;
            background: white;
            border-radius: 0 2px 2px 0;
        }

        .modern-sidebar.collapsed .nav-link.active::before {
            display: none;
        }

        .nav-icon {
            width: 20px;
            display: flex;
            justify-content: center;
            flex-shrink: 0;
        }

        .nav-text {
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Simple tooltips for collapsed state */
        .modern-sidebar.collapsed .nav-link {
            position: relative;
        }

        .modern-sidebar.collapsed .nav-link:hover::after {
            content: attr(title);
            position: absolute;
            left: calc(100% + 10px);
            top: 50%;
            transform: translateY(-50%);
            background: #374151;
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            font-size: 0.875rem;
            white-space: nowrap;
            z-index: 1001;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .modern-sidebar.collapsed .nav-link:hover::before {
            content: '';
            position: absolute;
            left: calc(100% + 4px);
            top: 50%;
            transform: translateY(-50%);
            border: 4px solid transparent;
            border-right-color: #374151;
            z-index: 1001;
        }

        /* Sidebar Footer */
        .sidebar-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .modern-sidebar.collapsed .sidebar-footer {
            padding: 1rem 0.5rem;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            width: 100%;
            padding: 0.75rem 1rem;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 0.5rem;
            color: white;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .modern-sidebar.collapsed .logout-btn {
            justify-content: center;
            padding: 0.75rem 0.5rem;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Main Panel */
        .main-panel {
            flex: 1;
            margin-left: 280px;
            background: var(--gray-50);
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        /* Collapsed Main Panel */
        .main-panel.sidebar-collapsed {
            margin-left: 80px;
        }

        /* Panel Header */
        .panel-header {
            background: white;
            padding: 1rem 2rem;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .sidebar-toggle {
            display: block;
            background: none;
            border: none;
            font-size: 1.25rem;
            color: var(--gray-600);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.375rem;
            transition: all 0.2s ease;
        }

        .sidebar-toggle:hover {
            background: var(--gray-100);
            color: var(--primary-color);
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            color: var(--gray-600);
        }

        .breadcrumb-item.active {
            color: var(--primary-color);
            font-weight: 600;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-search {
            position: relative;
        }

        .header-search input {
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            border: 1px solid var(--gray-300);
            border-radius: 0.5rem;
            background: var(--gray-50);
            font-size: 0.9rem;
            width: 300px;
        }

        .header-search i {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
        }

        .notification-btn {
            position: relative;
            background: none;
            border: none;
            font-size: 1.25rem;
            color: var(--gray-600);
            cursor: pointer;
        }

        .notification-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background: var(--secondary-color);
            color: white;
            font-size: 0.7rem;
            padding: 2px 6px;
            border-radius: 10px;
            min-width: 18px;
            text-align: center;
        }

        .profile-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: none;
            border: none;
            padding: 0.5rem;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .profile-btn:hover {
            background: var(--gray-100);
        }

        .profile-img {
            font-size: 1.5rem;
            color: var(--gray-600);
        }

        /* Dropdown Styles */
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
            padding: 0.5rem 0;
            min-width: 280px;
            margin-top: 0.5rem;
        }

        .dropdown-header {
            padding: 1rem 1.5rem 0.5rem;
            background: linear-gradient(135deg, #058D4F 0%, #046a3f 100%);
            color: white;
            border-radius: 12px 12px 0 0;
            margin: -0.5rem 0 0.5rem;
        }

        .dropdown-header .profile-img {
            font-size: 2rem;
            color: white;
        }

        .dropdown-item {
            padding: 0.75rem 1.5rem;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            border: none;
            background: none;
            display: flex;
            align-items: center;
        }

        .dropdown-item:hover {
            background: #f8f9fa;
            padding-left: 2rem;
            transform: translateX(5px);
        }

        .dropdown-item.text-danger:hover {
            background: #fee;
            color: #dc3545 !important;
        }

        .dropdown-item i {
            width: 20px;
            text-align: center;
        }

        .dropdown-divider {
            margin: 0.5rem 0;
            opacity: 0.2;
        }

        /* Panel Content */
        .panel-content {
            padding: 2rem;
        }

        /* Custom Styles */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #003285;
            border-color: #003285;
        }

        .text-primary-custom {
            color: var(--primary-color) !important;
        }

        .bg-primary-custom {
            background-color: var(--primary-color) !important;
        }

        .card {
            border: none;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: var(--primary-color);
            color: white;
            border-bottom: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .modern-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .modern-sidebar.active {
                transform: translateX(0);
            }

            .modern-sidebar.collapsed {
                width: 280px; /* Full width on mobile when active */
            }

            .main-panel {
                margin-left: 0;
            }

            .main-panel.sidebar-collapsed {
                margin-left: 0;
            }

            .header-search {
                display: none;
            }

            .panel-content {
                padding: 1rem;
            }
        }

        /* Print Styles - Global para todas as páginas admin */
        @media print {
            /* Ocultar elementos da interface administrativa */
            .modern-sidebar,
            .panel-header,
            .sidebar-toggle,
            .breadcrumb,
            .btn:not(.no-print-hide),
            .dropdown-toggle,
            .header-left,
            .header-right,
            .header-search,
            .header-notifications,
            .header-profile {
                display: none !important;
            }

            /* Ajustar layout principal para impressão */
            .admin-panel {
                display: block !important;
            }

            .main-panel {
                margin-left: 0 !important;
                width: 100% !important;
            }

            .panel-content {
                padding: 0 !important;
            }

            .container-fluid {
                padding: 0 !important;
                margin: 0 !important;
            }

            /* Garantir que o conteúdo principal seja visível */
            .card-body,
            .card-header h4,
            .text-center,
            .table,
            .row,
            .col-12,
            .col-md-8,
            .col-md-4,
            .col-md-6,
            .col-md-3 {
                display: block !important;
                visibility: visible !important;
            }

            /* Ajustar página */
            @page {
                margin: 1.5cm 1cm 1.5cm 1cm;
                size: A4;
            }

            body {
                font-size: 12px !important;
                line-height: 1.3 !important;
                color: #000 !important;
                background: white !important;
            }

            /* Cards e elementos básicos */
            .card {
                border: 1px solid #000 !important;
                box-shadow: none !important;
                page-break-inside: avoid;
                margin-bottom: 15px !important;
                display: block !important;
                visibility: visible !important;
            }

            .card-header {
                background-color: #f8f9fa !important;
                color: #000 !important;
                border-bottom: 1px solid #000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                display: block !important;
                visibility: visible !important;
            }

            /* Ocultar apenas botões específicos no card-header */
            .card-header .btn,
            .card-header .dropdown-toggle,
            .card-header .badge {
                display: none !important;
            }

            /* Mas manter o título visível */
            .card-header h4,
            .card-header h5,
            .card-header h6 {
                display: block !important;
                visibility: visible !important;
                color: #000 !important;
            }

            /* Tabelas */
            .table {
                font-size: 11px !important;
                border-collapse: collapse !important;
                display: table !important;
                visibility: visible !important;
            }

            .table th,
            .table td {
                border: 1px solid #000 !important;
                padding: 4px 6px !important;
                display: table-cell !important;
                visibility: visible !important;
            }

            .table th {
                background-color: #f0f0f0 !important;
                font-weight: bold !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            /* Normalizar cores para impressão */
            .text-primary,
            .text-success,
            .text-danger,
            .text-warning,
            .text-info,
            .text-secondary {
                color: #000 !important;
            }

            .badge {
                border: 1px solid #000 !important;
                color: #000 !important;
                background-color: #f8f9fa !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            h1, h2, h3, h4, h5, h6 {
                color: #000 !important;
                page-break-after: avoid !important;
                display: block !important;
                visibility: visible !important;
            }

            /* Garantir que elementos importantes sejam visíveis */
            p, div, span, strong, small {
                display: block !important;
                visibility: visible !important;
            }

            /* Ajustar spans inline */
            span, small, strong, em {
                display: inline !important;
            }

            /* Evitar quebras de página em elementos importantes */
            .card,
            .table,
            .form-group,
            .alert {
                page-break-inside: avoid !important;
            }

            /* Forçar primeira página a ter conteúdo */
            .card:first-child {
                margin-top: 0 !important;
                page-break-before: avoid !important;
            }
        }

        /* Estado durante impressão */
        .printing .modern-sidebar,
        .printing .panel-header,
        .printing .breadcrumb {
            display: none !important;
        }

        .printing .main-panel {
            margin-left: 0 !important;
            width: 100% !important;
        }

        .printing .card-header .btn {
            display: none !important;
        }
    </style>
</head>
<body>
    <div class="admin-panel">
        <!-- Modern Sidebar -->
        <div class="modern-sidebar">
            <!-- Logo Section -->
            <div class="sidebar-logo">
                <div class="logo-container">
                    <img src="{{ asset('img/logo.jpg') }}" alt="Logo" class="logo-img">
                    <span class="logo-text">Certificado Escolar</span>
                </div>
            </div>

            <!-- User Profile -->
            <div class="profile-section">
                <div class="profile-avatar">
                    <div class="avatar-circle">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="status-indicator"></div>
                </div>
                <div class="profile-info">
                    <h6 class="profile-name">{{ Auth::user()->name }}</h6>
                    <span class="profile-role">
                        @switch(Auth::user()->role)
                            @case('admin')
                                Administrador
                                @break
                            @case('manager')
                                Gerente
                                @break
                            @case('operator')
                                Operador
                                @break
                            @case('coordinator')
                                Coordenador
                                @break
                            @default
                                {{ ucfirst(Auth::user()->role) }}
                        @endswitch
                    </span>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-tachometer-alt"></i>
                        </div>
                        <span class="nav-text">Dashboard</span>
                    </a>

                    <a href="{{ route('students.index') }}" class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}" title="Estudantes">
                        <div class="nav-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <span class="nav-text">Estudantes</span>
                    </a>

                    <a href="{{ route('teachers.index') }}" class="nav-link {{ request()->routeIs('teachers.*') ? 'active' : '' }}" title="Professores">
                        <div class="nav-icon">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <span class="nav-text">Professores</span>
                    </a>

                    <a href="{{ route('classes.index') }}" class="nav-link {{ request()->routeIs('classes.*') ? 'active' : '' }}" title="Turmas">
                        <div class="nav-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <span class="nav-text">Turmas</span>
                    </a>

                    @unless(Auth::user()->role === 'coordinator')
                    <a href="{{ route('coordinators.index') }}" class="nav-link {{ request()->routeIs('coordinators.*') ? 'active' : '' }}" title="Coordenadores">
                        <div class="nav-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <span class="nav-text">Coordenadores</span>
                    </a>
                    @endunless

                    @unless(Auth::user()->role === 'coordinator')
                    <a href="{{ route('schools.index') }}" class="nav-link {{ request()->routeIs('schools.*') ? 'active' : '' }}" title="Escolas">
                        <div class="nav-icon">
                            <i class="fas fa-school"></i>
                        </div>
                        <span class="nav-text">Escolas</span>
                    </a>
                    @endunless

                    <a href="{{ route('notes.index') }}" class="nav-link {{ request()->routeIs('notes.*') ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <span class="nav-text">Notas</span>
                    </a>

                    <a href="{{ route('historical-reports.index') }}" class="nav-link {{ request()->routeIs('historical-reports.*') ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-scroll"></i>
                        </div>
                        <span class="nav-text">Histórico Escolar</span>
                    </a>

                    <a href="{{ route('certificates.index') }}" class="nav-link {{ request()->routeIs('certificates.*') ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-certificate"></i>
                        </div>
                        <span class="nav-text">Certificados</span>
                    </a>
                </div>
            </nav>

            <!-- Sidebar Footer -->
            <div class="sidebar-footer">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Sair</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Panel -->
        <div class="main-panel">
            <!-- Top Header -->
            <div class="panel-header">
                <div class="header-left">
                    <button class="sidebar-toggle" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="breadcrumb">
                        @yield('breadcrumb')
                    </div>
                </div>

                <div class="header-right">
                    <div class="header-search">
                        <input type="text" placeholder="Buscar...">
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="header-notifications">
                        <button class="notification-btn">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge">3</span>
                        </button>
                    </div>
                    <div class="header-profile dropdown">
                        <button class="profile-btn dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="profile-img">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <span>{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                            <li>
                                <div class="dropdown-header">
                                    <div class="d-flex align-items-center">
                                        <div class="profile-img me-2">
                                            <i class="fas fa-user-circle"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ Auth::user()->name }}</div>
                                            <small class="text-muted">
                                                @switch(Auth::user()->role)
                                                    @case('admin')
                                                        Administrador
                                                        @break
                                                    @case('manager')
                                                        Gerente
                                                        @break
                                                    @case('operator')
                                                        Operador
                                                        @break
                                                    @case('coordinator')
                                                        Coordenador
                                                        @break
                                                    @default
                                                        {{ ucfirst(Auth::user()->role) }}
                                                @endswitch
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('users.edit', Auth::id()) }}">
                                    <i class="fas fa-user-edit me-2"></i>
                                    Editar Perfil
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('users.show', Auth::id()) }}">
                                    <i class="fas fa-eye me-2"></i>
                                    Ver Perfil
                                </a>
                            </li>
                            @if(Auth::user()->isAdmin())
                            <li>
                                <a class="dropdown-item" href="{{ route('system.dashboard') }}">
                                    <i class="fas fa-cogs me-2"></i>
                                    Administração
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('users.index') }}">
                                    <i class="fas fa-users me-2"></i>
                                    Gerenciar Usuários
                                </a>
                            </li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i>
                                        Sair
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Panel Content -->
            <div class="panel-content">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Sidebar collapse functionality
        function toggleSidebar() {
            const sidebar = document.querySelector('.modern-sidebar');
            const mainPanel = document.querySelector('.main-panel');
            const toggleIcon = document.querySelector('.sidebar-toggle i');

            // For mobile, just toggle active class
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('active');
                return;
            }

            // For desktop, toggle collapsed state
            const isCollapsed = sidebar.classList.contains('collapsed');

            if (isCollapsed) {
                // Expand sidebar
                sidebar.classList.remove('collapsed');
                mainPanel.classList.remove('sidebar-collapsed');
                toggleIcon.className = 'fas fa-bars';
                localStorage.setItem('sidebarCollapsed', 'false');
            } else {
                // Collapse sidebar
                sidebar.classList.add('collapsed');
                mainPanel.classList.add('sidebar-collapsed');
                toggleIcon.className = 'fas fa-angle-right';
                localStorage.setItem('sidebarCollapsed', 'true');
            }
        }

        // Initialize sidebar state on page load
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.modern-sidebar');
            const mainPanel = document.querySelector('.main-panel');
            const toggleIcon = document.querySelector('.sidebar-toggle i');

            // Only apply saved state on desktop
            if (window.innerWidth > 768) {
                const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';

                if (isCollapsed) {
                    sidebar.classList.add('collapsed');
                    mainPanel.classList.add('sidebar-collapsed');
                    toggleIcon.className = 'fas fa-angle-right';
                } else {
                    toggleIcon.className = 'fas fa-bars';
                }
            }

            // Add tooltips to nav links
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                const navText = link.querySelector('.nav-text');
                if (navText) {
                    link.setAttribute('title', navText.textContent.trim());
                }
            });

            // Melhorar experiência de impressão
            setupPrintHandlers();
        });

        // Setup print event handlers
        function setupPrintHandlers() {
            window.addEventListener('beforeprint', function() {
                // Adicionar classe para estilização adicional durante impressão
                document.body.classList.add('printing');
            });

            window.addEventListener('afterprint', function() {
                // Remover classe após impressão
                document.body.classList.remove('printing');
            });

            // Interceptar cliques em botões de impressão para garantir CSS aplicado
            document.addEventListener('click', function(e) {
                if (e.target.onclick && e.target.onclick.toString().includes('window.print')) {
                    e.preventDefault();
                    // Aplicar classe imediatamente e imprimir
                    document.body.classList.add('printing');
                    setTimeout(() => {
                        window.print();
                    }, 50);
                }
            });
        }

        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.querySelector('.modern-sidebar');
            const mainPanel = document.querySelector('.main-panel');
            const toggleIcon = document.querySelector('.sidebar-toggle i');

            if (window.innerWidth <= 768) {
                // Mobile mode - remove collapsed classes
                sidebar.classList.remove('collapsed');
                mainPanel.classList.remove('sidebar-collapsed');
                toggleIcon.className = 'fas fa-bars';
            } else {
                // Desktop mode - restore saved state
                const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';

                if (isCollapsed) {
                    sidebar.classList.add('collapsed');
                    mainPanel.classList.add('sidebar-collapsed');
                    toggleIcon.className = 'fas fa-angle-right';
                } else {
                    sidebar.classList.remove('collapsed');
                    mainPanel.classList.remove('sidebar-collapsed');
                    toggleIcon.className = 'fas fa-bars';
                }

                // Remove mobile active class
                sidebar.classList.remove('active');
            }
        });

        // Auto-close sidebar on mobile when clicking outside
        document.addEventListener('click', function(e) {
            const sidebar = document.querySelector('.modern-sidebar');
            const toggle = document.querySelector('.sidebar-toggle');

            if (window.innerWidth <= 768 &&
                !sidebar.contains(e.target) &&
                !toggle.contains(e.target) &&
                sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
