<?php

use Ramsey\Collection\Collection;
use Rpungello\PackageTracking\Carriers\FedEx;
use Rpungello\PackageTracking\Package;
use Rpungello\PackageTracking\PackageTracking;

it('can match fedex tracking numbers', function () {
    $instance = new FedEx();
    $trackingNumbers = [
        '000000000000000',
        '000000000000',
        '9800000000000000000',
        '9800000000000000000000',
        '98000000000 0000 0000',
        '98000000000 0000 0000 000',
    ];

    foreach ($trackingNumbers as $trackingNumber) {
        expect($instance->trackingNumberMatches($trackingNumber))->toBeTrue();
    }
});

it('can reject invalid fedex tracking numbers', function () {
    $instance = new FedEx();
    $trackingNumbers = [
        '9A00000000000000000000',
        '960000000000000000000',
        '0000000 00000000',
        '000000000 000',
        '980 0000000000000000',
        'A9800000000000000000000',
        '98000000000 00000 0000',
        '98000000000 0000 0000 000A',
    ];

    foreach ($trackingNumbers as $trackingNumber) {
        expect($instance->trackingNumberMatches($trackingNumber))->toBeFalse();
    }
});

it('can reject fedex tracking numbers with padding', function () {
    $instance = new FedEx();

    expect($instance->trackingNumberMatches('9600000000000000000000 '))->toBeFalse()
        ->and($instance->trackingNumberMatches(' 98000000000 0000 0000'))->toBeFalse()
        ->and($instance->trackingNumberMatches(' 000000000000 '))->toBeFalse();
});

it('can extract fedex tracking numbers', function () {
    $instance = new PackageTracking();
    $fedex = new FedEx();

    $text = "First line has a tracking number 100000000001 and some other text\nSecond line has 98000000000 0000 0000";
    $expected = new Collection(Package::class, [new Package($fedex, '100000000001'), new Package($fedex, '98000000000 0000 0000')]);

    $results = $instance->parsePackages($text);
    expect($results)->toHaveCount($expected->count());

    for ($i = 0; $i < $expected->count(); $i++) {
        expect($results[$i]->carrier->getName())->toBe('FedEx')
            ->and($results[$i]->trackingNumber)->toBe($expected[$i]->trackingNumber);
    }
});

it('can extract fedex tracking numbers with no boundaries', function () {
    $instance = new PackageTracking();
    $fedex = new FedEx();

    $text = "First line has a tracking number100000010000and some other text\nSecond line has 98000000000 0000 0000";
    $expected = new Collection(Package::class, [new Package($fedex, '100000010000'), new Package($fedex, '98000000000 0000 0000')]);

    $results = $instance->parsePackages($text, false);
    expect($results)->toHaveCount($expected->count());

    for ($i = 0; $i < $expected->count(); $i++) {
        expect($results[$i]->carrier->getName())->toBe('FedEx')
            ->and($results[$i]->trackingNumber)->toBe($expected[$i]->trackingNumber);
    }
});

it('can generate tracking URLs', function () {
    $package = new Package(new FedEx(), '000000000000');
    expect($package->getTrackingUrl())->toBe('https://www.fedex.com/fedextrack/?trknbr=000000000000');
});

it('can parse fedex tracking numbers', function () {
    $instance = new PackageTracking();
    $trackingNumber = '000000000000';
    $package = $instance->parseTrackingNumber($trackingNumber);

    expect($package)->toBeInstanceOf(Package::class)
        ->and($package->carrier->getName())->toBe('FedEx')
        ->and($package->trackingNumber)->toBe('000000000000');
});
