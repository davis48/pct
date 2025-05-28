<?php

echo "=== Test d'accès aux routes agent via HTTP ===\n\n";

// 1. Test direct de l'URL agent/dashboard
echo "1. Test de l'URL agent/dashboard:\n";

$url = 'http://127.0.0.1:8001/agent/dashboard';
echo "URL testée: $url\n";

// Utiliser curl pour faire la requête
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    echo "❌ Erreur cURL: $error\n";
} else {
    echo "Code de réponse HTTP: $httpCode\n";

    if ($httpCode == 404) {
        echo "❌ Erreur 404 - Route non trouvée\n";
    } elseif ($httpCode == 302) {
        echo "✅ Redirection (probablement vers login) - Route existe\n";
    } elseif ($httpCode == 200) {
        echo "✅ Succès - Route accessible\n";
    } else {
        echo "⚠️  Code de réponse inattendu: $httpCode\n";
    }

    // Afficher les en-têtes
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    if ($response !== false) {
        list($headers, $body) = explode("\r\n\r\n", $response, 2);
        echo "En-têtes de réponse:\n";
        echo $headers . "\n\n";

        // Si redirection, montrer où
        if (preg_match('/Location: (.+)/i', $headers, $matches)) {
            echo "Redirection vers: " . trim($matches[1]) . "\n";
        }
    }
}

// 2. Test avec authentification simulée
echo "\n2. Test avec session/cookies:\n";

// Créer un fichier temporaire pour les cookies
$cookieFile = tempnam(sys_get_temp_dir(), 'laravel_cookies');

// D'abord, obtenir la page de login pour récupérer le token CSRF
echo "2.1. Récupération du token CSRF...\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1:8001/connexion');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);

$loginPage = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200 && $loginPage) {
    echo "✅ Page de login récupérée\n";

    // Extraire le token CSRF
    $csrfToken = '';
    if (preg_match('/name="_token" value="([^"]+)"/', $loginPage, $matches)) {
        $csrfToken = $matches[1];
        echo "✅ Token CSRF récupéré: " . substr($csrfToken, 0, 20) . "...\n";
    } else {
        echo "❌ Token CSRF non trouvé\n";
    }

    // 2.2. Tentative de connexion
    if ($csrfToken) {
        echo "\n2.2. Tentative de connexion agent...\n";

        $postData = http_build_query([
            '_token' => $csrfToken,
            'email' => 'agent@pct-uvci.ci',
            'password' => 'password123',
            'role' => 'agent'
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1:8001/connexion');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded',
            'X-Requested-With: XMLHttpRequest'
        ]);

        $loginResponse = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        echo "Code de réponse login: $httpCode\n";

        if ($httpCode == 302) {
            echo "✅ Redirection après login (connexion réussie)\n";

            // Extraire la location de redirection
            if (preg_match('/Location: (.+)/i', $loginResponse, $matches)) {
                $redirectUrl = trim($matches[1]);
                echo "Redirection vers: $redirectUrl\n";
            }

            // 2.3. Maintenant tester l'accès à agent/dashboard avec les cookies
            echo "\n2.3. Test d'accès à agent/dashboard avec authentification...\n";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1:8001/agent/dashboard');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);

            $dashboardResponse = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            echo "Code de réponse dashboard: $httpCode\n";

            if ($httpCode == 404) {
                echo "❌ Toujours erreur 404 - Les routes agent ne sont vraiment pas chargées\n";
            } elseif ($httpCode == 200) {
                echo "✅ Succès! Dashboard agent accessible\n";
            } elseif ($httpCode == 302) {
                echo "⚠️  Redirection - vérifier où\n";
                if (preg_match('/Location: (.+)/i', $dashboardResponse, $matches)) {
                    echo "Redirection vers: " . trim($matches[1]) . "\n";
                }
            } else {
                echo "Code de réponse inattendu: $httpCode\n";
                echo "Début de la réponse:\n" . substr($dashboardResponse, 0, 500) . "\n";
            }

        } else {
            echo "❌ Échec de la connexion (code: $httpCode)\n";
            echo "Début de la réponse:\n" . substr($loginResponse, 0, 500) . "\n";
        }
    }
} else {
    echo "❌ Impossible de récupérer la page de login (code: $httpCode)\n";
}

// Nettoyer le fichier de cookies
if (file_exists($cookieFile)) {
    unlink($cookieFile);
}

echo "\n=== Fin du test HTTP ===\n";
