<?php

namespace Rpungello\PackageTracking\Carriers;

use Rpungello\PackageTracking\Package;

abstract class Carrier
{
    public function __construct()
    {
    }

    public function trackingNumberMatches(string $trackingNumber): bool
    {
        foreach ($this->getTrackingNumberPatterns() as $pattern) {
            if (preg_match($pattern, $trackingNumber)) {
                return true;
            }
        }

        return false;
    }

    public function extractTrackingNumbers(string $text): array
    {
        $trackingNumbers = [];

        foreach ($this->getTrackingNumberPatterns() as $pattern) {
            if (preg_match_all($pattern, $text, $matches)) {
                $trackingNumbers = array_merge($trackingNumbers, $matches[0]);
            }
        }

        return $trackingNumbers;
    }

    abstract public function getTrackingNumberPatterns(): array;

    abstract public function getTrackingUrl(string $trackingNumber): string;

    abstract public function getName(): string;
}
