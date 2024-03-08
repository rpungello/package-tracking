<?php

namespace Rpungello\PackageTracking;

use Rpungello\PackageTracking\Carriers\Carrier;

class Package
{
    public function __construct(public Carrier $carrier, public string $trackingNumber)
    {
    }

    /**
     * Gets the URL for tracking the current package on the carrier's website
     *
     * @return string
     */
    public function getTrackingUrl(): string
    {
        return $this->carrier->getTrackingUrl($this->trackingNumber);
    }
}
