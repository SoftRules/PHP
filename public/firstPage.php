<?php declare(strict_types=1);

require_once __DIR__ . '/../bootstrap/bootstrap.php';

use SoftRules\PHP\Services\SoftRulesClient;

if (isset($_POST['xml'], $_POST['product'])) {
    header('Content-Type: text/xml');

    $xml = SoftRulesClient::fromConfig(e((string) $_POST['product']))
        ->firstPage((string) data_get($_POST, 'xml'));

    exit($xml->saveHTML($xml->documentElement));
}

http_response_code(204);
