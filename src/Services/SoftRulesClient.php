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
        public readonly string  $product,
        private readonly string $uri,
        private readonly string $username,
        private readonly string $password,
    ) {
        parent::__construct();
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
        $response = $this
            ->retry(3, 300)
            ->accept('application/xml')
            ->withBody($xml, 'application/xml')
            ->post("/userinterface/{$this->product}/firstpage/{$this->createSession()}");

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
            // TODO use some kind of config system
            ->baseUrl($this->uri);
    }

    protected function createSession(): string
    {
        $response = $this->retry(3, 200)
            ->get('/getsession', [
                'username' => $this->username,
                'password' => $this->password,
            ]);

        $xml = simplexml_load_string(trim($response->body()));

        return $xml->Session->SessionID?->__toString() ?? '';
    }
}
