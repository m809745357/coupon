<?php

namespace Lian\Coupon\Test;

use Lian\Coupon\CouponRegistrar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lian\Coupon\Models\Coupon;

class CouponTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 测试创建优惠券
     *
     * @test
     * @return void
     */
    public function testCreateCoupon()
    {
        $attributes = factory(Coupon::class)->make(['user_id' => $this->user->id])->toArray();

        $this->app[CouponRegistrar::class]->create($attributes);

        $this->assertDatabaseHas('coupons', $attributes);
    }

    /**
     * 测试优惠券属于用户
     *
     * @test
     * @return void
     */
    public function testCouponBelongsToUser()
    {
        $attributes = factory(Coupon::class)->make(['user_id' => $this->user->id])->toArray();

        $this->app[CouponRegistrar::class]->create($attributes);

        $this->assertInstanceOf(
            'Lian\Coupon\Test\User',
            $this->user
        );
    }

    /**
     * 测试用户有多张优惠券
     *
     * @return void
     */
    public function testUserHasManyCoupon()
    {
        $attributes = factory(Coupon::class)->make(['user_id' => $this->user->id])->toArray();

        $this->app[CouponRegistrar::class]->create($attributes);

        $this->assertInstanceOf(
            'Illuminate\Database\Eloquent\Collection',
            $this->user->coupons
        );
    }

    /**
     * 测试用户添加优惠券
     *
     * @return void
     */
    public function testUserAddCoupon()
    {
        $attributes = factory(Coupon::class)->make()->toArray();

        $this->assertInstanceOf(
            'Lian\Coupon\Models\Coupon',
            $this->user->addCoupon($attributes)
        );
    }

    /**
     * 测试创建没有领取的优惠券
     *
     * @return void
     */
    public function testCreateCouponNotHasUser()
    {
        $attributes = factory(Coupon::class)->make()->toArray();

        $this->app[CouponRegistrar::class]->create($attributes);

        $this->assertDatabaseHas('coupons', $attributes);

        $this->assertDatabaseMissing('coupons', ['user_id' => $this->user->id]);
    }

    /**
     * 测试用户领取优惠券
     *
     * @return void
     */
    public function testUserAssociateCoupon()
    {
        $coupon = factory(Coupon::class)->create();

        $this->assertDatabaseMissing('coupons', ['user_id' => $this->user->id]);

        $this->user->receiveCoupon($coupon);

        $this->assertDatabaseHas('coupons', ['user_id' => $this->user->id]);
    }
}
