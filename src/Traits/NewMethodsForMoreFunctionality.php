<?php

declare(strict_types=1);

namespace AlexKassel\Fetcher\Traits;

trait NewMethodsForMoreFunctionality
{
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
        return $this->handle(function () use ($callback) {
            $htmlBlockElements = array_map('trim', file(__DIR__ . '/HtmlBlockElements.txt'));

            $html = preg_replace('/<(script|style).*?<\/\\1>/si', '', $this->html());
            $html = str_replace($htmlBlockElements, ' ••• ', $html);
            $html = str_squish(strip_tags($html));

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
        }) ?? [];
    }

    public function texts(): array
    {
        return $this->each(fn($node) => $node->text());
    }
}
