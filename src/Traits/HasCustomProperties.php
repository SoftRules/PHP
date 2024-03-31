<?php declare(strict_types=1);

namespace SoftRules\PHP\Traits;

use Illuminate\Support\Collection;
use ReflectionProperty;
use SoftRules\PHP\Interfaces\CustomPropertyInterface;
use SoftRules\PHP\Interfaces\UiComponentInterface;

/**
 * @mixin UiComponentInterface
 */
trait HasCustomProperties
{
    /**
     * @var Collection<int, CustomPropertyInterface>
     */
    private Collection $customProperties;

    /**
     * @return Collection<int, CustomPropertyInterface>
     */
    public function getCustomProperties(): Collection
    {
        $rp = new ReflectionProperty(self::class, 'customProperties');

        if (! $rp->isInitialized($this)) {
            $this->customProperties = new Collection();
        }

        return $this->customProperties;
    }

    public function addCustomProperty(CustomPropertyInterface $customProperty): void
    {
        $rp = new ReflectionProperty(self::class, 'customProperties');

        if (! $rp->isInitialized($this)) {
            $this->customProperties = new Collection();
        }

        $this->customProperties->add($customProperty);
    }

    public function getCustomPropertyByName(string $propertyName): ?CustomPropertyInterface
    {
        return $this->getCustomProperties()->first(function (CustomPropertyInterface $customProperty) use ($propertyName): bool {
            return strtolower((string) $customProperty->getName()) === $propertyName;
        });
    }
}
