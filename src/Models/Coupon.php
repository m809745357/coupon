<?php

namespace Lian\Coupon\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Coupon extends Model
{
    protected $fillable = ['title', 'user_id', 'amount', 'start_time', 'end_time'];
    public $timestamps = false;
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
}
