<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire - Attestation de Domicile</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .form-section { margin-bottom: 20px; }
        .form-row { margin-bottom: 15px; }
        label { font-weight: bold; }
        input, select, textarea { border: 1px solid #ccc; padding: 5px; margin-left: 10px; }
        .signature-section { margin-top: 40px; text-align: right; }
        .footer { margin-top: 30px; font-size: 12px; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h2>FORMULAIRE DE DEMANDE D'ATTESTATION DE DOMICILE</h2>
        <p>Mairie de [Nom de la commune]</p>
    </div>

    <form>
        <div class="form-section">
            <h3>INFORMATIONS PERSONNELLES</h3>
            
            <div class="form-row">
                <label>Nom :</label>
                <input type="text" style="width: 300px;" />
            </div>
            
            <div class="form-row">
                <label>Prénoms :</label>
                <input type="text" style="width: 300px;" />
            </div>
            
            <div class="form-row">
                <label>Date de naissance :</label>
                <input type="date" />
            </div>
            
            <div class="form-row">
                <label>Lieu de naissance :</label>
                <input type="text" style="width: 300px;" />
            </div>
            
            <div class="form-row">
                <label>Nationalité :</label>
                <input type="text" />
            </div>
            
            <div class="form-row">
                <label>Profession :</label>
                <input type="text" />
            </div>
            
            <div class="form-row">
                <label>Numéro CNI :</label>
                <input type="text" />
            </div>
        </div>

        <div class="form-section">
            <h3>INFORMATIONS DE DOMICILE</h3>
            
            <div class="form-row">
                <label>Adresse complète :</label>
                <textarea style="width: 400px; height: 60px;"></textarea>
            </div>
            
            <div class="form-row">
                <label>Quartier/Secteur :</label>
                <input type="text" />
            </div>
            
            <div class="form-row">
                <label>Depuis quand habitez-vous à cette adresse :</label>
                <input type="text" />
            </div>
        </div>

        <div class="form-section">
            <h3>USAGE DE L'ATTESTATION</h3>
            
            <div class="form-row">
                <label>Motif de la demande :</label>
                <textarea style="width: 400px; height: 60px;" placeholder="Précisez l'usage prévu de l'attestation"></textarea>
            </div>
        </div>

        <div class="form-section">
            <h3>DOCUMENTS À JOINDRE</h3>
            <ul>
                <li>Copie de votre CNI ou passeport</li>
                <li>Justificatif de domicile (facture d'eau, électricité ou téléphone récente)</li>
                <li>Photo d'identité récente</li>
            </ul>
        </div>

        <div class="signature-section">
            <p>Fait à ........................., le ....../....../.........</p>
            <p>Signature du demandeur :</p>
            <div style="height: 60px; border: 1px solid #ccc; width: 200px; margin-left: auto;"></div>
        </div>
    </form>

    <div class="footer">
        <p><strong>Note :</strong> Tous les champs doivent être remplis. Les documents justificatifs sont obligatoires.</p>
        <p>Ce formulaire peut être rempli en ligne sur notre plateforme numérique.</p>
    </div>
</body>
</html>
