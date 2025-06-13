<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test - Cachet Électronique PCT_MAYOR</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c5aa0;
            text-align: center;
            border-bottom: 3px solid #2c5aa0;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }
        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin: 5px;
        }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-info { background: #d1ecf1; color: #0c5460; }
        
        .preview-section {
            border: 2px solid #2c5aa0;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            background: #f8f9fa;
        }
        .electronic-seal-demo {
            text-align: center;
            padding: 20px;
            background: white;
            border-radius: 8px;
            margin: 15px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .config-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .config-table th, .config-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .config-table th {
            background-color: #2c5aa0;
            color: white;
        }
        .config-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .code-block {
            background: #f4f4f4;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            overflow-x: auto;
        }
        .button {
            display: inline-block;
            background: #2c5aa0;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin: 10px 5px;
            transition: background 0.3s;
        }
        .button:hover {
            background: #1e3d6f;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Test - Cachet Électronique PCT_MAYOR</h1>
        
        <div style="text-align: center; margin: 20px 0;">
            <span class="badge badge-success">✓ Nom du maire mis à jour</span>
            <span class="badge badge-info">✓ Cachet électronique activé</span>
            <span class="badge badge-warning">⚡ Système de vérification</span>
        </div>
        
        <h2>📋 Configuration actuelle</h2>
        
        <table class="config-table">
            <tr>
                <th>Paramètre</th>
                <th>Valeur</th>
                <th>Statut</th>
            </tr>
            <tr>
                <td><strong>Nom du maire</strong></td>
                <td>PCT_MAYOR</td>
                <td><span class="badge badge-success">✓ Mis à jour</span></td>
            </tr>
            <tr>
                <td><strong>Municipalité</strong></td>
                <td>Mairie de Cocody</td>
                <td><span class="badge badge-success">✓ Configuré</span></td>
            </tr>
            <tr>
                <td><strong>Cachet électronique</strong></td>
                <td>Activé avec code de vérification</td>
                <td><span class="badge badge-success">✓ Fonctionnel</span></td>
            </tr>
            <tr>
                <td><strong>Template SVG</strong></td>
                <td>cachet_electronique_v2.svg</td>
                <td>
                    <?php if (file_exists('images/official/cachet_electronique_v2.svg')): ?>
                        <span class="badge badge-success">✓ Disponible</span>
                    <?php else: ?>
                        <span class="badge badge-warning">⚠ Non trouvé</span>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
        
        <h2>📏 Positionnement du cachet électronique</h2>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 20px 0;">
            <div style="background: #fff3cd; padding: 20px; border-radius: 8px; border-left: 4px solid #ffc107;">
                <h4 style="color: #856404; margin-top: 0;">❌ Ancien positionnement</h4>
                <p style="margin: 0; font-size: 14px;">Centré, entre la signature et le nom du maire</p>
                <div style="text-align: center; margin: 10px 0; padding: 8px; border: 1px dashed #ccc; background: #f8f9fa; border-radius: 4px; font-size: 12px; color: #666;">
                    [Signature] [Cachet officiel]<br>
                    <strong>CACHET ÉLECTRONIQUE</strong><br>
                    <strong>PCT_MAYOR</strong>
                </div>
            </div>
            
            <div style="background: #d4edda; padding: 20px; border-radius: 8px; border-left: 4px solid #28a745;">
                <h4 style="color: #155724; margin-top: 0;">✅ Nouveau positionnement</h4>
                <p style="margin: 0; font-size: 14px;">Aligné à droite, sous le nom du maire</p>
                <div style="margin: 10px 0; padding: 8px; border: 1px solid #ddd; background: #f8f9fa; border-radius: 4px; font-size: 12px; color: #666;">
                    <div style="text-align: center;">
                        [Signature] [Cachet officiel]<br>
                        <strong>PCT_MAYOR</strong>
                    </div>
                    <div style="text-align: right; margin-top: 8px;">
                        <strong style="font-size: 10px; color: #2c5aa0;">CACHET ÉLECTRONIQUE</strong>
                    </div>
                </div>
            </div>
        </div>
        
        <h2>🎨 Aperçu du cachet électronique</h2>
        
        <div class="preview-section">
            <h3>Version SVG (Template)</h3>
            <div class="electronic-seal-demo">
                <?php if (file_exists('images/official/cachet_electronique_v2.svg')): ?>
                    <img src="images/official/cachet_electronique_v2.svg" alt="Cachet électronique V2" 
                         style="max-width: 100%; height: auto; border: 1px solid #ddd;">
                    <p style="margin-top: 15px; color: #666; font-size: 12px;">
                        <em>Aperçu du template SVG - Les valeurs {DATE} et {VERIFICATION_CODE} seront remplacées dynamiquement</em>
                    </p>
                <?php else: ?>
                    <p style="color: #d32f2f;">❌ Template SVG non trouvé</p>
                <?php endif; ?>
            </div>
              <h3>Version Fallback (HTML/CSS) - Position finale</h3>
            <div class="electronic-seal-demo">
                <div style="text-align: right; margin: 20px 0;">
                    <div style="display: inline-block; text-align: center; margin: 0; padding: 12px; border: 2px dashed #2c5aa0; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 8px; max-width: 220px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                        <div style="font-size: 11px; color: #2c5aa0; font-weight: bold; margin-bottom: 8px; border-bottom: 1px solid #2c5aa0; padding-bottom: 4px;">
                            <strong>CACHET ÉLECTRONIQUE</strong>
                        </div>
                        <div>
                            <p style="margin: 3px 0; font-size: 9px; color: #666;">Document certifié conforme</p>
                            <p style="margin: 3px 0; font-size: 9px; color: #666;">Autorité: MAIRIE DE COCODY</p>
                            <p style="margin: 3px 0; font-size: 9px; color: #666;">Signé par: PCT_MAYOR</p>
                            <p style="margin: 3px 0; font-size: 9px; color: #666;">Date: <?php echo date('d/m/Y H:i:s'); ?></p>
                            <div style="margin-top: 6px; padding: 4px; background-color: #fff3cd; border: 1px solid #ffeaa7; border-radius: 3px;">
                                <strong style="font-family: 'Courier New', monospace; font-size: 8px; color: #d32f2f;">Code: ABC123XY</strong>
                            </div>
                        </div>
                    </div>
                </div>
                <p style="margin-top: 15px; color: #666; font-size: 12px;">
                    <em>✅ Nouveau positionnement : aligné à droite sous le nom du maire</em>
                </p>
            </div>
        </div>
        
        <h2>⚙️ Données générées dynamiquement</h2>
        
        <div class="code-block">
            <?php
            // Simulation des données du cachet électronique
            $sealData = [
                'seal_number' => 'SEAL-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                'seal_date' => date('d/m/Y H:i:s'),
                'seal_authority' => 'MAIRIE DE COCODY',
                'verification_code' => strtoupper(substr(md5(uniqid()), 0, 8)),
            ];
            
            echo "Exemple de données générées pour chaque document:\n\n";
            echo "Numéro de cachet: " . $sealData['seal_number'] . "\n";
            echo "Date de certification: " . $sealData['seal_date'] . "\n";
            echo "Autorité: " . $sealData['seal_authority'] . "\n";
            echo "Code de vérification: " . $sealData['verification_code'] . "\n";
            echo "Maire: PCT_MAYOR\n";
            ?>
        </div>
        
        <h2>🔍 Fonctionnalités du cachet électronique</h2>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 20px 0;">
            <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #28a745;">
                <h4 style="color: #28a745; margin-top: 0;">✅ Sécurité</h4>
                <ul style="margin: 0; padding-left: 20px;">
                    <li>Code de vérification unique</li>
                    <li>Horodatage précis</li>
                    <li>Nom du signataire (PCT_MAYOR)</li>
                    <li>Autorité certificatrice</li>
                </ul>
            </div>
            
            <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #007bff;">
                <h4 style="color: #007bff; margin-top: 0;">🎨 Présentation</h4>
                <ul style="margin: 0; padding-left: 20px;">
                    <li>Design professionnel</li>
                    <li>Intégration transparente</li>
                    <li>Fallback HTML/CSS</li>
                    <li>Responsive et accessible</li>
                </ul>
            </div>
        </div>
        
        <h2>📊 Templates mis à jour</h2>
        
        <div class="code-block">
            Templates modifiés avec cachet électronique:
            ✅ extrait-naissance.blade.php
            ✅ certificat-mariage.blade.php
            ⏳ certificat-celibat.blade.php (à mettre à jour)
            ⏳ attestation-domicile.blade.php (à mettre à jour)
            ⏳ certificat-deces.blade.php (à mettre à jour)
            ⏳ declaration-naissance.blade.php (à mettre à jour)
            ⏳ legalisation.blade.php (à mettre à jour)
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="../" class="button">🏠 Retour à l'accueil</a>
            <a href="test-signature-cachet.php" class="button">📋 Test signature classique</a>
            <a href="#" class="button" onclick="window.print()">🖨️ Imprimer cette page</a>
        </div>
        
        <hr style="margin: 30px 0;">
        
        <div style="text-align: center; color: #666; font-size: 12px;">
            <p><strong>PCT_MAYOR - Cachet Électronique</strong></p>
            <p>Test de configuration - Généré le <?php echo date('d/m/Y à H:i:s'); ?></p>
            <p>Système de certification électronique pour documents officiels</p>
        </div>
    </div>
</body>
</html>
