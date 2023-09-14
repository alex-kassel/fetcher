<?php

declare(strict_types=1);

namespace AlexKassel\Fetcher\Traits;

trait CrawlerMoreMethods
{
    public function textRows(\Closure $callback = null): array
    {
        $html = preg_replace('/<(script|style).*?<\/\\1>/si', '', $this->html());
        $html = str_replace('&nbsp;', ' ', $html);
        $html = str_replace($this->breakTags(), ' ••• ', $html);
        $html = strip_tags($html);

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

    private function breakTags(): array
    {
        return [
            '<br>',
            '<hr>',
            '</address>',
            '</article>',
            '</aside>',
            '</blockquote>',
            '</canvas>',
            '</dd>',
            '</div>',
            '</dl>',
            '</dt>',
            '</fieldset>',
            '</figcaption>',
            '</figure>',
            '</footer>',
            '</form>',
            '</h1>',
            '</h2>',
            '</h3>',
            '</h4>',
            '</h5>',
            '</h6>',
            '</header>',
            '</li>',
            '</main>',
            '</nav>',
            '</noscript>',
            '</ol>',
            '</p>',
            '</pre>',
            '</section>',
            '</table>',
            '</tfoot>',
            '</ul>',
            '</video>'
        ];
    }
}
