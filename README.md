<h1 align="center"> coupon </h1>

<p align="center"> 一个公用的优惠券管理工具.</p>


## 安装

```shell
$ composer require lian/coupon -vvv
```

## 使用

发布 migration 文件

```shell
php artisan vendor:publish --provider="Lian\CouponCouponServiceProvider" --tag="migrations"
```

发布 config 文件

```shell
php artisan vendor:publish --provider="Lian\CouponCouponServiceProvider" --tag="config"
```

修改配置文件

config/coupon.php

```php
<?php

return [
    'model' => \App\User::class //修改成自己的 User 模型 eg. \App\Models\User::class
];

```

增加 trait

```php
use Lian\Coupon\Traits\HasCoupon;
...
use HasCoupon
```

可以使用的方法

```php

// 用户创建优惠券
$user->addCoupon([
    'title' => 'this is a title',
    'amount' => 1.00,
    'start_time' => now(),
    'end_time' -> now()->addDays(7)
]);

// or
$user->addCouponOnce(1.00); // 默认 7 天

$user->addCouponOnce(1.00, 7);

$user->addCouponOnce(1.00, 7, 'this is a title');

// 用户领取优惠券
$user->receiveCoupon($coupon);

// 判断优惠券是否使用
$coupon->isBeUsed();

// 判断优惠券是否过期
$coupon->isBeOverdue();

// 使用优惠券
$coupon->apply();

// 获取优惠券到期时间
$coupon->distanceEndTime();
```

## 测试

```shell
$ composer test
```

## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/lian/coupon/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/lian/coupon/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## License

MIT