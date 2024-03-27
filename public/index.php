<?php declare(strict_types=1);

require_once __DIR__ . '/../bootstrap/bootstrap.php';

use SoftRules\PHP\HtmlRenderer;
use SoftRules\PHP\Services\SoftRules;
use SoftRules\PHP\UI\UIClass;

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
<?php
$xml = (new SoftRules())->firstpage();

$UIClass = new UIClass();
$UIClass->ParseUIXML($xml);
?>
<form id='userinterfaceForm'
      method='POST'
      style="padding: 15px;">
    <?php echo new HtmlRenderer($UIClass); ?>
</form>

<script>var SoftRules_XML = '<?php echo trim($xml->saveHTML($xml->documentElement)); ?>'</script>
</body>
</html>