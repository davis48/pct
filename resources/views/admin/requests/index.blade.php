@extends('layouts.admin')
@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="row">
                        <div class="col-6 d-flex align-items-center">
                            <h6 class="mb-0">Liste des demandes</h6>
                        </div>
                        <div class="col-6 text-end">
                            <form action="{{ route('admin.requests.index') }}" method="GET" class="d-flex">
                                <select name="status" class="form-select form-select-sm me-2" onchange="this.form.submit()">
                                    <option value="">Tous les statuts</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>En traitement</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approuvée</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejetée</option>
                                </select>
                                <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Recherche..." value="{{ request('search') }}">
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Référence</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Document</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Citoyen</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Statut</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($requests as $request)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">#{{ $request->reference_number }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($request->document)
                                            <p class="text-xs font-weight-bold mb-0">{{ $request->document->title }}</p>
                                            <p class="text-xs text-secondary mb-0">{{ $request->document->category }}</p>
                                        @else
                                            <p class="text-xs font-weight-bold mb-0 text-muted">Document non spécifié</p>
                                            <p class="text-xs text-secondary mb-0">-</p>
                                        @endif
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $request->user->nom }} {{ $request->user->prenoms }}</p>
                                        <p class="text-xs text-secondary mb-0">{{ $request->user->email }}</p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        @if($request->status === 'pending')
                                            <span class="badge badge-sm bg-warning">En attente</span>
                                        @elseif($request->status === 'approved')
                                            <span class="badge badge-sm bg-success">Approuvée</span>
                                        @elseif($request->status === 'rejected')
                                            <span class="badge badge-sm bg-danger">Rejetée</span>
                                        @else
                                            <span class="badge badge-sm bg-info">En traitement</span>
                                        @endif
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $request->created_at ? $request->created_at->format('d/m/Y') : 'N/A' }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.requests.show', $request->id) }}" class="btn btn-info btn-sm me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.requests.edit', $request->id) }}" class="btn btn-warning btn-sm me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <p class="text-sm text-secondary mb-0">Aucune demande trouvée</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($requests->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $requests->withQueryString()->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
