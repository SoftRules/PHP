<?php declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Origin: GET, POST');
header('Access-Control-Allow-Headers: *');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

if (env('APP_ENV') === 'local' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    \Spatie\Ignition\Ignition::make()
        ->applicationPath(__DIR__ . '/../')
        ->runningInProductionEnvironment(env('APP_ENV') !== 'local')
        ->register();
}
