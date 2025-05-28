<?php

$baseUrl = 'http://127.0.0.1:8000';

function makeRequest($url, $postData = null, $cookieJar = null) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); // Don't follow redirects automatically
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');

    if ($cookieJar) {
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieJar);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieJar);
    }

    if ($postData) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $redirectUrl = curl_getinfo($ch, CURLINFO_REDIRECT_URL);
    curl_close($ch);

    return [
        'code' => $httpCode,
        'response' => $response,
        'redirect' => $redirectUrl
    ];
}

echo "=== CORRECTED AGENT LOGIN TEST ===\n\n";

$cookieJar = tempnam(sys_get_temp_dir(), 'cookies');

// Step 1: Go to agent dashboard (should redirect to login)
echo "1. Accessing /agent/dashboard (should redirect to login)...\n";
$result = makeRequest($baseUrl . '/agent/dashboard', null, $cookieJar);
echo "Status: {$result['code']}\n";
echo "Redirect: {$result['redirect']}\n\n";

// Step 2: Follow redirect chain to role selection
if ($result['code'] == 302) {
    echo "2. Following redirect to login page...\n";
    $loginUrl = $result['redirect'];
    $result = makeRequest($loginUrl, null, $cookieJar);
    echo "Status: {$result['code']}\n";
    echo "Redirect: {$result['redirect']}\n\n";

    if ($result['code'] == 302) {
        echo "3. Following redirect to role selection...\n";
        $roleUrl = $result['redirect'];
        $result = makeRequest($roleUrl, null, $cookieJar);
        echo "Status: {$result['code']}\n";

        if ($result['code'] == 200) {
            echo "✓ Successfully reached role selection page\n\n";

            // Step 4: Select agent role (CORRECT URL with parameter)
            echo "4. Selecting agent role (accessing agent login form)...\n";
            $result = makeRequest($baseUrl . '/connexion?role=agent', null, $cookieJar);
            echo "Status: {$result['code']}\n";

            if ($result['code'] == 200) {
                echo "✓ Successfully reached agent login form\n";

                // Extract CSRF token
                preg_match('/<input[^>]*name="_token"[^>]*value="([^"]*)"/', $result['response'], $tokenMatch);
                $csrfToken = $tokenMatch[1] ?? null;

                if ($csrfToken) {
                    echo "✓ CSRF token extracted\n\n";

                    // Step 5: Submit login form
                    echo "5. Submitting agent login credentials...\n";
                    $loginData = [
                        '_token' => $csrfToken,
                        'email' => 'agent@pct-uvci.ci',
                        'password' => 'password123',
                        'role' => 'agent'
                    ];

                    $result = makeRequest($baseUrl . '/connexion', $loginData, $cookieJar);
                    echo "Status: {$result['code']}\n";
                    echo "Redirect: {$result['redirect']}\n\n";

                    if ($result['code'] == 302) {
                        echo "6. Following post-login redirect...\n";
                        $dashboardUrl = $result['redirect'];
                        $result = makeRequest($dashboardUrl, null, $cookieJar);
                        echo "Status: {$result['code']}\n";

                        if ($result['code'] == 200) {
                            echo "🎉 SUCCESS: Agent successfully logged in and accessed dashboard!\n";

                            // Check if it's actually the agent dashboard
                            if (strpos($result['response'], 'Dashboard Agent') !== false ||
                                strpos($result['response'], 'Agent') !== false) {
                                echo "✓ Confirmed: This is the agent dashboard\n";
                            } else {
                                echo "⚠ Dashboard content (first 200 chars):\n";
                                $body = substr($result['response'], strpos($result['response'], "\r\n\r\n") + 4);
                                echo substr($body, 0, 200) . "\n";
                            }
                        } else {
                            echo "❌ ERROR: Could not access dashboard after login (Status: {$result['code']})\n";
                        }
                    } else {
                        echo "❌ ERROR: Login failed (Status: {$result['code']})\n";
                        if ($result['code'] == 422) {
                            echo "Validation errors detected. Response body:\n";
                            $body = substr($result['response'], strpos($result['response'], "\r\n\r\n") + 4);
                            echo substr($body, 0, 500) . "\n";
                        }
                    }
                } else {
                    echo "❌ ERROR: Could not extract CSRF token\n";
                    echo "Response body (first 200 chars):\n";
                    $body = substr($result['response'], strpos($result['response'], "\r\n\r\n") + 4);
                    echo substr($body, 0, 200) . "\n";
                }
            } else {
                echo "❌ ERROR: Could not reach agent login form (Status: {$result['code']})\n";
            }
        } else {
            echo "❌ ERROR: Could not reach role selection (Status: {$result['code']})\n";
        }
    } else {
        echo "❌ ERROR: Login redirect failed (Status: {$result['code']})\n";
    }
} else {
    echo "❌ ERROR: Initial redirect failed (Status: {$result['code']})\n";
}

// Cleanup
unlink($cookieJar);

echo "\n=== TEST COMPLETE ===\n";
