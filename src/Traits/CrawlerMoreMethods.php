<?php

declare(strict_types=1);

namespace AlexKassel\Fetcher\Traits;

trait CrawlerMoreMethods
{
    public function strSquish(string $string, string $replacement = ' '): string
    {
        return preg_replace('/(?:\s|&nbsp;)+/', $replacement, $string);
    }

    public function assoc(\Closure $closure): array
    {
        $parentData = parent::each($closure);
        $firstElement = (array) array_shift($parentData);
        $firstKey = array_shift($firstElement);

        if (! is_string($firstKey)) {
            throw new \InvalidArgumentException(
                "Cannot convert an array to an associative array because the first element is not a string."
            );
        }

        $data[$firstKey] = $firstElement;
        foreach($parentData as $next) {
            $data[array_shift($next)] = $next;
        }

        return $data;
    }

    public function textRows(\Closure $callback = null): array
    {
        $breakHtmlTags = array_map('trim', file(__DIR__ . '/BreakHtmlTags.txt'));

        $html = preg_replace('/<(script|style).*?<\/\\1>/si', '', $this->html());
        $html = str_replace($breakHtmlTags, ' ••• ', $html);
        $html = $this->strSquish(strip_tags($html));

        $rows = [];
        foreach (explode('•••', $html) as $row) {
            if ($row = trim($row)) {
                $rows[] = $row;
            }
        }

        if(is_callable($callback)) {
            return $callback($rows);
        }

        return $rows;
    }

    public function texts(): array
    {
        return $this->each(fn($node) => $node->text());
    }
}
