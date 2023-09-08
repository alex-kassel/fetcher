<?php

declare(strict_types=1);

namespace AlexKassel\Fetcher;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\BrowserKit\Response;
use AlexKassel\Fetcher\CrawlerWrapper;

class Fetcher
{
    protected HttpBrowser $browser;
    protected Response $response;
    protected CrawlerWrapper $crawler;
    protected ?object $json;

    public function __construct(string $url = '', array|object $data = []) {
        $this->initBrowser();

        if ($url) {
            $this->url($url, $data);
        }
    }

    protected function initBrowser(): void
    {
        $this->browser = new HttpBrowser(HttpClient::create([
            'headers' => [
                'Accept-Language' => 'de,en-US;q=0.7,en;q=0.3',
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/117.0',
            ],
        ]));
        $this->browser->followRedirects(false);
    }

    public function url(string $url, array|object $data = [])
    {
        if ($data) {
            $crawler = $this->browser->request('POST', $url, [], [], [
                'CONTENT_TYPE' => 'application/json',
            ], json_encode($data));
        } else {
            $crawler = $this->browser->request('GET', $url);
        }

        $this->response = $this->browser->getResponse();
        $this->json = json_decode($this->response->getContent());
        $this->crawler = new CrawlerWrapper($this->json ? '' : $crawler);

        return $this;
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

    public function getJson(bool $asArray = false): object|array
    {
        if (is_null($this->json)) {
            return $asArray ? [] : (object) [];
        }

        return $asArray
            ? json_decode($this->response->getContent(), true)
            : $this->json;
    }

    public function __call(string $name, array $args)
    {
        return $this->crawler->$name(...$args);
    }
}
