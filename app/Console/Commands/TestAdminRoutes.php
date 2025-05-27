<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;

class TestAdminRoutes extends Command
{
    protected $signature = 'test:admin-routes';
    protected $description = 'Test if admin routes are properly registered';

    public function handle()
    {
        $this->info("Registering admin routes directly for testing");

        // Register a test route directly
        Route::middleware('web')
            ->prefix('admin')
            ->group(function () {
                Route::get('/test-direct', function () {
                    return 'Direct admin route test works!';
                });
            });

        $this->info("Route registered. Visit /admin/test-direct to test");

        // Check if admin.php routes are being loaded
        $this->info("Checking if admin.php routes file exists");
        $adminRoutesPath = base_path('routes/admin.php');

        if (!file_exists($adminRoutesPath)) {
            $this->error("Admin routes file does not exist at: " . $adminRoutesPath);
            return 1;
        }

        $this->info("Admin routes file exists at: " . $adminRoutesPath);
        $this->info("Contents of admin.php:");
        $this->line(file_get_contents($adminRoutesPath));

        return 0;
    }
}
