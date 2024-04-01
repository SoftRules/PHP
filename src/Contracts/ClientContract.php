<?php declare(strict_types=1);

namespace SoftRules\PHP\Contracts;

use DOMDocument;

interface ClientContract
{
    public function firstPage(string $xml): DOMDocument;

    public function updateUserInterface(string $id, string $xml): DOMDocument;

    public function nextPage(string $id, string $xml): DOMDocument;

    public function previousPage(string $id, string $xml): DOMDocument;

}
