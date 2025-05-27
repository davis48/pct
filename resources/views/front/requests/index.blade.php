@extends('layouts.front.app')
@section('content')
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Mes demandes</h1>
            <a href="{{ route('requests.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i>Nouvelle demande
            </a>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="card shadow">
            <div class="card-body">
                @if(count($requests) > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Type</th>
                                <th>Date de demande</th>
                                <th>Document associé</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $request)
                            <tr>
                                <td>{{ $request->id }}</td>
                                <td>{{ ucfirst($request->type) }}</td>
                                <td>{{ $request->created_at->format('d/m/Y') }}</td>
                                <td>{{ $request->document ? $request->document->title : 'Aucun' }}</td>
                                <td>
                                    @if($request->status == 'pending')
                                    <span class="badge bg-warning text-dark">En attente</span>
                                    @elseif($request->status == 'approved')
                                    <span class="badge bg-success">Approuvée</span>
                                    @elseif($request->status == 'rejected')
                                    <span class="badge bg-danger">Rejetée</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('requests.show', $request->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <p class="lead text-muted">Vous n'avez pas encore de demandes.</p>
                    <a href="{{ route('requests.create') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-plus-circle me-2"></i>Créer une demande
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
