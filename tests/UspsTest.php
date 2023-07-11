<?php

use Ramsey\Collection\Collection;
use Rpungello\PackageTracking\Carriers\USPS;
use Rpungello\PackageTracking\Package;
use Rpungello\PackageTracking\PackageTracking;

it('can match usps tracking numbers', function () {
    $instance = new USPS();
    $trackingNumber = '9400 1000 0000 0000 0000 00';

    expect($instance->trackingNumberMatches($trackingNumber))->toBeTrue();
});

it('can reject invalid usps tracking numbers', function () {
    $instance = new USPS();
    $trackingNumber = '000000000000';

    expect($instance->trackingNumberMatches($trackingNumber))->toBeFalse();
});

it('can reject usps tracking numbers with padding', function () {
    $instance = new USPS();

    expect($instance->trackingNumberMatches('9400 1000 0000 0000 0000 00 '))->toBeFalse();
    expect($instance->trackingNumberMatches(' 9400 1000 0000 0000 0000 00'))->toBeFalse();
    expect($instance->trackingNumberMatches(' 9400 1000 0000 0000 0000 00 '))->toBeFalse();
});

it('can extract usps tracking numbers', function () {
    $instance = new PackageTracking();
    $usps = new USPS();

    $text = "First line has a tracking number 9400 1000 0000 0000 0000 00 and some other text\nSecond line has 9205 5000 0000 0000 0000 00";
    $expected = new Collection(Package::class, [new Package($usps, '9400 1000 0000 0000 0000 00'), new Package($usps, '9205 5000 0000 0000 0000 00')]);

    $results = $instance->parsePackages($text);
    expect($results)->toHaveCount($expected->count());

    for ($i = 0; $i < $expected->count(); $i++) {
        expect($results[$i]->carrier->getName())->toBe('USPS');
        expect($results[$i]->trackingNumber)->toBe($expected[$i]->trackingNumber);
    }
});

it('can generate tracking URLs', function () {
    $package = new Package(new USPS(), '9400 1000 0000 0000 0000 00');
    expect($package->getTrackingUrl())->toBe('https://tools.usps.com/go/TrackConfirmAction_input?strOrigTrackNum=9400100000000000000000');
});

it('can parse usps tracking numbers', function () {
    $instance = new PackageTracking();
    $trackingNumber = '9400 1000 0000 0000 0000 00';
    $package = $instance->parseTrackingNumber($trackingNumber);

    expect($package)->toBeInstanceOf(Package::class);
    expect($package->carrier->getName())->toBe('USPS');
    expect($package->trackingNumber)->toBe('9400 1000 0000 0000 0000 00');
});
