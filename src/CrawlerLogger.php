<?php

namespace Alleochain\BrokenLinksTool;

use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Spatie\Crawler\CrawlObservers\CrawlObserver;

class CrawlerLogger extends CrawlObserver
{
    public const UNCRAWLABLE_CODE = 1000;

    /** @var array<int,array<int,string>> $crawledUrls */
    private $crawledUrls = [];

    public function __construct(
        private bool $recordValidUrls = false,
        private bool $recordInvalidUrls = false,
    ) {
    }

    /** @return array<int,array> */
    public function crawledUrls(): array
    {
        return $this->crawledUrls;
    }

    /**
     * Called when the crawler will crawl the url.
     *
     * @param \Psr\Http\Message\UriInterface $url
     */
    public function willCrawl(UriInterface $url): void
    {
    }

    public function finishedCrawling(): void
    {
        krsort($this->crawledUrls);

        dump(PHP_EOL);
        dump('Crawler Summary');
        dump('===============');

        foreach (array_keys($this->crawledUrls) as $statusCode) {
            foreach ($this->crawledUrls[$statusCode] as $log) {
                dump($log);
            }
        }

        dump(PHP_EOL);
        dump('Counts per Code');
        dump('===============');

        foreach ($this->crawledUrls as $statusCode => $urls) {
            $count = count($urls);

            if (is_numeric($statusCode)) {
                dump("Crawled {$count} url(s) with status code {$statusCode}");
            }
        }
    }

    public function crawled(
        UriInterface $url,
        ResponseInterface $response,
        ?UriInterface $foundOnUrl = null
    ): void {
        $this->addResult(
            (string) $url,
            (string) $foundOnUrl,
            $response->getStatusCode(),
            $response->getReasonPhrase()
        );
    }

    public function crawlFailed(
        UriInterface $url,
        RequestException $requestException,
        ?UriInterface $foundOnUrl = null
    ): void {
        if ($response = $requestException->getResponse()) {
            $this->crawled($url, $response, $foundOnUrl);
        } else {
            $this->addResult((string) $url, (string) $foundOnUrl, self::UNCRAWLABLE_CODE, 'Failed to crawl');
        }
    }

    private function addResult(string $url, string $foundOnUrl, int $statusCode, string $reason): void
    {
        if (isset($this->crawledUrls[$statusCode]) && in_array($url, $this->crawledUrls[$statusCode])) {
            return;
        }

        if ($this->excludeByStatusCode($statusCode)) {
            return;
        }

        $timestamp = date('Y-m-d H:i:s');
        $message = "{$reason} - " . $url . " (found on {$foundOnUrl})";

        $this->crawledUrls[$statusCode][] = "[{$statusCode}] {$message} , visited on {$timestamp}";
    }

    private function excludeByStatusCode(int $statusCode): bool
    {
        $isValid = $statusCode >= 200 && $statusCode < 399;
        if (
            $isValid
            && $this->recordValidUrls
        ) {
            return false;
        }

        $isInvalid = $statusCode >= 400;
        if (
            $isInvalid
            && $this->recordInvalidUrls
        ) {
            return false;
        }

        return true;
    }
}
