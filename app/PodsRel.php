<?php
/**
 * Created by IntelliJ IDEA.
 * User: nidheeshdas
 * Date: 10/04/17
 * Time: 10:10 AM
 */

namespace app;


use Corcel\Model;

class PodsRel extends Model {
	public $timestamps = false;
	protected $connection = 'wordpress';
	protected $table = 'podsrel';
	protected $primaryKey = 'id';
}