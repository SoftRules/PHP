<?php declare(strict_types=1);

require_once __DIR__ . '/../bootstrap/bootstrap.php';

use SoftRules\PHP\HtmlRenderer;
use SoftRules\PHP\UI\SoftRulesForm;

if (isset($_POST['xml'])) {
    header('Content-Type: text/html; charset=utf-8');
    exit(new HtmlRenderer(SoftRulesForm::fromXmlString($_POST['xml'])));
}

http_response_code(204);
