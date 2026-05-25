<?php
// scripts/ensure_db.php
// Run migrations and optional seeders in a safe, deploy-friendly way.

// Usage: set MIGRATE_ON_DEPLOY=true and/or SEED_ON_DEPLOY=true in environment,
// then run: php scripts/ensure_db.php

$logFile = __DIR__ . '/../storage/logs/ensure_db.log';
@mkdir(dirname($logFile), 0755, true);

function logMsg($msg) {
    global $logFile;
    $line = date('[Y-m-d H:i:s] ') . $msg . PHP_EOL;
    echo $line;
    @file_put_contents($logFile, $line, FILE_APPEND);
}

logMsg('Starting ensure_db script');

$migrate = strtolower(getenv('MIGRATE_ON_DEPLOY') ?: getenv('MIGRATE_ON_STARTUP') ?: 'false') === 'true';
$seed = strtolower(getenv('SEED_ON_DEPLOY') ?: getenv('SEED_ON_STARTUP') ?: 'false') === 'true';

if (!$migrate && !$seed) {
    logMsg('No action requested (MIGRATE_ON_DEPLOY or SEED_ON_DEPLOY not set to true). Exiting.');
    exit(0);
}

// Try to run php artisan commands and capture output
function runCmd($cmd) {
    logMsg('RUN: ' . $cmd);
    $output = [];
    $exit = 0;
    exec($cmd . ' 2>&1', $output, $exit);
    foreach ($output as $line) {
        logMsg('  ' . $line);
    }
    return $exit;
}

// Ensure environment is loaded by clearing cached config when running in deploy
runCmd('php artisan config:clear');

if ($migrate) {
    logMsg('Running migrations (force)');
    $code = runCmd('php artisan migrate --force');
    if ($code !== 0) {
        logMsg('Migrations failed with exit code ' . $code . '. See above output.');
        // continue to seeding only if migrations succeeded
        if (!$seed) exit($code);
    } else {
        logMsg('Migrations completed successfully.');
    }
}

if ($seed) {
    logMsg('Running db:seed (force)');
    $code = runCmd('php artisan db:seed --force');
    if ($code !== 0) {
        logMsg('Seeding failed with exit code ' . $code . '.');
        exit($code);
    }
    logMsg('Seeding completed successfully.');
}

logMsg('ensure_db script finished.');
exit(0);
