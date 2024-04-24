<?php

namespace Rpungello\PackageTracking\Carriers;

class FedEx extends Carrier
{
    public function getTrackingNumberPatterns(): array
    {
        return [
            '\d{15}',
            '\d{4} ?\d{4} ?\d{4}',
            '((98\d\d\d\d\d?\d\d\d\d|98\d\d) ?\d\d\d\d ?\d\d\d\d( ?\d\d\d)?)',
        ];
    }

    public function getTrackingUrl(string $trackingNumber): string
    {
        return "https://www.fedex.com/fedextrack/?trknbr=$trackingNumber";
    }

    public function getName(): string
    {
        return 'FedEx';
    }
}
