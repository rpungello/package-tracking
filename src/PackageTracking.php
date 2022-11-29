<?php

namespace Rpungello\PackageTracking;

use Ramsey\Collection\Collection;
use Rpungello\PackageTracking\Carriers\Carrier;
use Rpungello\PackageTracking\Carriers\FedEx;
use Rpungello\PackageTracking\Carriers\UPS;
use Rpungello\PackageTracking\Exceptions\InvalidTrackingNumberException;

class PackageTracking
{
    private Collection $carriers;

    public function __construct()
    {
        $this->carriers = new Collection(Carrier::class, [
            new UPS(),
            new FedEx(),
        ]);
    }

    public function addCarrier(Carrier $carrier): self
    {
        $this->carriers->add($carrier);
        return $this;
    }

    public function parseTrackingNumber(string $trackingNumber): Package
    {
        /** @var Carrier $carrier */
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
