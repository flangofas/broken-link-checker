<?php

use Alleochain\BrokenLinksTool\CrawlerLogger;
use Spatie\Browsershot\Browsershot;
use Spatie\Crawler\Crawler;
use Spatie\Crawler\CrawlProfiles\CrawlAllUrls;

(static function (array $arguments): void {
    require_once __DIR__ . '/../vendor/autoload.php';

    if (count($arguments) < 2) {
        exit('Please provide the URL');
    }

    $crawler = Crawler::create([]);

    if (in_array('--enable-js', $arguments, true)) {
        $crawler->setBrowsershot((new Browsershot())->noSandbox())
            ->executeJavaScript();
    }

    $recordValidUrls = in_array('--valid-urls', $arguments, true);
    $recordInvalidUrls = in_array('--invalid-urls', $arguments, true);
    if ($recordValidUrls === false && $recordInvalidUrls === false) {
        $recordValidUrls = true;
        $recordInvalidUrls = true;
    }
    $crawler->setCrawlObserver(new CrawlerLogger($recordValidUrls, $recordInvalidUrls))
        ->setCrawlProfile(new CrawlAllUrls())
        ->startCrawling($arguments[1]);

})($argv);
