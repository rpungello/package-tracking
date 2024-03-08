<?php

namespace Rpungello\PackageTracking;

use Ramsey\Collection\Collection;
use Rpungello\PackageTracking\Carriers\Carrier;
use Rpungello\PackageTracking\Carriers\FedEx;
use Rpungello\PackageTracking\Carriers\UPS;
use Rpungello\PackageTracking\Carriers\USPS;
use Rpungello\PackageTracking\Exceptions\InvalidTrackingNumberException;

class PackageTracking
{
    /**
     * Carriers supported by this package tracking instance
     *
     * @var Collection<Carrier>
     */
    private Collection $carriers;

    public function __construct()
    {
        $this->carriers = new Collection(Carrier::class, [
            new UPS(),
            new FedEx(),
            new USPS(),
        ]);
    }

    /**
     * Adds a new carrier to the list of carriers that are supported by this package tracking instance
     *
     * @param Carrier $carrier
     * @return $this
     */
    public function addCarrier(Carrier $carrier): self
    {
        $this->carriers->add($carrier);

        return $this;
    }

    /**
     * Parse a single tracking number and return a Package object if it matches any of the supported carriers.
     *
     * @param string $trackingNumber
     * @return Package
     */
    public function parseTrackingNumber(string $trackingNumber): Package
    {
        foreach ($this->carriers as $carrier) {
            if ($carrier->trackingNumberMatches($trackingNumber)) {
                return new Package(
                    $carrier,
                    $trackingNumber
                );
            }
        }

        throw new InvalidTrackingNumberException();
    }

    /**
     * Parse multiple packages from a block of text and return a collection of Package objects.
     *
     * @param string $text
     * @return Collection
     */
    public function parsePackages(string $text): Collection
    {
        $packages = new Collection(Package::class);

        /** @var Carrier $carrier */
        foreach ($this->carriers as $carrier) {
            foreach ($carrier->extractTrackingNumbers($text) as $trackingNumber) {
                $packages->add(
                    new Package(
                        $carrier,
                        $trackingNumber
                    )
                );
            }
        }

        return $packages;
    }
}
