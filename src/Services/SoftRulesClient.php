<?php declare(strict_types=1);

namespace SoftRules\PHP\Services;

use Carbon\CarbonInterval;
use DOMDocument;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\PendingRequest;
use RuntimeException;
use SoftRules\PHP\Contracts\ClientContract;

class SoftRulesClient extends Factory implements ClientContract
{
    public function __construct(
        public readonly string    $product,
        protected readonly string $uri,
        protected readonly string $username,
        private readonly string   $password,
    ) {
        parent::__construct();

        if (! $this->uri) {
            $this->throwError('URI provided for SoftRulesClient should not be empty');
        }

        if (! filter_var($this->uri, FILTER_VALIDATE_URL)) {
            $this->throwError('URI provided for SoftRulesClient is not a valid URL');
        }

        if (! $this->username) {
            $this->throwError('username provided for SoftRulesClient should not be empty');
        }

        if (! $this->password) {
            $this->throwError('password provided for SoftRulesClient should not be empty');
        }
    }

    public static function fromConfig(string $product): self
    {
        $product = e($product);

        $config = collect((require __DIR__ . '/../../config/softrules.php')['forms'])
            ->firstWhere('product', $product);

        if (! $config) {
            throw new RuntimeException('No form found for product "' . $product . '" in the config');
        }

        return new self(
            product: $product,
            uri: $config['uri'],
            username: $config['username'],
            password: $config['password'],
        );
    }

    public function firstPage(string $xml): DOMDocument
    {
        $session = $this->createSession();

        $url = "/userinterface/{$this->product}/firstpage/{$session}";

        $response = $this
            ->retry(3, 300, throw: false)
            ->accept('application/xml')
            ->withBody($xml, 'application/xml')
            ->post($url);

        if ($response->clientError()) {
            $this->throwError("Could not connect to {$this->uri}{$url} with the provided credentials");
        }

        $response->throw();

        $responseXml = new DOMDocument();
        $responseXml->loadXML($response->body());

        return $responseXml;
    }

    public function updateUserInterface(string $id, string $xml): DOMDocument
    {
        $response = $this
            ->retry(3, 300)
            ->accept('application/xml')
            ->withBody($xml, 'application/xml')
            ->post("/UpdateUserinterface/{$id}/{$this->createSession()}");

        $responseXml = new DOMDocument();
        $responseXml->loadXML($response->body());

        return $responseXml;
    }

    public function nextPage(string $id, string $xml): DOMDocument
    {
        $response = $this
            ->retry(3, 300)
            ->accept('application/xml')
            ->withBody($xml, 'application/xml')
            ->post("/Userinterface/{$this->product}/nextpage/{$this->createSession()}");

        $responseXml = new DOMDocument();
        $responseXml->loadXML($response->body());

        return $responseXml;
    }

    public function previousPage(string $id, string $xml): DOMDocument
    {
        $response = $this
            ->retry(3, 300)
            ->accept('application/xml')
            ->withBody($xml, 'application/xml')
            ->post("/Userinterface/{$this->product}/previouspage/{$this->createSession()}");

        $responseXml = new DOMDocument();
        $responseXml->loadXML($response->body());

        return $responseXml;
    }

    protected function newPendingRequest(): PendingRequest
    {
        return parent::newPendingRequest()
            ->timeout((int) CarbonInterval::seconds(25)->totalSeconds)
            ->connectTimeout((int) CarbonInterval::seconds(5)->totalSeconds)
            ->baseUrl($this->uri);
    }

    protected function createSession(): string
    {
        $response = $this->retry(3, 200, throw: false)
            ->get('/getsession', [
                'username' => $this->username,
                'password' => $this->password,
            ]);

        if ($response->clientError()) {
            $this->throwError("Could not connect to {$this->uri}/getsession with the provided credentials");
        }

        $response->throw();

        $xml = simplexml_load_string(trim($response->body()));

        $sessionId = $xml->Session->SessionID?->__toString() ?? '';

        if (! $sessionId) {
            $this->throwError(($xml->Result->ResultDescription?->__toString() ?? '') . " (Could not connect to {$this->uri}/getsession with the provided credentials.)");
        }

        return $sessionId;
    }

    protected function throwError(string $message): never
    {
        http_response_code(400);

        if ($_SERVER['HTTP_ACCEPT'] === 'application/xml') {
            header('Content-Type: application/xml');

            exit('<error>' . $message . '</error>');
        }

        throw new RuntimeException($message);
    }
}
