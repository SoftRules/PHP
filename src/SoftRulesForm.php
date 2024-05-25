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

    private string $scriptActionsRoute = '/scriptActions.php';

    private string $initialXml = '';

    private ?HtmlString $csrfProtection = null;

    private string $javascriptPath = 'js';

    private string $cssPath = 'css';

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

    public function setCSSPath(string $path): self
    {
        $this->cssPath = $path;

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

    public function setScriptActionsRoute(string $route): self
    {
        $this->scriptActionsRoute = $route;

        return $this;
    }

    public function render(): HtmlString
    {
        return new HtmlString(
            <<<HTML
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
                        scriptactions: '{$this->scriptActionsRoute}',
                    },
                };
                let script = document.createElement('script');
                script.type = 'module';
                script.src = '{$this->javascriptPath}/softRules.js';
                document.head.appendChild(script);

                let link  = document.createElement('link');
                link.rel  = 'stylesheet';
                link.type = 'text/css';
                link.href = '{$this->cssPath}/SoftRules.css';
                document.head.appendChild(link);
            </script>

            <form id='softrules-form'
                  method='POST'
                  style="padding: 15px; display: none;">
                {$this->csrfProtection}

                <div class='errorContainer alert alert-danger' style='margin-top: 2px; display:none' data-type='Danger' id='messageAlert'></div>

                <div id="softrules-form-content"></div>

                <div id='waitScreen' class='waitScreen'>
                    <div class='loadingscreen_div'>
                        <img src='https://www.softrules.com/wp-content/themes/softrules/assets/logo.jpg' alt='My Tp / SoftRules©' style='width:250px;height:166px;'/>

                        <i class="fa fa-solid fa-spinner fa-spin" style="font-size: 36px; height: 36px;"></i>
                    </div>
                </div>
            </form>
            HTML
        );
    }

    public function __toString(): string
    {
        return $this->render()->toHtml();
    }
}
