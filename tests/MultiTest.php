<?php

use Ramsey\Collection\Collection;
use Rpungello\PackageTracking\Carriers\FedEx;
use Rpungello\PackageTracking\Carriers\UPS;
use Rpungello\PackageTracking\Package;
use Rpungello\PackageTracking\PackageTracking;

it('can extract ups and fedex tracking numbers', function () {
    $instance = new PackageTracking();
    $ups = new UPS();
    $fedex = new FedEx();

    $text = "First line has a tracking number 1Z12345E0305271640 and some other text\nSecond line has 98000000000 0000 0000";
    $expected = new Collection(Package::class, [new Package($ups, '1Z12345E0305271640'), new Package($fedex, '98000000000 0000 0000')]);

    $results = $instance->parsePackages($text);
    expect($results)->toHaveCount($expected->count());

    for ($i = 0; $i < $expected->count(); $i++) {
        expect($results[$i]->carrier->getName())->toBe($expected[$i]->carrier->getName())
            ->and($results[$i]->trackingNumber)->toBe($expected[$i]->trackingNumber);
    }
});
