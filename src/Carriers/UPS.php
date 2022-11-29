<?php

namespace Rpungello\PackageTracking\Carriers;

class UPS extends Carrier
{
    public function getTrackingNumberPatterns(): array
    {
        return [
            '\b(1Z ?[0-9A-Z]{3} ?[0-9A-Z]{3} ?[0-9A-Z]{2} ?[0-9A-Z]{4} ?[0-9A-Z]{3} ?[0-9A-Z]|[\dT]\d\d\d ?\d\d\d\d ?\d\d\d)\b',
        ];
    }

    public function getTrackingUrl(string $trackingNumber): string
    {
        // TODO: Implement getTrackingUrl() method.
    }

    public function getName(): string
    {
        return 'UPS';
    }
}
