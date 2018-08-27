<?php

namespace Lian\Coupon\Traits;

use Lian\Coupon\Models\Coupon;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasCoupon
{
    /**
     * 关联优惠券表
     *
     * @return HasMany
     */
    public function coupons() : HasMany
    {
        return $this->hasMany(Coupon::class, 'user_id', 'id');
    }

    /**
     * 新增优惠券
     *
     * @param array $coupon
     * @return Coupon
     */
    public function addCoupon(array $coupon) : Coupon
    {
        return $this->coupons()->create($coupon);
    }

    /**
     * 用户领取优惠券
     *
     * @param Coupon $coupon
     * @return Coupon
     */
    public function receiveCoupon(Coupon $coupon) : Coupon
    {
        $coupon->user()->associate($this);
        $coupon->save();

        return $coupon;
    }
}
