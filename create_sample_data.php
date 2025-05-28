<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Document;
use App\Models\CitizenRequest;
use Illuminate\Support\Facades\Hash;

// Create sample citizens
$citizens = [
    [
        'nom' => 'Kouassi',
        'prenoms' => 'Jean',
        'email' => 'jean.kouassi@example.ci',
        'phone' => '+225 07 12 34 56 78',
        'role' => 'citizen'
    ],
    [
        'nom' => 'Traoré',
        'prenoms' => 'Marie',
        'email' => 'marie.traore@example.ci',
        'phone' => '+225 05 87 65 43 21',
        'role' => 'citizen'
    ],
    [
        'nom' => 'Diallo',
        'prenoms' => 'Amadou',
        'email' => 'amadou.diallo@example.ci',
        'phone' => '+225 01 23 45 67 89',
        'role' => 'citizen'
    ],
    [
        'nom' => 'Camara',
        'prenoms' => 'Fatou',
        'email' => 'fatou.camara@example.ci',
        'phone' => '+225 09 98 76 54 32',
        'role' => 'citizen'
    ]
];

echo "Creating sample citizens...\n";
foreach ($citizens as $citizenData) {
    User::firstOrCreate(
        ['email' => $citizenData['email']],
        [
            'nom' => $citizenData['nom'],
            'prenoms' => $citizenData['prenoms'],
            'phone' => $citizenData['phone'],
            'password' => Hash::make('password123'),
            'role' => 'citizen',
            'email_verified_at' => now()
        ]
    );
    echo "Created citizen: {$citizenData['prenoms']} {$citizenData['nom']}\n";
}

// Create sample documents
$documents = [
    [
        'title' => 'Extrait de Naissance',
        'description' => 'Document officiel attestant de la naissance',
        'category' => 'etat-civil',
        'file_path' => '/documents/templates/extrait-naissance.pdf',
        'is_public' => true,
        'status' => 'active'
    ],
    [
        'title' => 'Carte Nationale d\'Identité',
        'description' => 'Document d\'identité nationale',
        'category' => 'identite',
        'file_path' => '/documents/templates/cni.pdf',
        'is_public' => true,
        'status' => 'active'
    ],
    [
        'title' => 'Passeport',
        'description' => 'Document de voyage international',
        'category' => 'voyage',
        'file_path' => '/documents/templates/passeport.pdf',
        'is_public' => true,
        'status' => 'active'
    ],
    [
        'title' => 'Casier Judiciaire',
        'description' => 'Bulletin de casier judiciaire',
        'category' => 'judiciaire',
        'file_path' => '/documents/templates/casier-judiciaire.pdf',
        'is_public' => false,
        'status' => 'active'
    ],
    [
        'title' => 'Certificat de Résidence',
        'description' => 'Attestation de domicile',
        'category' => 'residence',
        'file_path' => '/documents/templates/certificat-residence.pdf',
        'is_public' => true,
        'status' => 'active'
    ]
];

echo "\nCreating sample documents...\n";
foreach ($documents as $docData) {
    Document::firstOrCreate(
        ['title' => $docData['title']],
        $docData
    );
    echo "Created document: {$docData['title']}\n";
}

// Create sample citizen requests
echo "\nCreating sample citizen requests...\n";
$citizenUsers = User::where('role', 'citizen')->get();
$documentTypes = Document::all();

$statuses = ['pending', 'approved', 'rejected'];
$requestTypes = ['nouveau', 'renouvellement', 'duplicata', 'rectification'];
$descriptions = [
    'Demande de nouveau document',
    'Renouvellement de document expiré',
    'Demande de duplicata suite à perte',
    'Rectification d\'informations erronées',
    'Première demande',
    'Changement d\'état civil',
    'Mise à jour des informations personnelles'
];

$requestsData = [];

for ($i = 0; $i < 15; $i++) {
    $citizen = $citizenUsers->random();
    $document = $documentTypes->random();
    $status = $statuses[array_rand($statuses)];
    $type = $requestTypes[array_rand($requestTypes)];
    $description = $descriptions[array_rand($descriptions)];

    // Generate random dates within the last 3 months
    $createdAt = now()->subDays(rand(1, 90));
    $updatedAt = $status !== 'pending' ? $createdAt->copy()->addDays(rand(1, 7)) : $createdAt;

    $requestsData[] = [
        'user_id' => $citizen->id,
        'document_id' => $document->id,
        'type' => $type,
        'description' => $description,
        'status' => $status,
        'admin_comments' => $status === 'rejected' ? 'Documents incomplets ou incorrects' : null,
        'created_at' => $createdAt,
        'updated_at' => $updatedAt
    ];
}

foreach ($requestsData as $requestData) {
    CitizenRequest::create($requestData);
    $statusText = $requestData['status'];
    $typeText = $requestData['type'];
    echo "Created request: {$typeText} - {$statusText}\n";
}

echo "\nSample data creation completed!\n";
echo "Citizens: " . User::where('role', 'citizen')->count() . "\n";
echo "Documents: " . Document::count() . "\n";
echo "Requests: " . CitizenRequest::count() . "\n";
