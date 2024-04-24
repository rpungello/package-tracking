<?php

namespace Rpungello\PackageTracking;

use Ramsey\Collection\Collection;
use Ramsey\Collection\CollectionInterface;
use Rpungello\PackageTracking\Carriers\Carrier;
use Rpungello\PackageTracking\Carriers\DHL;
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
            new DHL(),
        ]);
    }

    /**
     * Adds a new carrier to the list of carriers that are supported by this package tracking instance
     *
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
     * @throws InvalidTrackingNumberException
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
     */
    public function parsePackages(string $text, bool $requireBoundary = true): CollectionInterface
    {
        $packages = new Collection(Package::class);

        /** @var Carrier $carrier */
        foreach ($this->carriers as $carrier) {
            foreach ($carrier->extractTrackingNumbers($text, $requireBoundary) as $trackingNumber) {
                $packages->add(
                    new Package(
                        $carrier,
                        $trackingNumber
                    )
                );
            }
        }

        return $packages->filter(fn (Package $package) => !$this->trackingNumberIsSubset($package, $packages));
    }

    /**
     * @param Package $package
     * @param Collection<Package> $packages
     * @return bool
     */
    private function trackingNumberIsSubset(Package $package, Collection $packages): bool
    {
        $currentTrackingNumber = str_replace(' ', '', $package->trackingNumber);
        foreach($packages as $package) {
            $trackingNumber = str_replace(' ', '', $package->trackingNumber);
            if ($currentTrackingNumber !== $trackingNumber && str_contains($trackingNumber, $currentTrackingNumber)) {
                return true;
            }
        }

        return false;
    }
}
