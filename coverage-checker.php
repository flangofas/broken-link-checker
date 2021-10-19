<?php

declare(strict_types=1);

/**
 * @link https://ocramius.github.io/blog/automated-code-coverage-check-for-github-pull-requests-with-travis/
 */

final class CoverageChecker
{
    private $percentage;
    private $content;
    private $coverage;

    private function __construct(string $inputFile, float $percentage)
    {
        if (! file_exists($inputFile)) {
            throw new InvalidArgumentException('Invalid input file provided');
        }

        $content = file_get_contents($inputFile);
        if (false === $content) {
            throw new RuntimeException('Coverage report is empty');
        }
        $this->content = $content;

        $percentage = min(100, max(0, $percentage));
        if (! is_float($percentage)) {
            throw new InvalidArgumentException('A float checked percentage must be given as second parameter');
        }
        $this->percentage = $percentage;
    }

    public static function fromCli(array $arguments): self
    {
        return new CoverageChecker(
            $arguments[1] ?? '',
            (float)($arguments[2] ?? 0)
        );
    }

    public function percentage(): float
    {
        return $this->percentage;
    }

    public function coverage(): float
    {
        if (! $this->coverage) {
            $this->coverage = $this->calculateCoverage();
        }

        return $this->coverage;
    }

    public function isAboveThreshold(): bool
    {
        return $this->coverage() >= $this->percentage();
    }

    private function calculateCoverage(): float
    {
        $xml = new SimpleXMLElement($this->content);
        $metrics = $xml->xpath('//metrics');
        if (false === $metrics) {
            throw new \RuntimeException('Coverage metrics are empty');
        }
        $totalElements = 0;
        $checkedElements = 0;

        foreach ($metrics as $metric) {
            $totalElements += (int)$metric['elements'];
            $checkedElements += (int)$metric['coveredelements'];
        }

        return ($checkedElements / $totalElements) * 100;
    }
}

$coverageChecker = CoverageChecker::fromCli($argv);

if (! $coverageChecker->isAboveThreshold()) {
    echo 'Code coverage is ' . $coverageChecker->coverage() . '%, which is ';
    echo 'below the accepted ' . $coverageChecker->percentage() . '%' . PHP_EOL;
    exit(1);
}

echo 'Code coverage is ' . $coverageChecker->coverage() . '% - OK!' . PHP_EOL;
