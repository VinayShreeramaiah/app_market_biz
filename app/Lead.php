<?php
/**
 * Created by IntelliJ IDEA.
 * User: nidheeshdas
 * Date: 20/03/17
 * Time: 10:16 PM
 */

namespace App;


use Corcel\Post as Corcel;

class Lead extends Corcel
{
    protected $connection = 'wordpress';
    protected $postType = 'lead';


}