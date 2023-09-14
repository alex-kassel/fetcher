<?php

declare(strict_types=1);

namespace AlexKassel\Fetcher;

use Symfony\Component\DomCrawler\Crawler as SymfonyDomCrawler;
use AlexKassel\Fetcher\Traits\CrawlerBugFix;
use AlexKassel\Fetcher\Traits\CrawlerMoreMethods;

class Crawler extends SymfonyDomCrawler
{
    use CrawlerBugFix;
    use CrawlerMoreMethods;
}
