<?php declare(strict_types=1);

namespace SoftRules\PHP\Services;

use Carbon\CarbonInterval;
use DOMDocument;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\PendingRequest;

class SoftRules extends Factory
{
    private readonly string $product;

    public function __construct()
    {
        parent::__construct();

        $this->product = (string) getenv('SOFTRULES_PRODUCT');
    }

    public function firstpage(): DOMDocument
    {
        $XML = '<SR >
	<ActionID>nieuw</ActionID>
	<Relatiedocument>
		<Relatiemantel>
			<VP>
            <VP_ANAAM>Hans</VP_ANAAM>
            <VP_VOORL>J.P.</VP_VOORL>
            <VP_GESLACH>M</VP_GESLACH>
            <VP_STRAAT>Frederik van Eedenstraat</VP_STRAAT>
            <VP_HUISNR>21</VP_HUISNR>
            <VP_TOEVOEG></VP_TOEVOEG>
            <VP_PLAATS>SOMMELSDYK</VP_PLAATS>
            <VP_PCODE>3245RL</VP_PCODE>
            <VP_LAND>NL</VP_LAND>
            <VP_TELNUM>06-12345678</VP_TELNUM>
            <VP_EMAIL>hans@bienefelt.nl</VP_EMAIL>
            <VP_GEBDAT>19690326</VP_GEBDAT>
            <VP_GSSC>I</VP_GSSC>
			</VP>
		</Relatiemantel>
		<Pakket>
			<Mantel>
				<PK>
					<PK_NUMMER/>
					<PK_EXTERN/>
					<PK_OFFERTE/>
					<PK_PRODUCC/>
				</PK>
			</Mantel>
			<Onderdeel>
				<PP>
					<PP_PRODCFG></PP_PRODCFG>
					<PP_NUMMER></PP_NUMMER>
					<PP_EXTERN></PP_EXTERN>
					<PP_OFFERTE></PP_OFFERTE>
					<PP_PRODUCC/>
				</PP>
			</Onderdeel>
		</Pakket>
	</Relatiedocument>
</SR>';

        $response = $this
            ->retry(3, 300)
            ->accept('application/xml')
            ->withBody($XML, 'application/xml')
            ->post("/userinterface/{$this->product}/firstpage/{$this->createSession()}");

        $xml = new DOMDocument();
        $xml->loadXML($response->body());

        return $xml;
    }

    public function updateUserinterface($ID, string $XML): DOMDocument
    {
        $response = $this
            ->retry(3, 300)
            ->accept('application/xml')
            ->withBody($XML, 'application/xml')
            ->post("/UpdateUserinterface/{$ID}/{$this->createSession()}");

        $xml = new DOMDocument();
        $xml->loadXML($response->body());

        return $xml;
    }

    public function nextPage($ID, string $XML): DOMDocument
    {
        $response = $this
            ->retry(3, 300)
            ->accept('application/xml')
            ->withBody($XML, 'application/xml')
            ->post("/Userinterface/{$this->product}/nextpage/{$this->createSession()}");

        $xml = new DOMDocument();
        $xml->loadXML($response->body());

        return $xml;
    }

    public function previousPage($ID, string $XML): DOMDocument
    {
        $response = $this
            ->retry(3, 300)
            ->accept('application/xml')
            ->withBody($XML, 'application/xml')
            ->post("/Userinterface/{$this->product}/previouspage/{$this->createSession()}");

        $xml = new DOMDocument();
        $xml->loadXML($response->body());

        return $xml;
    }

    protected function newPendingRequest(): PendingRequest
    {
        return parent::newPendingRequest()
            ->timeout((int) CarbonInterval::seconds(25)->totalSeconds)
            ->connectTimeout((int) CarbonInterval::seconds(5)->totalSeconds)
            // TODO use some kind of config system
            ->baseUrl(getenv('SOFTRULES_URI'));
    }

    private function createSession(): string
    {
        $response = $this->retry(3, 200)
            ->get('/getsession', [
                'username' => getenv('SOFTRULES_USERNAME'),
                'password' => getenv('SOFTRULES_PASSWORD'),
            ]);

        $xml = simplexml_load_string(trim($response->body()));

        return $xml->Session->SessionID?->__toString() ?? '';
    }
}
