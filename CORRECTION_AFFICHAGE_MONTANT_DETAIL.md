# 🔧 Correction Affichage Montant - Détail Demande

## 📋 Problème Identifié

Dans l'interface de détail de la demande (`/citizen-request-standalone/{id}`), le montant affiché était toujours de **5 000 FCFA** même après avoir effectué un paiement avec le montant correct.

## 🎯 Fichier Corrigé

**Fichier:** `resources/views/citizen/request-detail_standalone.blade.php`
**Ligne:** 345

## ✅ Correction Apportée

### Avant
```php
<span class="font-semibold">5 000 FCFA</span>
```

### Après
```php
@php
    $amount = 500; // Montant par défaut
    
    // Essayer d'abord de récupérer le montant du paiement existant
    $payment = $request->payments()->latest()->first();
    if ($payment && $payment->amount) {
        $amount = $payment->amount;
    } else {
        // Sinon, calculer le montant basé sur le type de document
        $amount = \App\Services\PaymentService::getPriceForDocumentType($request->type ?? 'default');
    }
@endphp
<span class="font-semibold">{{ number_format($amount, 0, ',', ' ') }} FCFA</span>
```

## 🔍 Logique de Calcul

La nouvelle logique suit cette priorité :

1. **Paiement existant** : Si un paiement a été effectué, utilise le montant réel payé
2. **Calcul par type** : Sinon, utilise le service PaymentService pour calculer le montant selon le type de document
3. **Fallback** : Par défaut, 500 FCFA (prix des timbres)

## 💰 Montants Possibles

| **Type de Document** | **Montant (FCFA)** |
|---------------------|-------------------|
| timbre | 500 |
| acte_de_naissance | 2,500 |
| certificat_de_nationalite | 5,000 |
| carte_nationale_identite | 5,000 |
| extrait_de_casier_judiciaire | 3,000 |
| passeport | 40,000 |
| certificat_de_residence | 1,500 |
| acte_de_mariage | 3,500 |
| acte_de_deces | 500 |
| **par défaut** | **500** |

## 🎉 Résultat

✅ **Avant** : Affichage fixe de 5 000 FCFA
✅ **Après** : Affichage dynamique du montant réel payé ou calculé

## 🔗 Route Concernée

- **URL** : `/citizen-request-standalone/{id}`
- **Contrôleur** : `Citizen\DashboardController@showRequestStandalone`
- **Vue** : `citizen.request-detail_standalone`

## 📝 Impact

- ✅ Affichage correct du montant dans les détails de la demande
- ✅ Cohérence avec les montants de paiement réels
- ✅ Utilisation du service PaymentService pour les calculs
- ✅ Robustesse avec fallback sur 500 FCFA

## 🔮 Améliorations

Cette correction assure que :
- Le montant affiché correspond toujours au montant réellement payé
- Les nouveaux types de documents afficheront automatiquement le bon montant
- L'historique des paiements est respecté
- Les demandes sans paiement affichent le montant attendu
