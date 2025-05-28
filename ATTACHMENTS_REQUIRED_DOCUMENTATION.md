# Documentation sur les pièces jointes obligatoires

## Modifications apportées

1. **Mise à jour du formulaire de création de demande**
   - Ajout de l'attribut `required` sur le champ des pièces jointes
   - Remplacement du texte "(optionnel)" par une indication visuelle de champ obligatoire
   - Ajout d'un message d'aide pour guider l'utilisateur
   - Ajout d'une validation côté client via JavaScript

2. **Mise à jour du contrôleur RequestController**
   - Modification des règles de validation pour rendre les pièces jointes obligatoires
   - Ajout de messages d'erreur personnalisés
   - Amélioration du traitement des fichiers avec plus d'informations stockées
   - Vérification supplémentaire de la présence de pièces jointes
   - Mise à jour du message de confirmation avec le nombre de pièces jointes

3. **Mise à jour de la validation**
   - Validation du format des fichiers (PDF, JPG, PNG)
   - Validation de la taille des fichiers (max 2 Mo)
   - Obligation d'avoir au moins un fichier joint

## Impact sur l'expérience utilisateur

1. **Avantages**
   - Réduction des demandes incomplètes
   - Accélération du traitement des demandes par les agents
   - Amélioration de la qualité des demandes soumises

2. **Inconvénients potentiels**
   - Augmentation de la complexité pour les utilisateurs
   - Possible augmentation des échecs de soumission si les utilisateurs n'ont pas de documents à joindre

## Messages d'erreur ajoutés

- "Veuillez joindre au moins un document à votre demande."
- "Veuillez sélectionner un document associé à votre demande."
- "Le fichier doit être au format PDF, JPG ou PNG."
- "La taille du fichier ne doit pas dépasser 2 Mo."

## Recommandations pour les citoyens

1. Préparez vos documents avant de commencer à remplir le formulaire
2. Assurez-vous que vos fichiers sont au format PDF, JPG ou PNG
3. Vérifiez que la taille de chaque fichier ne dépasse pas 2 Mo
4. Si un fichier est trop volumineux, essayez de le compresser avant de le télécharger
