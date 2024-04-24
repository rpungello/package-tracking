<?php

namespace Rpungello\PackageTracking\Carriers;

class DHL extends Carrier
{

    /**
     * @inheritDoc
     */
    public function getTrackingNumberPatterns(): array
    {
        return [
            '[0-9]{10,11}'
        ];
    }

    /**
     * @inheritDoc
     */
    public function getTrackingUrl(string $trackingNumber): string
    {
        return "https://www.dhl.com/us-en/home/tracking.html?tracking-id=$trackingNumber";
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'DHL';
    }
}
