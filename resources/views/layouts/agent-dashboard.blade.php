@extends('layouts.dashboard')

@section('title', 'Espace Agent')
@section('dashboard-title', 'Espace Agent')

@section('sidebar-menu')
    <li>
        <a href="{{ route('agent.dashboard') }}" class="sidebar-item flex items-center p-3 text-blue-100 hover:text-white {{ request()->routeIs('agent.dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt mr-3"></i>
            Tableau de bord
        </a>
    </li>
    <li>
        <a href="{{ route('agent.requests.index') }}" class="sidebar-item flex items-center p-3 text-blue-100 hover:text-white {{ request()->routeIs('agent.requests.*') ? 'active' : '' }}">
            <i class="fas fa-file-alt mr-3"></i>
            Demandes Assignées
        </a>
    </li>
    <li>
        <a href="{{ route('agent.requests.pending') }}" class="sidebar-item flex items-center p-3 text-blue-100 hover:text-white {{ request()->routeIs('agent.requests.pending') ? 'active' : '' }}">
            <i class="fas fa-clock mr-3"></i>
            En Attente de Traitement
            <span class="ml-auto bg-yellow-500 text-white text-xs px-2 py-1 rounded-full">{{ $pendingCount ?? 0 }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('agent.requests.in-progress') }}" class="sidebar-item flex items-center p-3 text-blue-100 hover:text-white {{ request()->routeIs('agent.requests.in-progress') ? 'active' : '' }}">
            <i class="fas fa-spinner mr-3"></i>
            En Cours de Traitement
        </a>
    </li>
    <li>
        <a href="{{ route('agent.documents.generator') }}" class="sidebar-item flex items-center p-3 text-blue-100 hover:text-white {{ request()->routeIs('agent.documents.*') ? 'active' : '' }}">
            <i class="fas fa-file-pdf mr-3"></i>
            Générateur de Documents
        </a>
    </li>
    <li>
        <a href="{{ route('agent.notifications.index') }}" class="sidebar-item flex items-center p-3 text-blue-100 hover:text-white {{ request()->routeIs('agent.notifications.*') ? 'active' : '' }}">
            <i class="fas fa-bell mr-3"></i>
            Mes Notifications
        </a>
    </li>
    <li class="mt-8 pt-4 border-t border-blue-700">
        <a href="{{ route('agent.reports') }}" class="sidebar-item flex items-center p-3 text-blue-100 hover:text-white">
            <i class="fas fa-chart-line mr-3"></i>
            Mes Statistiques
        </a>
    </li>
    <li>
        <a href="{{ route('agent.profile') }}" class="sidebar-item flex items-center p-3 text-blue-100 hover:text-white">
            <i class="fas fa-user mr-3"></i>
            Mon Profil Agent
        </a>
    </li>
@endsection

@section('dashboard-content')
@yield('agent-content')
@endsection
