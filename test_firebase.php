<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\FirebaseService;
use Illuminate\Support\Facades\Log;

try {
    echo "Testing Firebase Connection...\n";
    $service = new FirebaseService();
    echo "Service initialized.\n";
    
    // Try to get providers to check connection
    $providers = $service->getProviders();
    echo "Connection successful! Found " . count($providers) . " providers.\n";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
