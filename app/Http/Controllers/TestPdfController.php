<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TestPdfController extends Controller
{
    public function generateTest($type)
    {
        // Données de test
        $testUser = (object) [
            'nom' => 'KOUASSI',
            'prenoms' => 'Jean-Baptiste',
            'date_naissance' => '1990-05-15',
            'address' => '123 Rue de la Paix, Abidjan',
            'genre' => 'M',
            'profession' => 'Enseignant',
            'place_of_birth' => 'Abidjan',
            'father_name' => 'KOUASSI Emmanuel',
            'mother_name' => 'KOUASSI née TRAORE Marie',
        ];

        $data = [
            'user' => $testUser,
            'reference_number' => 'TEST-' . strtoupper($type) . '-' . date('Ymd-His'),
            'date_generation' => date('d/m/Y'),
            'commune' => 'Commune de Yopougon',
        ];

        // Choisir le template selon le type
        switch ($type) {
            case 'extrait-naissance':
                $data['document_title'] = 'EXTRAIT D\'ACTE DE NAISSANCE';
                $view = 'documents.templates.extrait-naissance';
                $filename = 'test-extrait-naissance.pdf';
                break;
                
            case 'certificat-mariage':
                $data['document_title'] = 'CERTIFICAT DE MARIAGE';
                $view = 'documents.templates.certificat-mariage';
                $filename = 'test-certificat-mariage.pdf';
                break;
                
            case 'declaration-naissance':
                $data['document_title'] = 'DÉCLARATION DE NAISSANCE';
                $view = 'documents.templates.declaration-naissance';
                $filename = 'test-declaration-naissance.pdf';
                break;
                
            case 'certificat-celibat':
                $data['document_title'] = 'CERTIFICAT DE CÉLIBAT';
                $view = 'documents.templates.certificat-celibat';
                $filename = 'test-certificat-celibat.pdf';
                break;
                
            case 'certificat-deces':
                $data['document_title'] = 'CERTIFICAT DE DÉCÈS';
                $view = 'documents.templates.certificat-deces';
                $filename = 'test-certificat-deces.pdf';
                break;
                
            default:
                abort(404, 'Type de document non supporté');
        }

        try {
            $pdf = Pdf::loadView($view, $data);
            return $pdf->download($filename);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la génération du PDF',
                'message' => $e->getMessage(),
                'view' => $view,
                'data' => $data
            ], 500);
        }
    }
}
