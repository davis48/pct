<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\CitizenRequest;
use App\Models\Attachment;

class TestAttachmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer quelques fichiers de test
        $testFiles = [
            'document1.pdf' => 'Contenu PDF de test 1',
            'document2.jpg' => 'Contenu image de test 2',
            'document3.docx' => 'Contenu Word de test 3'
        ];

        // S'assurer que le dossier attachments existe
        Storage::makeDirectory('public/attachments');

        foreach ($testFiles as $filename => $content) {
            Storage::put('public/attachments/' . $filename, $content);
        }

        // Récupérer les premières demandes pour y ajouter des attachments
        $requests = CitizenRequest::take(5)->get();

        foreach ($requests as $index => $request) {
            $filename = array_keys($testFiles)[$index % count($testFiles)];
            
            // Créer un attachment dans la table attachments
            Attachment::create([
                'citizen_request_id' => $request->id,
                'file_path' => 'attachments/' . $filename,
                'file_name' => $filename,
                'file_type' => pathinfo($filename, PATHINFO_EXTENSION),
                'file_size' => strlen($testFiles[$filename]),
                'uploaded_by' => $request->user_id,
                'type' => 'citizen'
            ]);

            // Aussi mettre à jour le champ uploaded_document pour certaines demandes
            if ($index < 3) {
                $request->update([
                    'uploaded_document' => 'attachments/' . $filename
                ]);
            }
        }

        $this->command->info('Attachments de test créés avec succès !');
    }
}
