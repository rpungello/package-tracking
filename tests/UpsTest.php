<?php

use Rpungello\PackageTracking\Carriers\UPS;

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
