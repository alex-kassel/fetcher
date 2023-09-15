<?php

declare(strict_types=1);

namespace AlexKassel\Fetcher;

use Symfony\Component\DomCrawler\Crawler as SymfonyDomCrawler;
use AlexKassel\Fetcher\Traits\ExistingMethodsEnhancement;
use AlexKassel\Fetcher\Traits\NewMethodsForMoreFunctionality;

class Crawler extends SymfonyDomCrawler
{
    use ExistingMethodsEnhancement;
    use NewMethodsForMoreFunctionality;

    public function __construct(...$arguments)
    {
        parent::__construct(...$arguments);
        $this->nullInsteadOfExceptionIfNodeListIsEmpty = true;
    }
}
