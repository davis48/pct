@extends('admin.special.layout')

@section('title', 'Informations Système')
@section('subtitle', 'État détaillé du serveur et de l\'infrastructure')

@section('content')
<div class="space-y-6">
    <!-- System Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-lg p-6 hover-lift">
            <div class="flex items-center">
                <div class="stats-gradient rounded-lg p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">CPU</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $systemInfo['cpu_usage'] ?? '0%' }}</p>
                    <p class="text-xs text-gray-500">{{ $systemInfo['cpu_cores'] ?? 'N/A' }} cœurs</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 hover-lift">
            <div class="flex items-center">
                <div class="success-gradient rounded-lg p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Mémoire</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $systemInfo['memory_usage'] ?? '0%' }}</p>
                    <p class="text-xs text-gray-500">{{ $systemInfo['memory_total'] ?? 'N/A' }} total</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 hover-lift">
            <div class="flex items-center">
                <div class="warning-gradient rounded-lg p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Stockage</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $systemInfo['disk_usage'] ?? '0%' }}</p>
                    <p class="text-xs text-gray-500">{{ $systemInfo['disk_free'] ?? 'N/A' }} libre</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 hover-lift">
            <div class="flex items-center">
                <div class="card-gradient rounded-lg p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Uptime</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $systemInfo['uptime'] ?? '0d' }}</p>
                    <p class="text-xs text-gray-500">depuis le redémarrage</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Server Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations Serveur</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-600">Système d'exploitation</span>
                    <span class="text-sm text-gray-900">{{ $serverInfo['os'] ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-600">Serveur Web</span>
                    <span class="text-sm text-gray-900">{{ $serverInfo['web_server'] ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-600">Version PHP</span>
                    <span class="text-sm text-gray-900">{{ $serverInfo['php_version'] ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-600">Version Laravel</span>
                    <span class="text-sm text-gray-900">{{ $serverInfo['laravel_version'] ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-600">Base de données</span>
                    <span class="text-sm text-gray-900">{{ $serverInfo['database'] ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-sm font-medium text-gray-600">Timezone</span>
                    <span class="text-sm text-gray-900">{{ $serverInfo['timezone'] ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">État des Services</h3>
            <div class="space-y-4">
                @foreach($services ?? [] as $service)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full mr-3 {{ $service['status'] === 'running' ? 'bg-green-500' : 'bg-red-500' }}"></div>
                        <span class="text-sm font-medium text-gray-700">{{ $service['name'] ?? 'Service' }}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-xs {{ $service['status'] === 'running' ? 'text-green-600' : 'text-red-600' }} font-medium">
                            {{ $service['status'] === 'running' ? 'En ligne' : 'Hors ligne' }}
                        </span>
                        @if(isset($service['response_time']))
                        <span class="text-xs text-gray-500">{{ $service['response_time'] }}ms</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Métriques de Performance</h3>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- CPU Usage Chart -->
            <div>
                <h4 class="text-md font-medium text-gray-700 mb-3">Utilisation CPU (24h)</h4>
                <canvas id="cpuChart" class="max-h-48"></canvas>
            </div>

            <!-- Memory Usage Chart -->
            <div>
                <h4 class="text-md font-medium text-gray-700 mb-3">Utilisation Mémoire (24h)</h4>
                <canvas id="memoryChart" class="max-h-48"></canvas>
            </div>
        </div>
    </div>

    <!-- Database Information -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations Base de Données</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <div class="text-2xl font-bold text-blue-600">{{ $databaseInfo['total_tables'] ?? 0 }}</div>
                <div class="text-sm text-gray-600">Tables</div>
            </div>
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <div class="text-2xl font-bold text-green-600">{{ $databaseInfo['total_records'] ?? 0 }}</div>
                <div class="text-sm text-gray-600">Enregistrements</div>
            </div>
            <div class="text-center p-4 bg-yellow-50 rounded-lg">
                <div class="text-2xl font-bold text-yellow-600">{{ $databaseInfo['database_size'] ?? '0 MB' }}</div>
                <div class="text-sm text-gray-600">Taille DB</div>
            </div>
            <div class="text-center p-4 bg-purple-50 rounded-lg">
                <div class="text-2xl font-bold text-purple-600">{{ $databaseInfo['active_connections'] ?? 0 }}</div>
                <div class="text-sm text-gray-600">Connexions</div>
            </div>
        </div>

        <div class="mt-6">
            <h4 class="text-md font-medium text-gray-700 mb-3">Tables Principales</h4>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Table</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enregistrements</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Taille</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dernière MAJ</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($databaseTables ?? [] as $table)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $table['name'] ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($table['rows'] ?? 0) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $table['size'] ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $table['updated_at'] ?? 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- PHP Configuration -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Configuration PHP</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="text-md font-medium text-gray-700 mb-3">Limites</h4>
                <div class="space-y-2">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Memory Limit</span>
                        <span class="text-sm font-medium text-gray-900">{{ $phpConfig['memory_limit'] ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Max Execution Time</span>
                        <span class="text-sm font-medium text-gray-900">{{ $phpConfig['max_execution_time'] ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Max Upload Size</span>
                        <span class="text-sm font-medium text-gray-900">{{ $phpConfig['upload_max_filesize'] ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-sm text-gray-600">Post Max Size</span>
                        <span class="text-sm font-medium text-gray-900">{{ $phpConfig['post_max_size'] ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <div>
                <h4 class="text-md font-medium text-gray-700 mb-3">Extensions</h4>
                <div class="grid grid-cols-2 gap-2">
                    @foreach($phpExtensions ?? [] as $extension)
                    <div class="flex items-center p-2 bg-green-50 rounded">
                        <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-sm text-gray-700">{{ $extension }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Disk Usage -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Utilisation du Disque</h3>
        <div class="space-y-4">
            @foreach($diskUsage ?? [] as $disk)
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="font-medium text-gray-900">{{ $disk['mount'] ?? 'N/A' }}</div>
                        <div class="text-sm text-gray-600">{{ $disk['used'] ?? '0' }} / {{ $disk['total'] ?? '0' }}</div>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="w-32 bg-gray-200 rounded-full h-3">
                        <div class="bg-blue-500 h-3 rounded-full" style="width: {{ $disk['percentage'] ?? 0 }}%"></div>
                    </div>
                    <span class="text-sm font-medium text-gray-900">{{ $disk['percentage'] ?? 0 }}%</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // CPU Usage Chart
    const cpuCtx = document.getElementById('cpuChart').getContext('2d');
    new Chart(cpuCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData['cpu']['labels'] ?? []) !!},
            datasets: [{
                label: 'CPU %',
                data: {!! json_encode($chartData['cpu']['data'] ?? []) !!},
                borderColor: 'rgb(99, 102, 241)',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Memory Usage Chart
    const memoryCtx = document.getElementById('memoryChart').getContext('2d');
    new Chart(memoryCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData['memory']['labels'] ?? []) !!},
            datasets: [{
                label: 'Mémoire %',
                data: {!! json_encode($chartData['memory']['data'] ?? []) !!},
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});
</script>
@endpush
@endsection
