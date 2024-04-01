<?php declare(strict_types=1);

require_once __DIR__ . '/../bootstrap/bootstrap.php';

use SoftRules\PHP\HtmlRenderer;
use SoftRules\PHP\Services\SoftRulesClient;
use SoftRules\PHP\UI\SoftRulesForm;

$product = 'softrules';
$xml = SoftRulesClient::fromConfig($product)
    ->firstPage(file_get_contents(__DIR__ . '/../examples/first-page.xml'));
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="js/UpdateUserInterface.js"></script>
    <title>SoftRules PHP</title>
</head>
<body>
<form id='userinterfaceForm'
      method='POST'
      style="padding: 15px;">
    <?php echo new HtmlRenderer(SoftRulesForm::fromDomDocument($xml)); ?>
</form>

<script>
    const config = {
        product: '<?php echo $product; ?>',
        initialXml: '<?php echo trim($xml->saveHTML($xml->documentElement)); ?>',
        routes: {
            renderXml: '/page.php',
            updateUserInterface: '/updateUserInterface.php',
            previousPage: '/previouspage.php',
            nextPage: '/nextpage.php',
        },
    };
</script>
</body>
</html>
