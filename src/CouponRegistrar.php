<?php

namespace Lian\Coupon;

use Lian\Coupon\Models\Coupon;

class CouponRegistrar
{
    public function __construct()
    {
    }

    public function create($attributes)
    {
        Coupon::create($attributes);
    }
}
