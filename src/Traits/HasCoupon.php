<?php

namespace Lian\Coupon\Traits;

use InvalidArgumentException;
use Lian\Coupon\Models\Coupon;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    public function addCouponOnce($amount, $distance = 7, $title = '')
    {
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
