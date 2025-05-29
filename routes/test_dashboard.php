<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

// Create a simple test route to check the dashboard
Route::get('/test-dashboard', function () {
    // Create a test user
    $user = User::create([
        'nom' => 'Test',
        'prenoms' => 'User',
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
        'role' => 'citizen',
        'date_naissance' => '1990-01-01',
        'genre' => 'M'
    ]);
    
    // Login the user
    Auth::login($user);
    
    try {
        // Try to render the dashboard view
        $view = view('front.dashboard');
        $content = $view->render();
        
        return response()->json([
            'success' => true,
            'message' => 'Dashboard view rendered successfully',
            'content_length' => strlen($content)
        ]);
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});
