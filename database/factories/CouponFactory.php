<?php

/*
 * This file is part of the lian/coupon.
 *
 * (c) shenyifei <m809745357@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

use Faker\Generator as Faker;
use Lian\Coupon\Models\Coupon;

$factory->define(Coupon::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'amount' => $faker->randomFloat(2, $min = 0, $max = 10),
        'status' => 1,
        'start_time' => now(),
        'end_time' => now()->addDays(7),
    ];
});
