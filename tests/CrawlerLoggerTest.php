<?php

namespace Tests;

use Alleochain\BrokenLinksTool\CrawlerLogger;
use GuzzleHttp\Exception\RequestException;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

class CrawlerLoggerTest extends TestCase
{
    public function testCrawled(): void
    {
        $crawlerLogger = new CrawlerLogger(true, true);

        $this->crawlUrlWithSuccess($crawlerLogger);

        $this->assertCount(1, $crawlerLogger->crawledUrls());
        $this->assertArrayHasKey(200, $crawlerLogger->crawledUrls());
    }

    public function testFailedCrawledBecauseUrlResponds500(): void
    {
        $crawlerLogger = new CrawlerLogger(true, true);

        $this->crawlUrlWithError($crawlerLogger);

        $this->assertCount(1, $crawlerLogger->crawledUrls());
        $this->assertArrayHasKey(500, $crawlerLogger->crawledUrls());
    }

    public function testFailedCrawledDeterminesUrlAsUncrawlable(): void
    {
        $crawlerLogger = new CrawlerLogger(true, true);

        $this->crawlUrlThatCannotBeCrawled($crawlerLogger);

        $this->assertCount(1, $crawlerLogger->crawledUrls());
        $this->assertArrayHasKey(1000, $crawlerLogger->crawledUrls());
    }

    /** @dataProvider optionsProvider */
    public function testCrawlerLoggerOptions(
        bool $recordValidUrls,
        bool $recordInvalidUrls,
        int $countSuccess,
        int $countError,
        int $countUncrawlable
    ): void {
        $crawlerLogger = new CrawlerLogger($recordValidUrls, $recordInvalidUrls);

        $this->crawlUrlWithSuccess($crawlerLogger);
        $this->assertCount($countSuccess, $crawlerLogger->crawledUrls());

        $this->crawlUrlWithError($crawlerLogger);
        $this->assertCount($countError, $crawlerLogger->crawledUrls()[500] ?? []);

        $this->crawlUrlThatCannotBeCrawled($crawlerLogger);
        $this->assertCount($countUncrawlable, $crawlerLogger->crawledUrls()[1000] ?? []);
    }

    /** @return iterable<string,array> */
    public function optionsProvider(): iterable
    {
        yield 'all urls' => [
                'recordvalidUrls' => true,
                'recordInvalidUrls' => true,
                'success' => 1,
                'with error' => 1,
                'uncrawlable' => 1,

            ];

        yield 'none given' => [
                'recordvalidUrls' => false,
                'recordInvalidUrls' => false,
                'success' => 0,
                'with error' => 0,
                'uncrawlable' => 0,
            ];

        yield 'invalid only urls' => [
                'recordvalidUrls' => false,
                'recordInvalidUrls' => true,
                'success' => 0,
                'with error' => 1,
                'uncrawlable' => 1,
            ];

        yield 'valid only urls' => [
                'recordvalidUrls' => true,
                'recordInvalidUrls' => false,
                'success' => 1,
                'with error' => 0,
                'uncrawlable' => 0,
            ];
    }

    private function crawlUrlWithSuccess(CrawlerLogger $crawlerLogger): void
    {
        $mockUri = $this->createMock(UriInterface::class);
        $mockUri->expects($this->once())
            ->method('__toString')
            ->willReturn('https://example.com');

        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse->expects($this->once())
                ->method('getStatusCode')
                ->willReturn(200);

        $mockResponse->expects($this->once())
                ->method('getReasonPhrase')
                ->willReturn('OK');

        $crawlerLogger->crawled($mockUri, $mockResponse);
    }

    private function crawlUrlWithError(CrawlerLogger $crawlerLogger): void
    {
        $mockUri = $this->createMock(UriInterface::class);
        $mockUri->expects($this->once())
            ->method('__toString')
            ->willReturn('https://500-response.com');

        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse->expects($this->once())
                ->method('getStatusCode')
                ->willReturn(500);

        $mockResponse->expects($this->once())
                ->method('getReasonPhrase')
                ->willReturn('Server Error');

        $crawlerLogger->crawled($mockUri, $mockResponse);
    }

    private function crawlUrlThatCannotBeCrawled(CrawlerLogger $crawlerLogger): void
    {
        $mockUri = $this->createMock(UriInterface::class);
        $mockUri->expects($this->once())
            ->method('__toString')
            ->willReturn('https://cannot-be-crawled.com');

        $mockRequestException = $this->createMock(RequestException::class);
        $mockRequestException->expects($this->once())
                ->method('getResponse');

        $crawlerLogger->crawlFailed($mockUri, $mockRequestException);
    }
}
