<!DOCTYPE html>
<html>
<head>
    <title>Confirmation de création de compte</title>
</head>
<body>
    <h1>Bienvenue sur PCT-UVCI</h1>

    <p>Cher(e) {{ $prenoms }} {{ $nom }},</p>

    <p>Votre compte a été créé avec succès. Pour activer votre compte, veuillez cliquer sur le lien ci-dessous :</p>

    <p>
        <a href="{{ url('confirm/'.$code) }}">Cliquez ici pour confirmer votre compte</a>
    </p>

    <p>Si le lien ci-dessus ne fonctionne pas, copiez et collez l'URL suivante dans votre navigateur :</p>
    <p>{{ url('confirm/'.$code) }}</p>

    <p>
        Cordialement,<br>
        L'équipe PCT-UVCI
    </p>
</body>
</html>
