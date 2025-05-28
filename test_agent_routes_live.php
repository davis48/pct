<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TESTING AGENT ROUTES FOR UNDEFINED KEY ERRORS ===\n\n";

// Test the agent routes by making HTTP requests
$baseUrl = 'http://127.0.0.1:8000';

// Get agent user for authentication
$agent = \App\Models\User::where('role', 'agent')->first();

if (!$agent) {
    echo "❌ No agent user found. Creating one...\n";
    $agent = \App\Models\User::create([
        'nom' => 'Agent',
        'prenoms' => 'Test',
        'email' => 'agent.test@example.com',
        'password' => bcrypt('password123'),
        'role' => 'agent',
        'date_naissance' => '1990-01-01',
        'genre' => 'M'
    ]);
    echo "✓ Agent user created\n";
}

// Initialize cURL session with cookie jar for maintaining login session
$cookieJar = tempnam(sys_get_temp_dir(), 'agent_test_cookies');

function makeCurlRequest($url, $postData = null, $cookieJar = null) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Agent Route Test');

    if ($cookieJar) {
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieJar);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieJar);
    }

    if ($postData) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    return [
        'response' => $response,
        'httpCode' => $httpCode,
        'error' => $error
    ];
}

echo "1. Testing agent login...\n";

// First get the login page to get CSRF token
$loginPageResponse = makeCurlRequest($baseUrl . '/login?role=agent', null, $cookieJar);

if ($loginPageResponse['httpCode'] === 200) {
    // Extract CSRF token from the response
    preg_match('/<meta name="csrf-token" content="([^"]+)"/', $loginPageResponse['response'], $matches);
    $csrfToken = $matches[1] ?? '';

    if ($csrfToken) {
        echo "✓ CSRF token obtained\n";

        // Attempt login
        $loginData = http_build_query([
            '_token' => $csrfToken,
            'email' => $agent->email,
            'password' => 'password123',
            'role' => 'agent'
        ]);

        $loginResponse = makeCurlRequest($baseUrl . '/authenticate', $loginData, $cookieJar);

        if ($loginResponse['httpCode'] === 302 || $loginResponse['httpCode'] === 200) {
            echo "✓ Agent login successful\n";
        } else {
            echo "⚠ Agent login returned HTTP " . $loginResponse['httpCode'] . "\n";
        }
    } else {
        echo "⚠ Could not extract CSRF token\n";
    }
} else {
    echo "❌ Could not access login page (HTTP " . $loginPageResponse['httpCode'] . ")\n";
}

echo "\n2. Testing agent routes...\n";

$routesToTest = [
    '/agent/dashboard' => 'Agent Dashboard',
    '/agent/citizens' => 'Citizens Management',
    '/agent/requests' => 'Requests Management',
    '/agent/documents' => 'Documents Management'
];

foreach ($routesToTest as $route => $description) {
    echo "Testing $description ($route)...\n";

    $response = makeCurlRequest($baseUrl . $route, null, $cookieJar);

    if ($response['error']) {
        echo "  ❌ cURL Error: " . $response['error'] . "\n";
        continue;
    }

    if ($response['httpCode'] === 200) {
        // Check for undefined array key errors in the response
        if (strpos($response['response'], 'Undefined array key') !== false) {
            echo "  ❌ Contains 'Undefined array key' error\n";

            // Extract the specific error
            preg_match('/Undefined array key ["\']([^"\']+)["\']/', $response['response'], $matches);
            if (isset($matches[1])) {
                echo "  ❌ Missing key: '" . $matches[1] . "'\n";
            }
        } else {
            echo "  ✅ No undefined array key errors found\n";
        }
    } elseif ($response['httpCode'] === 302) {
        echo "  ⚠ Redirected (HTTP 302) - might need authentication\n";
    } elseif ($response['httpCode'] === 403) {
        echo "  ⚠ Access forbidden (HTTP 403)\n";
    } else {
        echo "  ❌ HTTP Error: " . $response['httpCode'] . "\n";
    }
}

// Clean up cookie file
unlink($cookieJar);

echo "\n=== TEST COMPLETED ===\n";
echo "If all routes show '✅ No undefined array key errors found', then the fixes are successful!\n";
