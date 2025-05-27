@extends('layouts.front.app')
@section('content')
  <section class="py-5">
        <div class="container">
            <h1 class="mb-4">Mes documents</h1>
            
            <div class="card shadow mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Historique des demandes</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Référence</th>
                                    <th>Type de document</th>
                                    <th>Date de demande</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#AD20230001</td>
                                    <td>Attestation de domicile</td>
                                    <td>15/06/2023</td>
                                    <td><span class="badge bg-success">Terminé</span></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-primary">Télécharger</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#AD20230002</td>
                                    <td>Extrait d'acte de naissance</td>
                                    <td>20/06/2023</td>
                                    <td><span class="badge bg-warning text-dark">En traitement</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-secondary" disabled>En attente</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#AD20230003</td>
                                    <td>Demande de passeport</td>
                                    <td>25/06/2023</td>
                                    <td><span class="badge bg-info">En vérification</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-secondary" disabled>En attente</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="text-center">
                <a href="{{ url('/requests') }}" class="btn btn-primary">Nouvelle demande</a>
            </div>
        </div>
    </section>
@endsection
