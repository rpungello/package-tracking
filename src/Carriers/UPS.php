<?php

namespace Rpungello\PackageTracking\Carriers;

class UPS extends Carrier
{
    public function getTrackingNumberPatterns(): array
    {
        return [
            '1[Zz] ?[0-9A-Za-z]{3} ?[0-9A-Za-z]{3} ?[0-9A-Za-z]{2} ?[0-9A-Za-z]{4} ?[0-9A-Za-z]{3} ?[0-9A-Za-z]',
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
