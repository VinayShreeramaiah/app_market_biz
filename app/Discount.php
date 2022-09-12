<?php

namespace App;

use Corcel\Post as Corcel;

class Discount extends Corcel
{


	protected $connection = 'wordpress';
	protected $postType = 'discount';

	public function __construct(array $attributes = []) {
		parent::__construct($attributes);
	}
}

