<?php

namespace Rpungello\PackageTracking\Carriers;

class UPS extends Carrier
{
    public function getTrackingNumberPatterns(): array
    {
        return [
            '1Z ?[0-9A-Z]{3} ?[0-9A-Z]{3} ?[0-9A-Z]{2} ?[0-9A-Z]{4} ?[0-9A-Z]{3} ?[0-9A-Z]',
        ];
    }

    public function getTrackingUrl(string $trackingNumber): string
    {
        return "https://wwwapps.ups.com/tracking/tracking.cgi?tracknum=$trackingNumber";
    }

    public function getName(): string
    {
        return 'UPS';
    }
}
