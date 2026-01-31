<?php
// File: public_html/tani.suska.id/migrate-simple.php
// Langsung jalankan tanpa interface

define('LARAVEL_START', microtime(true));
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Security password
if (! isset($_GET['pass']) || $_GET['pass'] !== '12345') {
    die('Access Denied');
}

// Pilih command
$command = $_GET['cmd'] ?? 'fresh_seed';

echo "<pre>";
echo "Executing: $command\n";
echo str_repeat("=", 50) . "\n";

try {
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

    switch ($command) {
        case 'fresh':
            $kernel->call('migrate:fresh', ['--force' => true]);
            break;
        case 'fresh_seed':
            $kernel->call('migrate:fresh', ['--force' => true, '--seed' => true]);
            break;
        case 'seed':
            $kernel->call('db:seed', ['--force' => true, '--class' => 'MassiveDummySeeder']);
            break;
        default:
            die("Invalid command");
    }

    echo $kernel->output();
    echo "\n✅ SUCCESS!";

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage();
    echo "\n" . $e->getTraceAsString();
}

echo "</pre>";

// Auto delete
// unlink(__FILE__);
// echo "\n\n🗑️ File telah dihapus otomatis";
