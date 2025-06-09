@extends('layouts.admin')
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="row">
                        <div class="col-6 d-flex align-items-center">
                            <h6 class="mb-0">Profil utilisateur</h6>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm mb-0">
                                <i class="fas fa-edit"></i>&nbsp;&nbsp;Modifier
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-info btn-sm mb-0">
                                <i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Retour
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card card-profile">
                                <div class="card-header text-center border-0 pt-4 pb-0">
                                    <div class="d-flex justify-content-center">
                                        <i class="fas fa-user-circle fa-6x text-primary"></i>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="text-center mt-2">
                                        <h5>{{ $user->nom }} {{ $user->prenoms }}</h5>
                                        <div class="h6 font-weight-300">
                                            <i class="fas fa-envelope me-2"></i>{{ $user->email }}
                                        </div>
                                        <div class="h6">
                                            @if($user->role === 'admin')
                                                <span class="badge bg-danger">Administrateur</span>
                                            @else
                                                <span class="badge bg-info">Citoyen</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-sm">Téléphone:</span>
                                            <span class="text-sm font-weight-bold">{{ $user->phone ?? 'Non renseigné' }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-sm">Demandes:</span>
                                            <span class="text-sm font-weight-bold">{{ $user->requests->count() }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-sm">Inscription:</span>
                                            <span class="text-sm font-weight-bold">{{ $user->created_at ? $user->created_at->format('d/m/Y') : 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card mb-4">
                                <div class="card-header pb-0">
                                    <h6 class="mb-0">Informations personnelles</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-control-label">Nom</label>
                                                <p class="form-control-static">{{ $user->nom }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-control-label">Prénoms</label>
                                                <p class="form-control-static">{{ $user->prenoms }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-control-label">Email</label>
                                                <p class="form-control-static">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-control-label">Téléphone</label>
                                                <p class="form-control-static">{{ $user->phone ?? 'Non renseigné' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-control-label">Adresse</label>
                                                <p class="form-control-static">{{ $user->address ?? 'Non renseignée' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6 class="mb-0">Demandes récentes</h6>
                                </div>
                                <div class="card-body px-0 pt-0 pb-2">
                                    <div class="table-responsive p-0">
                                        <table class="table align-items-center mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Document</th>
                                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Statut</th>
                                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                                                    <th class="text-secondary opacity-7"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($user->requests->take(5) as $request)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex px-2 py-1">
                                                            <div>
                                                                <i class="fas fa-file-alt text-primary opacity-10 fa-lg me-3"></i>
                                                            </div>
                                                            <div class="d-flex flex-column justify-content-center">
                                                                <h6 class="mb-0 text-sm">{{ ucfirst($request->type) ?? 'Type non spécifié' }}</h6>
                                                                <p class="text-xs text-secondary mb-0">Ref: #{{ $request->reference_number }}</p>
                                                            </div>
                                                        </div>
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
                                                        <a href="{{ route('admin.requests.show', $request->id) }}" class="btn btn-info btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Voir">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="4" class="text-center py-4">
                                                        <p class="text-sm text-secondary mb-0">Aucune demande trouvée</p>
                                                    </td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
