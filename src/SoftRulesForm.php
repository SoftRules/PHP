<?php declare(strict_types=1);

namespace SoftRules\PHP;

use Illuminate\Support\HtmlString;
use Stringable;

final class SoftRulesForm implements Stringable
{
    private string $firstPageRoute = '/firstPage.php';

    private string $renderXmlRoute = '/renderXml.php';

    private string $updateUserInterfaceRoute = '/updateUserInterface.php';

    private string $previousPageRoute = '/previousPage.php';

    private string $nextPageRoute = '/nextPage.php';

    private string $initialXml = '';

    private ?HtmlString $csrfProtection = null;

    private string $javascriptPath = 'js';

    private function __construct(public readonly string $product)
    {
        //
    }

    public static function make(string $product): self
    {
        return new self($product);
    }

    public function withInitialXml(string $initialXml): self
    {
        $this->initialXml = rawurlencode($initialXml);

        return $this;
    }

    public function setJavascriptPath(string $path): self
    {
        $this->javascriptPath = $path;

        return $this;
    }

    public function withCsrfProtection(HtmlString $csrfInput): self
    {
        $this->csrfProtection = $csrfInput;

        return $this;
    }

    public function setRenderXmlRoute(string $route): self
    {
        $this->renderXmlRoute = $route;

        return $this;
    }

    public function setUpdateUserInterfaceRoute(string $route): self
    {
        $this->updateUserInterfaceRoute = $route;

        return $this;
    }

    public function setFirstPageRoute(string $route): self
    {
        $this->firstPageRoute = $route;

        return $this;
    }

    public function setPreviousPageRoute(string $route): self
    {
        $this->previousPageRoute = $route;

        return $this;
    }

    public function setNextPageRoute(string $route): self
    {
        $this->nextPageRoute = $route;

        return $this;
    }

    public function render(): HtmlString
    {
        return new HtmlString(
            <<<EOT
<form id='softrules-form'
      method='POST'
      style="padding: 15px;">
{$this->csrfProtection}
<div id="softrules-form-content">Aan het laden...</div>
</form>

<script>
    const config = {
        product: '{$this->product}',
        initialXml: '{$this->initialXml}',
        routes: {
            firstPage: '{$this->firstPageRoute}',
            renderXml: '{$this->renderXmlRoute}',
            updateUserInterface: '{$this->updateUserInterfaceRoute}',
            previousPage: '{$this->previousPageRoute}',
            nextPage: '{$this->nextPageRoute}',
        },
    };
    let script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = '{$this->javascriptPath}/UpdateUserInterface.js';

    document.head.appendChild(script);
</script>
EOT
        );
    }

    public function __toString(): string
    {
        return $this->render()->toHtml();
    }
}
