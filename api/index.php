<?php
// Titik masuk Vercel: siapkan folder tulis di /tmp lalu teruskan ke Laravel.
foreach (['framework/views','framework/cache','framework/sessions','logs'] as $d) {
    @mkdir('/tmp/storage/'.$d, 0777, true);
}
require __DIR__ . '/../public/index.php';
