<?php

declare(strict_types=1);

namespace AlexKassel\Fetcher\Traits;

trait ExistingMethodsEnhancement
{
    public bool $nullInsteadOfExceptionIfNodeListIsEmpty = false;

    private function handle(\Closure $closure): mixed
    {
        return $this->count() || false === $this->nullInsteadOfExceptionIfNodeListIsEmpty
            ? $closure()
            : null
            ;
    }

    public function text(string $default = null, bool $normalizeWhitespace = true): string
    {
        return (string) $this->handle(function () use ($default, $normalizeWhitespace) {
            return parent::text($default, $normalizeWhitespace);
        });
    }

    public function innerText(string $default = null, bool $normalizeWhitespace = true): string
    {
        return (string) $this->handle(function () use ($default, $normalizeWhitespace) {
            return parent::innerText($normalizeWhitespace) ?: $default;
        });
    }

    public function html(string $default = null, bool $normalizeWhitespace = true): string
    {
        return (string) $this->handle(function () use ($default, $normalizeWhitespace) {
            return $normalizeWhitespace
                ? str_squish(parent::html($default))
                : parent::html($default)
                ;
        });
    }

    public function attr(string $attribute, string $default = null): ?string
    {
        return $this->handle(function () use ($attribute, $default) {
             return ($attributeValue = parent::attr($attribute))
                 ? $attributeValue
                 : (is_null($attributeValue) ? null : $default)
                 ;
        });
    }
}
