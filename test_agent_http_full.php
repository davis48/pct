<?php

// Test agent login via HTTP requests
$baseUrl = 'http://127.0.0.1:8000';

function makeRequest($url, $data = null, $cookies = '') {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_COOKIE, $cookies);

    if ($data) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return ['code' => $httpCode, 'response' => $response];
}

echo "=== Testing Agent Login Process ===\n\n";

// Step 1: Get login page to get CSRF token
echo "1. Getting login page...\n";
$loginPageResponse = makeRequest($baseUrl . '/connexion');
echo "Status: " . $loginPageResponse['code'] . "\n";

// Extract CSRF token and session cookie
preg_match('/Set-Cookie: ([^;]+)/', $loginPageResponse['response'], $sessionMatch);
preg_match('/<input[^>]*name="_token"[^>]*value="([^"]*)"/', $loginPageResponse['response'], $tokenMatch);

$sessionCookie = $sessionMatch[1] ?? '';
$csrfToken = $tokenMatch[1] ?? '';

echo "Session cookie: " . substr($sessionCookie, 0, 50) . "...\n";
echo "CSRF token: " . substr($csrfToken, 0, 20) . "...\n\n";

if (!$csrfToken) {
    echo "ERROR: Could not extract CSRF token from login page\n";
    exit;
}

// Step 2: Attempt agent login
echo "2. Attempting agent login...\n";
$loginData = [
    '_token' => $csrfToken,
    'email' => 'agent@pct-uvci.ci',
    'password' => 'password123',
    'role' => 'agent'
];

$loginResponse = makeRequest($baseUrl . '/connexion', $loginData, $sessionCookie);
echo "Login status: " . $loginResponse['code'] . "\n";

// Extract new session cookie if redirected
if (preg_match('/Set-Cookie: ([^;]+)/', $loginResponse['response'], $newSessionMatch)) {
    $sessionCookie = $newSessionMatch[1];
}

// Check if redirected to agent dashboard
if ($loginResponse['code'] == 302) {
    preg_match('/Location: (.+)/', $loginResponse['response'], $locationMatch);
    $redirectUrl = trim($locationMatch[1] ?? '');
    echo "Redirected to: $redirectUrl\n\n";

    if (strpos($redirectUrl, '/agent/dashboard') !== false) {
        echo "3. Testing agent dashboard access...\n";
        $dashboardResponse = makeRequest($baseUrl . '/agent/dashboard', null, $sessionCookie);
        echo "Dashboard status: " . $dashboardResponse['code'] . "\n";

        if ($dashboardResponse['code'] == 200) {
            echo "SUCCESS: Agent can access dashboard!\n";
        } else {
            echo "ERROR: Agent cannot access dashboard\n";
            echo "Response headers:\n";
            $headers = substr($dashboardResponse['response'], 0, strpos($dashboardResponse['response'], "\r\n\r\n"));
            echo $headers . "\n";
        }
    } else {
        echo "ERROR: Not redirected to agent dashboard\n";
    }
} else {
    echo "ERROR: Login failed or unexpected response\n";
    echo "Response body:\n";
    $body = substr($loginResponse['response'], strpos($loginResponse['response'], "\r\n\r\n") + 4);
    echo substr($body, 0, 500) . "\n";
}
