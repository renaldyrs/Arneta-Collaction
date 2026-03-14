<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$result = \DB::selectOne("SELECT COUNT(DISTINCT p.id) as c FROM products p LEFT JOIN product_sizes ps ON ps.product_id = p.id WHERE (p.cost IS NULL OR p.cost = 0) AND (p.stock > 0 OR ps.stock > 0)");
echo ($result->c ?? 0) . PHP_EOL;
