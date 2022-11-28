<?php

namespace Rpungello\PackageTracking;

use Rpungello\PackageTracking\Carriers\Carrier;

class Package
{
    public function __construct(public Carrier $carrier, public string $trackingNumber)
    {
    }

    public function getTrackingUrl(): string
    {
        return $this->carrier->getTrackingUrl($this->trackingNumber);
    }
}
