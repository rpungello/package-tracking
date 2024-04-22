<?php

namespace Rpungello\PackageTracking\Carriers;

abstract class Carrier
{
    public function __construct()
    {
    }

    /**
     * Checks whether the supplied tracking number matches any of the predefined patterns for the current carrier
     */
    public function trackingNumberMatches(string $trackingNumber): bool
    {
        foreach ($this->getTrackingNumberPatterns() as $pattern) {
            if (preg_match('/^'.$pattern.'$/', $trackingNumber)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Extracts any tracking numbers from an arbitrary block of text, which are based on the predefined patterns
     * for the current carrier, except they must have word boundaries on both sides.
     * This additional check prevents situations where a very large string of numbers triggers a false match on a
     * numeric tracking number pattern (for example).
     *
     * @return string[]
     */
    public function extractTrackingNumbers(string $text, bool $requireBoundary = true): array
    {
        $trackingNumbers = [];

        foreach ($this->getTrackingNumberPatterns() as $pattern) {
            if (preg_match_all($this->convertPatternToRegularExpression($pattern, $requireBoundary), $text, $matches)) {
                $trackingNumbers = array_merge($trackingNumbers, $matches[0]);
            }
        }

        return $trackingNumbers;
    }

    /**
     * Regular expressions that define the patterns for tracking numbers for the current carrier
     *
     * @return string[]
     */
    abstract public function getTrackingNumberPatterns(): array;

    /**
     * Convert a tracking number into a URL that links to the carrier's tracking page
     */
    abstract public function getTrackingUrl(string $trackingNumber): string;

    /**
     * Get the name of the carrier (ex: UPS, FedEx, etc.)
     */
    abstract public function getName(): string;

    /**
     * @param  mixed  $pattern
     */
    private function convertPatternToRegularExpression(string $pattern, bool $requireBoundary = true): string
    {
        if ($requireBoundary) {
            return '/\b'.$pattern.'\b/';
        } else {
            return "/$pattern/";
        }
    }
}
