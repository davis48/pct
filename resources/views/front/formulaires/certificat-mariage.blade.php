<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire - Certificat de Mariage</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .form-section { margin-bottom: 20px; }
        .form-row { margin-bottom: 15px; }
        label { font-weight: bold; }
        input, select, textarea { border: 1px solid #ccc; padding: 5px; margin-left: 10px; }
        .signature-section { margin-top: 40px; text-align: right; }
        .footer { margin-top: 30px; font-size: 12px; text-align: center; }
        .spouse-section { border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>FORMULAIRE DE DEMANDE DE CERTIFICAT DE MARIAGE</h2>
        <p>Mairie de [Nom de la commune]</p>
    </div>

    <form>
        <div class="form-section">
            <h3>INFORMATIONS SUR LE MARIAGE</h3>
            
            <div class="form-row">
                <label>Date du mariage :</label>
                <input type="date" />
            </div>
            
            <div class="form-row">
                <label>Lieu du mariage :</label>
                <input type="text" style="width: 300px;" />
            </div>
            
            <div class="form-row">
                <label>Numéro d'acte de mariage :</label>
                <input type="text" />
            </div>
        </div>

        <div class="form-section">
            <h3>INFORMATIONS SUR LES ÉPOUX</h3>
            
            <div class="spouse-section">
                <h4>ÉPOUX</h4>
                <div class="form-row">
                    <label>Nom et prénoms :</label>
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
                    <label>Profession :</label>
                    <input type="text" />
                </div>
                <div class="form-row">
                    <label>Nom du père :</label>
                    <input type="text" style="width: 300px;" />
                </div>
                <div class="form-row">
                    <label>Nom de la mère :</label>
                    <input type="text" style="width: 300px;" />
                </div>
            </div>
            
            <div class="spouse-section">
                <h4>ÉPOUSE</h4>
                <div class="form-row">
                    <label>Nom et prénoms :</label>
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
                    <label>Profession :</label>
                    <input type="text" />
                </div>
                <div class="form-row">
                    <label>Nom du père :</label>
                    <input type="text" style="width: 300px;" />
                </div>
                <div class="form-row">
                    <label>Nom de la mère :</label>
                    <input type="text" style="width: 300px;" />
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3>INFORMATIONS SUR LE DEMANDEUR</h3>
            
            <div class="form-row">
                <label>Qualité du demandeur :</label>
                <select>
                    <option value="">Choisir</option>
                    <option value="epoux">Époux</option>
                    <option value="epouse">Épouse</option>
                    <option value="enfant">Enfant</option>
                    <option value="parent">Parent</option>
                    <option value="autre">Autre</option>
                </select>
            </div>
            
            <div class="form-row">
                <label>Nom et prénoms du demandeur :</label>
                <input type="text" style="width: 300px;" />
            </div>
            
            <div class="form-row">
                <label>Adresse :</label>
                <textarea style="width: 400px; height: 60px;"></textarea>
            </div>
            
            <div class="form-row">
                <label>Téléphone :</label>
                <input type="text" />
            </div>
        </div>

        <div class="form-section">
            <h3>USAGE DU CERTIFICAT</h3>
            
            <div class="form-row">
                <label>Motif de la demande :</label>
                <textarea style="width: 400px; height: 60px;" placeholder="Précisez l'usage prévu du certificat de mariage"></textarea>
            </div>
        </div>

        <div class="form-section">
            <h3>DOCUMENTS À JOINDRE</h3>
            <ul>
                <li>Copie CNI des deux époux</li>
                <li>Certificat de célibat de chaque époux</li>
                <li>Certificat médical prénuptial</li>
                <li>Photos d'identité récentes (4 par personne)</li>
                <li>Procuration si la demande est faite par un tiers</li>
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
