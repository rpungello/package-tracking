<?php

use Ramsey\Collection\Collection;
use Rpungello\PackageTracking\Carriers\UPS;
use Rpungello\PackageTracking\Package;
use Rpungello\PackageTracking\PackageTracking;

it('can match ups tracking numbers', function () {
    $instance = new UPS();
    $trackingNumber = '1Z12345E0305271640';

    expect($instance->trackingNumberMatches($trackingNumber))->toBeTrue();
});

it('can reject invalid ups tracking numbers', function () {
    $instance = new UPS();
    $trackingNumber = '000000000000';

    expect($instance->trackingNumberMatches($trackingNumber))->toBeFalse();
});

it('can reject ups tracking numbers with padding', function () {
    $instance = new UPS();

    expect($instance->trackingNumberMatches('1Z12345E0305271640 '))->toBeFalse()
        ->and($instance->trackingNumberMatches(' 1Z12345E0305271640'))->toBeFalse()
        ->and($instance->trackingNumberMatches(' 1Z12345E0305271640 '))->toBeFalse();
});

it('can extract ups tracking numbers', function () {
    $instance = new PackageTracking();
    $ups = new UPS();

    $text = "First line has a tracking number 1Z12345E0305271640 and some other text\nSecond line has 1Z12345E0205271688";
    $expected = new Collection(Package::class, [new Package($ups, '1Z12345E0305271640'), new Package($ups, '1Z12345E0205271688')]);

    $results = $instance->parsePackages($text);
    expect($results)->toHaveCount($expected->count());

    for ($i = 0; $i < $expected->count(); $i++) {
        expect($results[$i]->carrier->getName())->toBe('UPS')
            ->and($results[$i]->trackingNumber)->toBe($expected[$i]->trackingNumber);
    }
});

it('can extract ups tracking numbers without boundaries', function () {
    $instance = new PackageTracking();
    $ups = new UPS();

    $text = "First line has a tracking number1Z12345E0305271640and some other text\nSecond line has 1Z12345E0205271688";
    $expected = new Collection(Package::class, [new Package($ups, '1Z12345E0305271640'), new Package($ups, '1Z12345E0205271688')]);

    $results = $instance->parsePackages($text, false);
    expect($results)->toHaveCount($expected->count());

    for ($i = 0; $i < $expected->count(); $i++) {
        expect($results[$i]->carrier->getName())->toBe('UPS')
            ->and($results[$i]->trackingNumber)->toBe($expected[$i]->trackingNumber);
    }
});

it('can generate tracking URLs', function () {
    $package = new Package(new UPS(), '1Z12345E0305271640');
    expect($package->getTrackingUrl())->toBe('https://wwwapps.ups.com/tracking/tracking.cgi?tracknum=1Z12345E0305271640');
});

it('can parse ups tracking numbers', function () {
    $instance = new PackageTracking();
    $trackingNumber = '1Z12345E0305271640';
    $package = $instance->parseTrackingNumber($trackingNumber);

    expect($package)->toBeInstanceOf(Package::class)
        ->and($package->carrier->getName())->toBe('UPS')
        ->and($package->trackingNumber)->toBe('1Z12345E0305271640');
});
