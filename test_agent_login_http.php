<?php

echo "=== Test de connexion agent via HTTP ===\n\n";

// Configuration
$baseUrl = 'http://127.0.0.1:8001';
$cookieFile = tempnam(sys_get_temp_dir(), 'agent_test_cookies');

function makeRequest($url, $postData = null, $cookieFile = null, $followRedirects = true) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $followRedirects);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    if ($cookieFile) {
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    }

    if ($postData) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded'
        ]);
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    return [
        'code' => $httpCode,
        'response' => $response,
        'error' => $error
    ];
}

try {
    // 1. Accéder à la page de login pour récupérer le token CSRF
    echo "1. Récupération du token CSRF...\n";

    $result = makeRequest("$baseUrl/connexion", null, $cookieFile, true);

    if ($result['code'] == 200) {
        echo "✅ Page de login récupérée\n";

        // Extraire le token CSRF
        if (preg_match('/name="_token" value="([^"]+)"/', $result['response'], $matches)) {
            $csrfToken = $matches[1];
            echo "✅ Token CSRF récupéré: " . substr($csrfToken, 0, 20) . "...\n";
        } else {
            echo "❌ Token CSRF non trouvé\n";
            exit(1);
        }
    } else {
        echo "❌ Impossible d'accéder à la page de login (code: {$result['code']})\n";
        exit(1);
    }

    // 2. Tentative de connexion agent
    echo "\n2. Connexion en tant qu'agent...\n";

    $loginData = http_build_query([
        '_token' => $csrfToken,
        'email' => 'agent@pct-uvci.ci',
        'password' => 'password123',
        'role' => 'agent'
    ]);

    $result = makeRequest("$baseUrl/connexion", $loginData, $cookieFile);

    echo "Code de réponse: {$result['code']}\n";

    if ($result['code'] == 302) {
        // Extraire la redirection
        if (preg_match('/Location: (.+)/i', $result['response'], $matches)) {
            $redirectUrl = trim($matches[1]);
            echo "✅ Connexion réussie! Redirection vers: $redirectUrl\n";

            // Vérifier si c'est une redirection vers agent ou dashboard
            if (strpos($redirectUrl, '/agent/dashboard') !== false) {
                echo "🎉 Redirection directe vers agent/dashboard!\n";
            } elseif (strpos($redirectUrl, '/dashboard') !== false) {
                echo "✅ Redirection vers dashboard général\n";
            } else {
                echo "⚠️  Redirection inattendue: $redirectUrl\n";
            }
        } else {
            echo "✅ Connexion réussie (redirection sans Location header)\n";
        }
    } else {
        echo "❌ Échec de la connexion (code: {$result['code']})\n";
        echo "Début de la réponse:\n" . substr($result['response'], 0, 500) . "\n";
        exit(1);
    }

    // 3. Tester l'accès au dashboard agent
    echo "\n3. Test d'accès au dashboard agent...\n";

    $result = makeRequest("$baseUrl/agent/dashboard", null, $cookieFile);

    echo "Code de réponse: {$result['code']}\n";

    if ($result['code'] == 200) {
        echo "🎉 SUCCÈS COMPLET! Dashboard agent accessible!\n";

        // Vérifier le contenu de la réponse
        if (strpos($result['response'], 'Dashboard Agent') !== false ||
            strpos($result['response'], 'Agent Dashboard') !== false ||
            strpos($result['response'], 'agent') !== false) {
            echo "✅ Contenu du dashboard agent confirmé\n";
        } else {
            echo "⚠️  Dashboard accessible mais contenu à vérifier\n";
        }

    } elseif ($result['code'] == 302) {
        echo "⚠️  Redirection depuis dashboard agent\n";
        if (preg_match('/Location: (.+)/i', $result['response'], $matches)) {
            $redirectUrl = trim($matches[1]);
            echo "Redirection vers: $redirectUrl\n";

            if (strpos($redirectUrl, '/connexion') !== false) {
                echo "❌ Redirection vers login - problème d'authentification\n";
            }
        }
    } else {
        echo "❌ Erreur d'accès au dashboard (code: {$result['code']})\n";
    }

    // 4. Test des autres routes agent
    echo "\n4. Test d'autres routes agent...\n";

    $agentRoutes = [
        '/agent/requests' => 'Liste des demandes',
    ];

    foreach ($agentRoutes as $route => $description) {
        echo "  Test de $route ($description):\n";
        $result = makeRequest("$baseUrl$route", null, $cookieFile);
        echo "    Code: {$result['code']} - " . ($result['code'] == 200 ? "✅ OK" : ($result['code'] == 302 ? "➡️ Redirigé" : "❌ Erreur")) . "\n";
    }

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
} finally {
    // Nettoyer le fichier de cookies
    if (file_exists($cookieFile)) {
        unlink($cookieFile);
    }
}

echo "\n=== Fin du test ===\n";
