<?php

/*
 * This file is part of the lian/coupon.
 *
 * (c) shenyifei <m809745357@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Lian\Coupon\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use InvalidArgumentException;
use Lian\Coupon\Models\Coupon;

trait HasCoupon
{
    /**
     * 关联优惠券表.
     *
     * @return HasMany
     */
    public function coupons(): HasMany
    {
        return $this->hasMany(Coupon::class, 'user_id', 'id');
    }

    /**
     * 新增优惠券.
     *
     * @param array $coupon
     *
     * @return Coupon
     */
    public function addCoupon($coupon): Coupon
    {
        if (!\is_array($coupon)) {
            throw new InvalidArgumentException('Invalid coupon: '.$coupon);
        }

        return $this->coupons()->create($coupon);
    }

    /**
     * 新增优惠券.
     *
     * @param float  $amount
     * @param int    $distance
     * @param string $title
     */
    public function addCouponOnce($amount, $distance = 0, $title = '')
    {
        $distance || $distance = config('coupon.distance');
        $title || $title = config('coupon.title');

        if (!\is_float($amount)) {
            throw new InvalidArgumentException('Invalid amount: '.$amount);
        }

        if (!\is_numeric($distance)) {
            throw new InvalidArgumentException('Invalid distance: '.$distance);
        }

        if (!\is_string($title)) {
            throw new InvalidArgumentException('Invalid title: '.$title);
        }

        return $this->coupons()->create([
            'amount' => $amount,
            'start_time' => now(),
            'end_time' => now()->addDays($distance),
            'title' => $title,
        ]);
    }

    /**
     * 用户领取优惠券.
     *
     * @param Coupon $coupon
     *
     * @return Coupon
     */
    public function receiveCoupon(Coupon $coupon): Coupon
    {
        $coupon->user()->associate($this);
        $coupon->save();

        return $coupon;
    }
}
