<?php

namespace Lian\Coupon\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Lian\Coupon\Exceptions\CouponAlreadyUsed;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Lian\Coupon\Exceptions\CouponAlreadyOverdue;

class Coupon extends Model
{
    protected $fillable = ['title', 'user_id', 'amount', 'start_time', 'end_time'];

    protected $table = 'coupons';

    /**
     * 用户关联优惠券表
     *
     * @return BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(config('coupon.model'), 'user_id', 'id');
    }

    /**
     * 判断用户优惠券是否过期
     *
     * @return boolean
     */
    public function isBeOverdue() : bool
    {
        return now()->gt(Carbon::parse($this->end_time));
    }

    /**
     * 判断用户优惠券是否过期
     *
     * @return boolean
     */
    public function isBeUsed() : bool
    {
        return $this->status == 2;
    }

    /**
     * 获取用户优惠券到期时间
     *
     * @return string
     */
    public function distanceEndTime() : string
    {
        return Carbon::parse($this->end_time)->diffForHumans();
    }

    /**
     * 使用优惠券
     *
     * @return void
     */
    public function apply()
    {
        if ($this->isBeUsed()) {
            throw new CouponAlreadyUsed('coupon has already be used!');
        }

        if ($this->isBeOverdue()) {
            $this->update(['status' => 3]);
            throw new CouponAlreadyOverdue('coupon has already be overdue!');
        }

        return $this->update(['status' => 1]);
    }
}
