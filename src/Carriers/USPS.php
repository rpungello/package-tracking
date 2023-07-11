<?php

namespace Rpungello\PackageTracking\Carriers;

class USPS extends Carrier
{

    public function getTrackingNumberPatterns(): array
    {
        return [
            '(\d{4})\s?(\d{4})\s?(\d{4})\s?(\d{4})\s?(\d{4})\s?(\d{2})',
        ];
    }

    public function getTrackingUrl(string $trackingNumber): string
    {
        return 'https://tools.usps.com/go/TrackConfirmAction_input?strOrigTrackNum=' . str_replace(' ', '', $trackingNumber);
    }

    public function getName(): string
    {
        return 'USPS';
    }
}
