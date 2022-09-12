<?php
/**
 * Created by PhpStorm.
 * User: Geethu
 * Date: 4/25/2017
 * Time: 16:43
 */

namespace App;

use Corcel\Post as Corcel;

class Coupon extends Corcel {
	protected $connection = 'wordpress';
	protected $postType = 'coupon';
}