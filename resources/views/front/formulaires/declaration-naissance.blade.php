<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire - Déclaration de Naissance</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .form-section { margin-bottom: 20px; }
        .form-row { margin-bottom: 15px; }
        label { font-weight: bold; }
        input, select, textarea { border: 1px solid #ccc; padding: 5px; margin-left: 10px; }
        .signature-section { margin-top: 40px; text-align: right; }
        .footer { margin-top: 30px; font-size: 12px; text-align: center; }
        .parent-section { border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>FORMULAIRE DE DÉCLARATION DE NAISSANCE</h2>
        <p>Mairie de [Nom de la commune]</p>
    </div>

    <form>
        <div class="form-section">
            <h3>INFORMATIONS SUR L'ENFANT</h3>
            
            <div class="form-row">
                <label>Nom de famille :</label>
                <input type="text" style="width: 300px;" />
            </div>
            
            <div class="form-row">
                <label>Prénoms souhaités :</label>
                <input type="text" style="width: 300px;" placeholder="Séparez les prénoms par des virgules" />
            </div>
            
            <div class="form-row">
                <label>Date de naissance :</label>
                <input type="date" />
            </div>
            
            <div class="form-row">
                <label>Heure de naissance :</label>
                <input type="time" />
            </div>
            
            <div class="form-row">
                <label>Lieu de naissance :</label>
                <input type="text" style="width: 300px;" />
            </div>
            
            <div class="form-row">
                <label>Sexe :</label>
                <select>
                    <option value="">Choisir</option>
                    <option value="M">Masculin</option>
                    <option value="F">Féminin</option>
                </select>
            </div>
        </div>

        <div class="form-section">
            <h3>INFORMATIONS SUR LES PARENTS</h3>
            
            <div class="parent-section">
                <h4>PÈRE</h4>
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
                    <label>Nationalité :</label>
                    <input type="text" />
                </div>
                <div class="form-row">
                    <label>Profession :</label>
                    <input type="text" />
                </div>
                <div class="form-row">
                    <label>Domicile :</label>
                    <textarea style="width: 400px; height: 40px;"></textarea>
                </div>
            </div>
            
            <div class="parent-section">
                <h4>MÈRE</h4>
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
                    <label>Nationalité :</label>
                    <input type="text" />
                </div>
                <div class="form-row">
                    <label>Profession :</label>
                    <input type="text" />
                </div>
                <div class="form-row">
                    <label>Domicile :</label>
                    <textarea style="width: 400px; height: 40px;"></textarea>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3>INFORMATIONS SUR LES TÉMOINS</h3>
            
            <div style="border: 1px solid #ddd; padding: 15px; margin-bottom: 15px;">
                <h4>TÉMOIN 1</h4>
                <div class="form-row">
                    <label>Nom et prénoms :</label>
                    <input type="text" style="width: 300px;" />
                </div>
                <div class="form-row">
                    <label>Date de naissance :</label>
                    <input type="date" />
                </div>
                <div class="form-row">
                    <label>Profession :</label>
                    <input type="text" />
                </div>
                <div class="form-row">
                    <label>Domicile :</label>
                    <input type="text" style="width: 300px;" />
                </div>
            </div>
            
            <div style="border: 1px solid #ddd; padding: 15px;">
                <h4>TÉMOIN 2</h4>
                <div class="form-row">
                    <label>Nom et prénoms :</label>
                    <input type="text" style="width: 300px;" />
                </div>
                <div class="form-row">
                    <label>Date de naissance :</label>
                    <input type="date" />
                </div>
                <div class="form-row">
                    <label>Profession :</label>
                    <input type="text" />
                </div>
                <div class="form-row">
                    <label>Domicile :</label>
                    <input type="text" style="width: 300px;" />
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3>DOCUMENTS À JOINDRE</h3>
            <ul>
                <li>Certificat de naissance de l'hôpital</li>
                <li>CNI des parents</li>
                <li>Certificat de mariage des parents (si applicable)</li>
                <li>CNI des témoins</li>
                <li>Justificatifs de domicile</li>
            </ul>
        </div>

        <div class="signature-section">
            <p>Fait à ........................., le ....../....../.........</p>
            <p>Signature du déclarant :</p>
            <div style="height: 60px; border: 1px solid #ccc; width: 200px; margin-left: auto;"></div>
        </div>
    </form>

    <div class="footer">
        <p><strong>Note :</strong> La déclaration doit être faite dans les 45 jours suivant la naissance.</p>
        <p>Tous les champs doivent être remplis. Les documents justificatifs sont obligatoires.</p>
    </div>
</body>
</html>
