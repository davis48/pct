<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Start a session
$app->make('session')->start();

// Get an agent user from the database
$user = \App\Models\User::where('role', 'agent')->first();

if (!$user) {
    echo "No agent user found in database\n";
    exit;
}

echo "Found agent user: {$user->email} (role: {$user->role})\n";

// Authenticate the user
\Illuminate\Support\Facades\Auth::login($user);

echo "User authenticated: " . (\Illuminate\Support\Facades\Auth::check() ? 'Yes' : 'No') . "\n";
echo "Current user role: " . (\Illuminate\Support\Facades\Auth::user()->role ?? 'None') . "\n";

// Create a test request to agent dashboard
$request = Illuminate\Http\Request::create('/agent/dashboard', 'GET');

try {
    $response = $kernel->handle($request);
    echo "Response status: " . $response->getStatusCode() . "\n";
    echo "Response content (first 500 chars): " . substr($response->getContent(), 0, 500) . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
