<?php declare(strict_types=1);

namespace SoftRules\PHP\Traits;

use Illuminate\Support\Collection;
use ReflectionProperty;
use SoftRules\PHP\Interfaces\ISoftRules_Base;
use SoftRules\PHP\UI\CustomProperty;

/**
 * @mixin ISoftRules_Base
 */
trait HasCustomProperties
{
    /**
     * @var Collection<int, CustomProperty>
     */
    private Collection $customProperties;

    /**
     * @return Collection<int, CustomProperty>
     */
    public function getCustomProperties(): Collection
    {
        $rp = new ReflectionProperty(self::class, 'customProperties');

        if (! $rp->isInitialized($this)) {
            $this->customProperties = new Collection();
        }

        return $this->customProperties;
    }

    public function addCustomProperty(CustomProperty $customProperty): void
    {
        $rp = new ReflectionProperty(self::class, 'customProperties');

        if (! $rp->isInitialized($this)) {
            $this->customProperties = new Collection();
        }

        $this->customProperties->add($customProperty);
    }

    public function getCustomPropertyByName(string $propertyName): ?CustomProperty
    {
        return $this->getCustomProperties()->first(function (CustomProperty $customProperty) use ($propertyName): bool {
            return strtolower((string) $customProperty->getName()) === $propertyName;
        });
    }
}
