<?php

declare(strict_types=1);

namespace AlexKassel\Fetcher;

use Symfony\Component\DomCrawler\Crawler;

class CrawlerWrapper extends Crawler
{
    public function each(\Closure $closure, bool $asAssoc = false): array
    {
        if (! $asAssoc) {
            return parent::each($closure);
        }

        $data = [];
        foreach(parent::each($closure) as $next) {
            if (! is_array($next)) {
                $data[] = $next;
            } elseif (is_string($next[0]) && isset($next[1])) {
                $data[$next[0]] = $next[1];
            } else {
                $data[] = $next[0];
            }
        }

        return $data;
    }

    public function texts(): array
    {
        return $this->each(fn($node) => $node->text());
    }

    public function text(string $default = null, bool $normalizeWhitespace = true): string
    {
        $default ??= '';

        return $this->count()
            ? parent::text($default, $normalizeWhitespace)
            : $default
            ;
    }

    public function innerText(string $default = null, bool $normalizeWhitespace = true): string
    {
        $default ??= '';

        return $this->count()
            ? (parent::innerText($normalizeWhitespace) ?: $default)
            : $default
            ;
    }

    public function html(string $default = null, bool $normalizeWhitespace = true): string
    {
        $default ??= '';

        if ($this->count()) {
            return $normalizeWhitespace
                ? trim(preg_replace('/\s+/', ' ', parent::html($default)))
                : parent::html($default)
                ;
        }

        return $default;
    }

    public function attr(string $attribute, string $default = null): string
    {
        return $this->count()
            ? (parent::attr($attribute) ?? $default)
            : $default
            ;
    }
}
