@extends('layouts.dashboard')

@section('title', 'Administration')
@section('dashboard-title', 'Panneau d\'Administration')

@section('sidebar-menu')
    <li>
        <a href="{{ route('admin.dashboard') }}" class="sidebar-item flex items-center p-3 text-blue-100 hover:text-white {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt mr-3"></i>
            Tableau de bord
        </a>
    </li>
    <li>
        <a href="{{ route('admin.requests.index') }}" class="sidebar-item flex items-center p-3 text-blue-100 hover:text-white {{ request()->routeIs('admin.requests.*') ? 'active' : '' }}">
            <i class="fas fa-file-alt mr-3"></i>
            Gestion des Demandes
        </a>
    </li>
    <li>
        <a href="{{ route('admin.users.index') }}" class="sidebar-item flex items-center p-3 text-blue-100 hover:text-white {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="fas fa-users mr-3"></i>
            Gestion des Utilisateurs
        </a>
    </li>
    <li>
        <a href="{{ route('admin.agents.index') }}" class="sidebar-item flex items-center p-3 text-blue-100 hover:text-white {{ request()->routeIs('admin.agents.*') ? 'active' : '' }}">
            <i class="fas fa-user-tie mr-3"></i>
            Gestion des Agents
        </a>
    </li>
    <li>
        <a href="{{ route('admin.notifications.index') }}" class="sidebar-item flex items-center p-3 text-blue-100 hover:text-white {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
            <i class="fas fa-bell mr-3"></i>
            Notifications
        </a>
    </li>
    <li>
        <a href="{{ route('admin.reports') }}" class="sidebar-item flex items-center p-3 text-blue-100 hover:text-white {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
            <i class="fas fa-chart-bar mr-3"></i>
            Rapports & Statistiques
        </a>
    </li>
    <li class="mt-8 pt-4 border-t border-blue-700">
        <a href="{{ route('admin.settings') }}" class="sidebar-item flex items-center p-3 text-blue-100 hover:text-white {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
            <i class="fas fa-cogs mr-3"></i>
            Paramètres Système
        </a>
    </li>
    <li>
        <a href="{{ route('admin.logs') }}" class="sidebar-item flex items-center p-3 text-blue-100 hover:text-white">
            <i class="fas fa-history mr-3"></i>
            Journaux d'Activité
        </a>
    </li>
@endsection

@section('dashboard-content')
@yield('admin-content')
@endsection
