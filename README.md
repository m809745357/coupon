<h1 align="center"> coupon </h1>

![Image text](https://raw.githubusercontent.com/m809745357/coupon/master/images/carbon.png)

<p align="center"> 一个公用的优惠券管理工具.</p>

[![Build Status](https://travis-ci.org/m809745357/coupon.svg?branch=master)](https://travis-ci.org/m809745357/coupon)
![StyleCI build status](https://github.styleci.io/repos/146409515/shield)
[![codecov](https://codecov.io/gh/m809745357/coupon/branch/master/graph/badge.svg)](https://codecov.io/gh/m809745357/coupon)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/5d1c0c2d661346a0953e7025c14f4318)](https://www.codacy.com/app/m809745357/coupon?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=m809745357/coupon&amp;utm_campaign=Badge_Grade)
[![Latest Stable Version](https://poser.pugx.org/lian/coupon/v/stable)](https://packagist.org/packages/lian/coupon)
[![Total Downloads](https://poser.pugx.org/lian/coupon/downloads)](https://packagist.org/packages/lian/coupon)
[![Latest Unstable Version](https://poser.pugx.org/lian/coupon/v/unstable)](https://packagist.org/packages/lian/coupon)
[![License](https://poser.pugx.org/lian/coupon/license)](https://packagist.org/packages/lian/coupon)

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
    'model' => \App\User::class, //修改成自己的 User 模型 eg. \App\Models\User::class
    
    'distance' => 7,

    'title' => 'coupon',
];

```

增加 trait

```php
<?php

use Lian\Coupon\Traits\HasCoupon;
...
use HasCoupon;
```

可以使用的方法

```php
<?php

// 用户创建优惠券
$user->addCoupon([
    'title' => 'this is a title',
    'amount' => 1.00,
    'start_time' => now(),
    'end_time' -> now()->addDays(7)
]);

// or
$user->addCouponOnce(1.00); // 默认 7 天

$user->addCouponOnce(1.00, 7); // 默认标题 coupon

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

1. File bug reports using the [issue tracker](https://github.com/m809745357/coupon/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/m809745357/coupon/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## License

MIT