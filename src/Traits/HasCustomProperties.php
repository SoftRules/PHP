<?php declare(strict_types=1);

namespace SoftRules\PHP\Traits;

use Illuminate\Support\Collection;
use ReflectionProperty;
use SoftRules\PHP\Contracts\UI\CustomPropertyContract;
use SoftRules\PHP\Contracts\UI\UiComponentContract;

/**
 * @mixin UiComponentContract
 */
trait HasCustomProperties
{
    /**
     * @var Collection<int, CustomPropertyContract>
     */
    private Collection $customProperties;

    /**
     * @return Collection<int, CustomPropertyContract>
     */
    public function getCustomProperties(): Collection
    {
        $rp = new ReflectionProperty(self::class, 'customProperties');

        if (! $rp->isInitialized($this)) {
            $this->customProperties = new Collection();
        }

        return $this->customProperties;
    }

    public function addCustomProperty(CustomPropertyContract $customProperty): void
    {
        $rp = new ReflectionProperty(self::class, 'customProperties');

        if (! $rp->isInitialized($this)) {
            $this->customProperties = new Collection();
        }

        $this->customProperties->add($customProperty);
    }

    public function getCustomPropertyByName(string $propertyName): ?CustomPropertyContract
    {
        return $this->getCustomProperties()->first(function (CustomPropertyContract $customProperty) use ($propertyName): bool {
            return strtolower((string) $customProperty->getName()) === $propertyName;
        });
    }
}
