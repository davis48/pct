<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class FormulaireController extends Controller
{
    /**
     * Afficher la liste des formulaires disponibles
     */
    public function index()
    {
        $formulaires = [
            [
                'id' => 'attestation-domicile',
                'title' => 'Attestation de Domicile',
                'description' => 'Formulaire pour demander une attestation de domicile',
                'icon' => 'fas fa-home',
                'category' => 'Résidence'
            ],
            [
                'id' => 'certificat-celibat',
                'title' => 'Certificat de Célibat',
                'description' => 'Formulaire pour demander un certificat de célibat',
                'icon' => 'fas fa-user',
                'category' => 'État civil'
            ],
            [
                'id' => 'certificat-mariage',
                'title' => 'Certificat de Mariage',
                'description' => 'Formulaire pour demander un certificat de mariage',
                'icon' => 'fas fa-ring',
                'category' => 'État civil'
            ],
            [
                'id' => 'extrait-acte-naissance',
                'title' => 'Extrait d\'Acte de Naissance',
                'description' => 'Formulaire pour demander un extrait d\'acte de naissance',
                'icon' => 'fas fa-baby',
                'category' => 'État civil'
            ],
            [
                'id' => 'declaration-naissance',
                'title' => 'Déclaration de Naissance',
                'description' => 'Formulaire pour déclarer une naissance',
                'icon' => 'fas fa-birth-certificate',
                'category' => 'État civil'
            ]
        ];

        return view('front.formulaires.index', compact('formulaires'));
    }

    /**
     * Afficher un formulaire spécifique
     */
    public function show($type)
    {
        $formulaires = [
            'attestation-domicile' => [
                'title' => 'Attestation de Domicile',
                'view' => 'front.formulaires.attestation-domicile'
            ],
            'certificat-celibat' => [
                'title' => 'Certificat de Célibat',
                'view' => 'front.formulaires.certificat-celibat'
            ],
            'certificat-mariage' => [
                'title' => 'Certificat de Mariage',
                'view' => 'front.formulaires.certificat-mariage'
            ],
            'extrait-acte-naissance' => [
                'title' => 'Extrait d\'Acte de Naissance',
                'view' => 'front.formulaires.extrait-acte-naissance'
            ],
            'declaration-naissance' => [
                'title' => 'Déclaration de Naissance',
                'view' => 'front.formulaires.declaration-naissance'
            ]
        ];

        if (!isset($formulaires[$type])) {
            abort(404, 'Formulaire non trouvé');
        }

        $formulaire = $formulaires[$type];
        
        return view($formulaire['view'], compact('formulaire'));
    }

    /**
     * Télécharger un formulaire en PDF
     */
    public function download($type)
    {
        $formulaires = [
            'attestation-domicile' => 'Formulaire_Attestation_Domicile.pdf',
            'certificat-celibat' => 'Formulaire_Certificat_Celibat.pdf',
            'certificat-mariage' => 'Formulaire_Certificat_Mariage.pdf',
            'extrait-acte-naissance' => 'Formulaire_Extrait_Acte_Naissance.pdf',
            'declaration-naissance' => 'Formulaire_Declaration_Naissance.pdf'
        ];

        if (!isset($formulaires[$type])) {
            abort(404, 'Formulaire non trouvé');
        }

        // Générer le PDF du formulaire
        $formulaire = [
            'attestation-domicile' => [
                'title' => 'Attestation de Domicile',
                'view' => 'front.formulaires.attestation-domicile'
            ],
            'certificat-celibat' => [
                'title' => 'Certificat de Célibat',
                'view' => 'front.formulaires.certificat-celibat'
            ],
            'certificat-mariage' => [
                'title' => 'Certificat de Mariage',
                'view' => 'front.formulaires.certificat-mariage'
            ],
            'extrait-acte-naissance' => [
                'title' => 'Extrait d\'Acte de Naissance',
                'view' => 'front.formulaires.extrait-acte-naissance'
            ],
            'declaration-naissance' => [
                'title' => 'Déclaration de Naissance',
                'view' => 'front.formulaires.declaration-naissance'
            ]
        ];        try {
            $pdf = Pdf::loadView($formulaire[$type]['view'], ['formulaire' => $formulaire[$type]]);
            return $pdf->download($formulaires[$type]);
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la génération du formulaire PDF : ' . $e->getMessage());
        }
    }
}
