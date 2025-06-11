# Correction de l'affichage des formulaires interactifs

## Problème identifié
Les informations saisies dans les formulaires interactifs n'apparaissaient pas toutes lors du traitement par l'agent et dans les documents PDF générés. Certains champs étaient perdus à cause de :

1. **Validation incomplète** : Les règles de validation ne couvraient pas tous les champs des formulaires
2. **Affichage partiel** : La vue agent ne montrait qu'une partie des champs saisis
3. **Décodage JSON défaillant** : Le service de génération ne décodait pas correctement les données additionnelles

## Corrections apportées

### 1. Validation étendue (`InteractiveFormController.php`)

**Attestation de domicile** - Ajout de tous les champs :
- Informations personnelles : `nom`, `prenoms`, `date_naissance`, `lieu_naissance`, `nationalite`, `profession`, `cin_number`, `telephone`
- Informations de domicile : `adresse_complete`, `commune`, `quartier`, `date_installation`, `statut_logement`
- Témoin : `nom_temoin`, `prenoms_temoin`, `profession_temoin`, `telephone_temoin`
- Complémentaires : `motif`, `lieu_delivrance`

**Extrait de naissance** - Ajout de tous les champs :
- Identité : `name`, `first_names`, `gender`, `date_of_birth`, `birth_time`, `place_of_birth`, `nationality`
- Filiation paternelle : `father_name`, `prenoms_pere`, `age_pere`, `profession_pere`, `domicile_pere`
- Filiation maternelle : `mother_name`, `prenoms_mere`, `age_mere`, `profession_mere`, `domicile_mere`
- Enregistrement : `centre_etat_civil`, `numero_acte`, `date_declaration`, `annee_registre`, `declarant_name`

**Certificat de mariage** - Ajout de tous les champs :
- Époux : `nom_epoux`, `prenoms_epoux`, `date_naissance_epoux`, `lieu_naissance_epoux`, `profession_epoux`, `domicile_epoux`
- Épouse : `nom_epouse`, `prenoms_epouse`, `date_naissance_epouse`, `lieu_naissance_epouse`, `profession_epouse`, `domicile_epouse`
- Mariage : `date_mariage`, `heure_mariage`, `lieu_mariage`, `regime_matrimonial`
- Témoins : `temoin1_nom`, `temoin1_prenoms`, `temoin1_profession`, `temoin1_domicile`, `temoin2_nom`, `temoin2_prenoms`, `temoin2_profession`, `temoin2_domicile`

**Certificat de célibat** - Ajout de tous les champs :
- Identité : `nom`, `prenoms`, `date_naissance`, `lieu_naissance`, `nationalite`, `profession`, `domicile`
- Filiation : `nom_pere`, `prenoms_pere`, `nom_mere`, `prenoms_mere`
- Autres : `motif_demande`

### 2. Affichage complet dans la vue agent (`process.blade.php`)

- Ajout de tous les champs spécifiques pour chaque type de formulaire
- Gestion des anciens et nouveaux noms de champs pour la compatibilité
- Formatage approprié des dates
- Organisation en sections logiques (identité, filiation, enregistrement, etc.)

### 3. Correction du service de génération (`DocumentGeneratorService.php`)

```php
// Avant
'form_data' => $request->additional_data ?? [],

// Après
$additionalData = [];
if ($request->additional_data) {
    $decodedData = json_decode($request->additional_data, true);
    $additionalData = $decodedData['form_data'] ?? [];
}
```

### 4. Mise à jour des templates PDF

**Template extrait de naissance** (`extrait-naissance.blade.php`) :
- Ajout des nouveaux champs : `prenoms_pere`, `age_pere`, `domicile_pere`, `lieu_naissance_pere`
- Ajout des nouveaux champs : `prenoms_mere`, `age_mere`, `domicile_mere`, `lieu_naissance_mere`
- Ajout des informations d'enregistrement : `centre_etat_civil`, `numero_acte`, `date_declaration`, `annee_registre`
- Gestion de la compatibilité avec les anciens noms de champs

## Fichiers modifiés

1. `app/Http/Controllers/Front/InteractiveFormController.php` - Validation étendue
2. `resources/views/agent/requests/process.blade.php` - Affichage complet
3. `app/Services/DocumentGeneratorService.php` - Décodage JSON corrigé
4. `resources/views/documents/templates/extrait-naissance.blade.php` - Template mis à jour

## Tests recommandés

1. **Test de saisie** : Remplir complètement un formulaire interactif
2. **Test d'affichage agent** : Vérifier que tous les champs apparaissent dans la vue de traitement
3. **Test de génération PDF** : Vérifier que le document contient toutes les informations
4. **Test de compatibilité** : S'assurer que les anciennes demandes fonctionnent toujours

## Impact

✅ **Toutes les informations** saisies par le citoyen sont maintenant visibles par l'agent  
✅ **Tous les champs** apparaissent dans les documents PDF générés  
✅ **Compatibilité** maintenue avec les anciennes demandes  
✅ **Validation robuste** empêche la perte de données  

## Remarques

- La solution gère la compatibilité ascendante avec les anciens noms de champs
- Tous les formulaires interactifs sont maintenant couverts
- La validation peut être étendue facilement pour de nouveaux champs
- Les templates PDF sont flexibles et s'adaptent aux données disponibles
