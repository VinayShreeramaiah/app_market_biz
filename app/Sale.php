<?php

namespace App;

use Corcel\Post as Corcel;

class Sale extends Corcel
{
    protected $connection = 'wordpress';
    protected $postType = 'sale';


}