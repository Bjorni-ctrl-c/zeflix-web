<?php

declare(strict_types=1);

// Titik masuk Vercel: siapkan folder tulis di /tmp lalu teruskan ke Laravel.
$storagePath = '/tmp/storage';
foreach (['framework/views', 'framework/cache', 'framework/cache/data', 'framework/sessions', 'logs', 'app/public'] as $d) {
    @mkdir($storagePath.'/'.$d, 0777, true);
}

$_ENV['LARAVEL_STORAGE_PATH'] = $storagePath;
$_SERVER['LARAVEL_STORAGE_PATH'] = $storagePath;
putenv('LARAVEL_STORAGE_PATH='.$storagePath);
putenv('VIEW_COMPILED_PATH='.$storagePath.'/framework/views');

require __DIR__.'/../public/index.php';
