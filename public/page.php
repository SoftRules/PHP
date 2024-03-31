<?php declare(strict_types=1);

require_once __DIR__ . '/../bootstrap/bootstrap.php';

use GuzzleHttp\Utils;
use SoftRules\PHP\HtmlRenderer;
use SoftRules\PHP\UI\SoftRulesForm;

if (isset($_POST['data'])) {
    $json = Utils::jsonDecode($_POST['data'], true);
    if (isset($json['XML'])) {
        header('Content-Type: text/html; charset=utf-8');
        exit(new HtmlRenderer(SoftRulesForm::fromXmlString($json['XML'])));
    }
}

http_response_code(204);
