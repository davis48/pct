<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test - Signature et Cachet Officiel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c5aa0;
            text-align: center;
            border-bottom: 3px solid #2c5aa0;
            padding-bottom: 15px;
        }
        .status {
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }
        .success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
        }
        .error {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        .image-preview {
            border: 2px dashed #ccc;
            padding: 20px;
            text-align: center;
            margin: 15px 0;
            background-color: #fafafa;
        }
        .image-preview img {
            max-width: 200px;
            max-height: 150px;
            border: 1px solid #ddd;
            padding: 10px;
            background: white;
        }
        .config-info {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            font-family: monospace;
        }
        .button {
            display: inline-block;
            background-color: #2c5aa0;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 5px;
        }
        .button:hover {
            background-color: #1e3d6f;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏛️ Test - Signature et Cachet Officiel</h1>
        
        <?php
        // Configuration
        $signaturePath = 'images/official/signature_maire.png';
        $sealPath = 'images/official/cachet_officiel.png';
        $signatureExists = file_exists($signaturePath);
        $sealExists = file_exists($sealPath);
        
        // Variables d'environnement simulées
        $mayorName = $_ENV['MAYOR_NAME'] ?? 'M. Jean KOUASSI';
        $municipalityName = $_ENV['MUNICIPALITY_NAME'] ?? 'Mairie de Cocody';
        ?>
        
        <h2>📋 État du système</h2>
        
        <div class="status <?php echo ($signatureExists && $sealExists) ? 'success' : 'warning'; ?>">
            <strong>État général :</strong>
            <?php if ($signatureExists && $sealExists): ?>
                ✅ Système configuré - Signature et cachet disponibles
            <?php elseif ($signatureExists || $sealExists): ?>
                ⚠️ Configuration partielle - Une image manquante
            <?php else: ?>
                ❌ Configuration incomplète - Images manquantes
            <?php endif; ?>
        </div>
        
        <h2>🖼️ Images officielles</h2>
        
        <h3>Signature du maire</h3>
        <div class="image-preview">
            <?php if ($signatureExists): ?>
                <img src="<?php echo $signaturePath; ?>" alt="Signature du maire">
                <div class="status success">✅ Signature trouvée</div>
            <?php else: ?>
                <div class="status error">❌ Fichier manquant : <?php echo $signaturePath; ?></div>
                <p>Placez la signature dans le fichier signature_maire.png</p>
            <?php endif; ?>
        </div>
        
        <h3>Cachet officiel</h3>
        <div class="image-preview">
            <?php if ($sealExists): ?>
                <img src="<?php echo $sealPath; ?>" alt="Cachet officiel">
                <div class="status success">✅ Cachet trouvé</div>
            <?php else: ?>
                <div class="status error">❌ Fichier manquant : <?php echo $sealPath; ?></div>
                <p>Placez le cachet dans le fichier cachet_officiel.png</p>
            <?php endif; ?>
        </div>
        
        <h2>⚙️ Configuration</h2>
        
        <div class="config-info">
            <strong>Nom du maire :</strong> <?php echo htmlspecialchars($mayorName); ?><br>
            <strong>Municipalité :</strong> <?php echo htmlspecialchars($municipalityName); ?><br>
            <strong>Date du test :</strong> <?php echo date('d/m/Y à H:i'); ?>
        </div>
        
        <h2>📄 Exemples disponibles</h2>
        
        <div class="status warning">
            <strong>Note :</strong> Des exemples SVG sont disponibles dans le répertoire pour vous aider à créer vos propres images.
        </div>
        
        <?php if (file_exists('images/official/signature_maire_exemple.svg')): ?>
            <p>✅ Exemple de signature disponible</p>
        <?php endif; ?>
        
        <?php if (file_exists('images/official/cachet_officiel_exemple.svg')): ?>
            <p>✅ Exemple de cachet disponible</p>
        <?php endif; ?>
        
        <h2>🚀 Actions suivantes</h2>
        
        <?php if (!$signatureExists || !$sealExists): ?>
            <div class="status warning">
                <strong>Pour terminer la configuration :</strong>
                <ol>
                    <li>Obtenez la vraie signature du maire (PNG, fond transparent)</li>
                    <li>Obtenez le vrai cachet officiel (PNG, fond transparent)</li>
                    <li>Placez-les dans public/images/official/</li>
                    <li>Testez la génération d'un document PDF</li>
                </ol>
            </div>
        <?php else: ?>
            <div class="status success">
                <strong>Configuration terminée !</strong>
                <p>Vous pouvez maintenant générer des documents avec signature et cachet officiels.</p>
            </div>
        <?php endif; ?>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="../" class="button">🏠 Retour à l'accueil</a>
            <a href="setup-official-images.ps1" class="button" download>📁 Script PowerShell</a>
        </div>
        
        <hr style="margin: 30px 0;">
        
        <div style="text-align: center; color: #666; font-size: 12px;">
            <p>Test de configuration - Système de signature et cachet officiel</p>
            <p>Généré le <?php echo date('d/m/Y à H:i:s'); ?></p>
        </div>
    </div>
</body>
</html>
