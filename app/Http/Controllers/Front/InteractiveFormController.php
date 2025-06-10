<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CitizenRequest;
use App\Models\User;
use App\Services\DocumentGeneratorService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class InteractiveFormController extends Controller
{
    protected $documentGenerator;

    public function __construct(DocumentGeneratorService $documentGenerator)
    {
        $this->documentGenerator = $documentGenerator;
    }

    /**
     * Affiche la liste des formulaires interactifs disponibles
     */
    public function index()
    {
        $availableForms = [
            'certificat-mariage' => [
                'title' => 'Certificat de Mariage',
                'description' => 'Formulaire pour demander un certificat de mariage',
                'icon' => 'fas fa-heart',
                'estimated_time' => '5-10 minutes'
            ],
            'certificat-celibat' => [
                'title' => 'Certificat de Célibat',
                'description' => 'Formulaire pour demander un certificat de célibat',
                'icon' => 'fas fa-user',
                'estimated_time' => '3-5 minutes'
            ],
            'extrait-naissance' => [
                'title' => 'Extrait de Naissance',
                'description' => 'Formulaire pour demander un extrait de naissance',
                'icon' => 'fas fa-baby',
                'estimated_time' => '3-5 minutes'
            ],
            'certificat-deces' => [
                'title' => 'Certificat de Décès',
                'description' => 'Formulaire pour demander un certificat de décès',
                'icon' => 'fas fa-cross',
                'estimated_time' => '5-8 minutes'
            ],
            'attestation-domicile' => [
                'title' => 'Attestation de Domicile',
                'description' => 'Formulaire pour demander une attestation de domicile',
                'icon' => 'fas fa-home',
                'estimated_time' => '3-5 minutes'
            ],
            'legalisation' => [
                'title' => 'Légalisation de Document',
                'description' => 'Formulaire pour demander la légalisation d\'un document',
                'icon' => 'fas fa-stamp',
                'estimated_time' => '5-8 minutes'
            ]
        ];

        return view('front.interactive-forms.index_standalone', compact('availableForms'));
    }

    /**
     * Affiche un formulaire interactif spécifique
     */
    public function show($formType)
    {
        $validForms = [
            'certificat-mariage', 'certificat-celibat', 'extrait-naissance',
            'certificat-deces', 'attestation-domicile', 'legalisation'
        ];

        if (!in_array($formType, $validForms)) {
            abort(404, 'Formulaire non trouvé');
        }

        // Pré-remplir avec les données utilisateur si connecté
        $userData = [];
        if (Auth::check()) {
            $user = Auth::user();
            $userData = [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address,
                'date_of_birth' => $user->date_of_birth,
                'place_of_birth' => $user->place_of_birth,
                'nationality' => $user->nationality,
                'profession' => $user->profession,
                'father_name' => $user->father_name,
                'mother_name' => $user->mother_name
            ];
        }

        // Essayer d'abord la version standalone, sinon utiliser la version normale
        $standaloneView = "front.interactive-forms.{$formType}_standalone";
        $normalView = "front.interactive-forms.{$formType}";

        if (view()->exists($standaloneView)) {
            return view($standaloneView, compact('userData'));
        } else {
            return view($normalView, compact('userData'));
        }
    }

    /**
     * Méthodes pour les formulaires standalone
     */
    public function extraitNaissanceStandalone()
    {
        return $this->show('extrait-naissance');
    }

    public function attestationDomicileStandalone()
    {
        return $this->show('attestation-domicile');
    }

    public function certificatMariageStandalone()
    {
        return $this->show('certificat-mariage');
    }

    public function certificatCelibatStandalone()
    {
        return $this->show('certificat-celibat');
    }

    public function certificatDecesStandalone()
    {
        return $this->show('certificat-deces');
    }

    public function legalisationStandalone()
    {
        return $this->show('legalisation');
    }

    /**
     * Traite et génère le document depuis le formulaire interactif
     */
    public function generate(Request $request, $formType)
    {
        // Log de debug
        Log::info('=== DÉBUT GÉNÉRATION FORMULAIRE ===', [
            'form_type' => $formType,
            'method' => $request->method(),
            'all_data' => $request->all(),
            'user_connected' => Auth::check(),
            'user_id' => Auth::id()
        ]);

        $validForms = [
            'certificat-mariage', 'certificat-celibat', 'extrait-naissance',
            'certificat-deces', 'attestation-domicile', 'legalisation'
        ];

        if (!in_array($formType, $validForms)) {
            Log::warning('Formulaire non valide', ['form_type' => $formType]);
            abort(404, 'Formulaire non trouvé');
        }

        // Validation des données selon le type de formulaire
        $validatedData = $this->validateFormData($request, $formType);
        // Gérer les documents uploadés
        $uploadedDocuments = [];
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                if ($file->isValid()) {
                    $uploadedDocuments[] = [
                        'original_name' => $file->getClientOriginalName(),
                        'size' => $file->getSize(),
                        'type' => $file->getClientMimeType(),
                        // Ne pas stocker le contenu ici pour éviter les données trop volumineuses
                        'temp_path' => $file->getRealPath() // Chemin temporaire pour traitement ultérieur
                    ];
                }
            }
        }        // L'utilisateur doit être connecté pour créer une demande
        if (!Auth::check()) {
            // Pour les formulaires standalone, sauvegarder les données en session et rediriger vers connexion
            session([
                'pending_form_submission' => [
                    'form_type' => $formType,
                    'form_data' => $validatedData,
                    'uploaded_documents' => $uploadedDocuments,
                    'submitted_at' => now()->toISOString()
                ]
            ]);

            return redirect()->route('login.standalone')
                ->with('info', 'Pour finaliser votre demande et procéder au paiement, veuillez vous connecter ou créer un compte.')
                ->with('show_register_option', true);
        }

        try {
            // Mettre à jour les informations personnelles de l'utilisateur si disponibles
            $user = Auth::user();
            $updateData = [];

            if (isset($validatedData['name']) && $validatedData['name'] !== $user->name) {
                $updateData['name'] = $validatedData['name'];
            }
            if (isset($validatedData['nationality'])) {
                $updateData['nationality'] = $validatedData['nationality'];
            }
            if (isset($validatedData['profession'])) {
                $updateData['profession'] = $validatedData['profession'];
            }
            if (isset($validatedData['address'])) {
                $updateData['address'] = $validatedData['address'];
            }
            if (isset($validatedData['place_of_birth'])) {
                $updateData['place_of_birth'] = $validatedData['place_of_birth'];
            }

            if (!empty($updateData)) {
                $user->update($updateData);
            }            // Créer la demande avec statut "draft" pour passer par le processus de paiement
            $citizenRequest = CitizenRequest::create([
                'user_id' => Auth::id(),
                'type' => $this->mapFormTypeToRequestType($formType),
                'description' => "Demande de {$this->getFormTitle($formType)} via formulaire interactif",
                'status' => 'draft', // Statut draft pour passer par le paiement
                'payment_status' => 'unpaid',
                'payment_required' => true,
                'contact_preference' => 'email',                'additional_data' => json_encode([
                    'form_type' => $formType,
                    'form_data' => $validatedData,
                    'uploaded_documents_count' => count($uploadedDocuments),
                    'generated_via' => 'interactive_form',
                    'form_version' => '1.0',
                    'submitted_at' => now()->toISOString()
                ])
            ]);            // Stocker les documents uploadés si disponibles
            if (!empty($uploadedDocuments)) {
                foreach ($uploadedDocuments as $index => $docData) {
                    // Utiliser le fichier temporaire directement
                    $extension = pathinfo($docData['original_name'], PATHINFO_EXTENSION);
                    $filename = 'request_' . $citizenRequest->id . '_doc_' . ($index + 1) . '.' . $extension;

                    // Stocker le fichier dans le storage Laravel
                    $path = Storage::disk('public')->putFileAs(
                        'attachments',
                        new \Illuminate\Http\File($docData['temp_path']),
                        $filename
                    );
                      // Créer l'enregistrement dans la base de données
                    $citizenRequest->attachments()->create([
                        'file_name' => $docData['original_name'],
                        'file_path' => $path,
                        'file_size' => $docData['size'],
                        'file_type' => $docData['type'],                        'uploaded_by' => Auth::id(),
                        'type' => 'citizen'
                    ]);
                }
            }

            // Rediriger vers la page de paiement standalone
            return redirect()->route('payments.standalone.show', $citizenRequest)
                ->with('success', '🎉 Votre formulaire a été traité avec succès ! Référence: ' . $citizenRequest->reference_number . '. Veuillez procéder au paiement pour finaliser votre demande.');

        } catch (\Exception $e) {
            Log::error('Erreur lors de la génération du formulaire interactif', [
                'user_id' => Auth::id(),
                'form_type' => $formType,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors du traitement de votre formulaire. Veuillez réessayer.');
        }
    }

    /**
     * Traite une soumission en attente après connexion
     */
    public function processPendingSubmission()
    {
        if (!Auth::check()) {
            return redirect()->route('login.standalone')->with('error', 'Vous devez être connecté pour continuer.');
        }

        $pendingSubmission = session('pending_form_submission');
        if (!$pendingSubmission) {
            return redirect()->route('interactive-forms.index')
                ->with('info', 'Aucune demande en attente trouvée.');
        }

        // Effacer la session
        session()->forget('pending_form_submission');

        // Créer une nouvelle requête avec les données sauvegardées
        $request = new Request();
        $request->merge($pendingSubmission['form_data']);

        // Reconstituer les fichiers uploadés s'il y en a
        if (!empty($pendingSubmission['uploaded_documents'])) {
            // Note: Les fichiers ne peuvent pas être reconstitués de cette façon
            // Il faudrait les stocker temporairement dans le filesystem
            // Pour l'instant, on traite sans les fichiers
            Log::info('Documents en attente trouvés mais non reconstitués', [
                'count' => count($pendingSubmission['uploaded_documents'])
            ]);
        }

        // Traiter la soumission
        return $this->generate($request, $pendingSubmission['form_type']);
    }

    /**
     * Télécharge le document généré
     */
    public function download($formType, $requestId)
    {
        // Créer un PDF à la volée avec les données stockées
        if (Auth::check() && is_numeric($requestId)) {
            $citizenRequest = CitizenRequest::findOrFail($requestId);
            $data = json_decode($citizenRequest->additional_data, true);
        } else {
            // Pour les téléchargements temporaires, on ne peut pas récupérer les données
            return abort(404, 'Document non trouvé ou expiré');
        }

        if (!$data || !isset($data['form_data'])) {
            return abort(404, 'Données du document non trouvées');
        }

        // Générer le PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            "front.interactive-forms.previews.{$formType}",
            ['data' => $data['form_data']]
        );

        $fileName = "{$formType}-" . now()->format('Y-m-d-H-i-s') . ".pdf";

        return $pdf->download($fileName);
    }

    /**
     * Mapper les types de formulaires aux types de demandes dans la base
     */
    private function mapFormTypeToRequestType($formType)
    {
        $mapping = [
            'certificat-mariage' => 'mariage',
            'certificat-celibat' => 'certificat',
            'extrait-naissance' => 'extrait-acte',
            'certificat-deces' => 'certificat',
            'attestation-domicile' => 'attestation',
            'legalisation' => 'legalisation'
        ];

        return $mapping[$formType] ?? 'autre';
    }

    /**
     * Obtenir le titre du formulaire selon le type
     */
    private function getFormTitle($formType)
    {
        $titles = [
            'certificat-mariage' => 'Certificat de Mariage',
            'certificat-celibat' => 'Certificat de Célibat',
            'extrait-naissance' => 'Extrait de Naissance',
            'certificat-deces' => 'Certificat de Décès',
            'attestation-domicile' => 'Attestation de Domicile',
            'legalisation' => 'Légalisation de Document'
        ];

        return $titles[$formType] ?? ucfirst(str_replace('-', ' ', $formType));
    }

    /**
     * Valide les données du formulaire selon le type
     */
    private function validateFormData(Request $request, $formType)
    {
        $commonRules = [
            'name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'place_of_birth' => 'required|string|max:255',
            'nationality' => 'required|string|max:100',
            'documents.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max per file
        ];

        $specificRules = [];

        switch ($formType) {
            case 'certificat-mariage':
                $specificRules = [
                    'spouse_name' => 'required|string|max:255',
                    'spouse_birth_date' => 'required|date',
                    'spouse_birth_place' => 'required|string|max:255',
                    'marriage_date' => 'required|date',
                    'marriage_time' => 'required|string',
                    'witness1_name' => 'required|string|max:255',
                    'witness2_name' => 'required|string|max:255',
                ];
                break;

            case 'certificat-celibat':
                $specificRules = [
                    'profession' => 'required|string|max:255',
                    'address' => 'required|string|max:500',
                ];
                break;            case 'extrait-naissance':
                $specificRules = [
                    'gender' => 'required|string|in:Masculin,Féminin',
                    'birth_time' => 'nullable|string',
                    'father_name' => 'required|string|max:255',
                    'father_profession' => 'nullable|string|max:255',
                    'mother_name' => 'required|string|max:255',
                    'mother_profession' => 'nullable|string|max:255',
                    'registry_number' => 'required|string|max:255',
                    'registration_date' => 'required|date',
                    'declarant_name' => 'nullable|string|max:255',
                ];
                break;

            case 'certificat-deces':
                $specificRules = [
                    'deceased_last_name' => 'required|string|max:255',
                    'deceased_first_name' => 'required|string|max:255',
                    'deceased_birth_date' => 'required|date',
                    'deceased_birth_place' => 'required|string|max:255',
                    'death_date' => 'required|date',
                    'death_place' => 'required|string|max:255',
                    'declarant_name' => 'required|string|max:255',
                    'declarant_birth_date' => 'required|date',
                    'declarant_profession' => 'nullable|string|max:255',
                    'declarant_address' => 'required|string|max:500',
                    'relationship_to_deceased' => 'required|string|max:255',
                    'purpose' => 'required|string|max:255',
                    'notes' => 'nullable|string|max:1000',
                    'declaration' => 'required|accepted',
                ];
                // Remove common rules for death certificate as it has different structure
                $commonRules = [
                    'documents.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                ];
                break;

            case 'attestation-domicile':
                $specificRules = [
                    'address' => 'required|string|max:500',
                    'district' => 'required|string|max:255',
                    'residence_duration' => 'required|string|max:100',
                ];
                break;

            case 'legalisation':
                $specificRules = [
                    'document_type' => 'required|string|max:255',
                    'document_date' => 'required|date',
                    'issuing_authority' => 'required|string|max:255',
                    'document_number' => 'nullable|string|max:255',
                    'motif_demande' => 'required|string|max:255',
                    'destination' => 'nullable|string|max:255',
                    'accept_terms' => 'required|boolean',
                ];
                break;
        }

        return $request->validate(array_merge($commonRules, $specificRules));
    }
}
