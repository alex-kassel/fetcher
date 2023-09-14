<?php

declare(strict_types=1);

namespace AlexKassel\Fetcher;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\BrowserKit\Response;

class Fetcher
{
    protected HttpBrowser $browser;
    protected Crawler $crawler;
    protected Response $response;

    public function __construct(
        protected string $url,
        protected array|object $data = []
    ) {
        $this->browser = new HttpBrowser(HttpClient::create([
            'headers' => [
                'Accept-Language' => 'de,en-US;q=0.7,en;q=0.3',
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/117.0',
            ],
        ]));

        $this->browser->followRedirects(false);

        if ($this->data) {
            $crawler = $this->browser->request('POST', $this->url, [], [], [
                'CONTENT_TYPE' => 'application/json',
            ], json_encode($this->data));
        } else {
            $crawler = $this->browser->request('GET', $this->url);
        }

        $this->crawler = new Crawler($crawler->html(''));
        $this->response = $this->browser->getResponse();
    }

    public static function fetch(string $url, array|object $data = []): static
    {
        return new self($url, $data);
    }

    public function getStatusCode(): int
    {
        return $this->response->getStatusCode();
    }

    public function getHeaders(): array
    {
        return $this->response->getHeaders();
    }

    public function getContent(): string
    {
        return $this->response->getContent();
    }

    public function getJson(bool $asArray = false): array|object
    {
        return json_decode($this->response->getContent(), $asArray);
    }

    public function __call(string $method, array $args)
    {
        $class = ['response', 'crawler'][(int) method_exists($this->crawler, $method)];
        return $this->$class->$method(...$args);
    }
}
