<?php

namespace Lian\Coupon\Test;

use InvalidArgumentException;
use Lian\Coupon\Models\Coupon;
use Lian\Coupon\Exceptions\CouponAlreadyUsed;
use Lian\Coupon\Exceptions\CouponAlreadyOverdue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CouponTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 测试创建优惠券.
     *
     * @test
     */
    public function testCreateCoupon()
    {
        $attributes = factory(Coupon::class)->make()->toArray();

        $this->user->addCoupon($attributes);

        $this->assertDatabaseHas('coupons', $attributes);

        $this->user->addCouponOnce(1.00, 7, 'this is a title');

        $this->assertDatabaseHas('coupons', ['amount' => 1.00, 'title' => 'this is a title']);
    }

    /**
     * 测试异常的创建优惠券.
     */
    public function testCreateCouponInvalidArgumentAmount()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid amount: 1');

        $this->user->addCouponOnce(1);
    }

    /**
     * 测试异常的创建优惠券.
     */
    public function testCreateCouponInvalidArgumentDistance()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid distance');

        $this->user->addCouponOnce(1.00, 'test');
    }

    /**
     * 测试异常的创建优惠券.
     */
    public function testCreateCouponInvalidArgumentTitle()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid title');

        $this->user->addCouponOnce(1.00, 7, 1);
    }

    /**
     * 测试优惠券属于用户.
     *
     * @test
     */
    public function testCouponBelongsToUser()
    {
        $attributes = factory(Coupon::class)->make()->toArray();

        $this->user->addCoupon($attributes);

        $this->assertInstanceOf(
            'Lian\Coupon\Test\User',
            $this->user
        );
    }

    /**
     * 测试用户有多张优惠券.
     */
    public function testUserHasManyCoupon()
    {
        $attributes = factory(Coupon::class)->make()->toArray();

        $this->user->addCoupon($attributes);

        $this->assertInstanceOf(
            'Illuminate\Database\Eloquent\Collection',
            $this->user->coupons
        );
    }

    /**
     * 测试用户添加优惠券.
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
     * 测试创建没有领取的优惠券.
     */
    public function testCreateCouponNotHasUser()
    {
        $coupon = factory(Coupon::class)->create();

        $this->assertDatabaseHas('coupons', $coupon->toArray());

        $this->assertDatabaseMissing('coupons', ['user_id' => $this->user->id]);
    }

    /**
     * 测试用户领取优惠券.
     */
    public function testUserAssociateCoupon()
    {
        $coupon = factory(Coupon::class)->create();

        $this->assertDatabaseMissing('coupons', ['user_id' => $this->user->id]);

        $this->user->receiveCoupon($coupon);

        $this->assertDatabaseHas('coupons', ['user_id' => $this->user->id]);
    }

    /**
     * 测试用户领取的优惠券过期
     */
    public function testCouponHasBeOverdue()
    {
        $coupon = factory(Coupon::class)->create([
            'start_time' => now()->subDays(7),
            'end_time' => now()->subDay(),
        ]);

        $this->assertTrue($coupon->isBeOverdue());
    }

    /**
     * 测试距离优惠券失效时间.
     */
    public function testCouponEndTimeDiffForHumans()
    {
        $now = now();

        $coupon = factory(Coupon::class)->create([
            'start_time' => $now->subDays(7),
            'end_time' => $now->subDay(),
        ]);

        $this->assertTrue($now->addDays(7)->diffForHumans() == $coupon->distanceEndTime());
    }

    /**
     * 测试优惠券可以被使用.
     */
    public function testCouponHasBeUsed()
    {
        $coupon = factory(Coupon::class)->create(['user_id' => $this->user->id]);

        $coupon->apply();

        $this->assertDatabaseHas('coupons', ['user_id' => $this->user->id, 'status' => 1]);
    }

    /**
     * 测试优惠券已经被使用.
     */
    public function testCouponHasBeAlreadyUsed()
    {
        $coupon = factory(Coupon::class)->create(['user_id' => $this->user->id, 'status' => 2]);

        $this->expectException(CouponAlreadyUsed::class);
        $this->expectExceptionMessage('coupon has already be used!');

        $coupon->apply();
    }

    /**
     * 测试优惠券已过期并修改.
     */
    public function testCouponHasBeAlreadyOverdue()
    {
        $now = now();

        $coupon = factory(Coupon::class)->create([
            'user_id' => $this->user->id,
            'start_time' => $now->subDays(7),
            'end_time' => $now->subDay(),
            'status' => 1,
        ]);

        $this->expectException(CouponAlreadyOverdue::class);
        $this->expectExceptionMessage('coupon has already be overdue!');

        $coupon->apply();

        $this->assertDatabaseHas('coupons', ['user_id' => $this->user->id, 'status' => 3]);
    }
}
