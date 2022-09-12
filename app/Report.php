<?php

namespace App;

use Corcel\Post as Corcel;

class Report extends Corcel
{
	protected $connection = 'wordpress';
    protected $postType = 'report';

    public function __construct(array $attributes = []) {
        array_push($this->appends, 'reportMeta');
        parent::__construct($attributes);
    }

    public function getReportMetaAttribute() {
        $meta = [];
        foreach ($this->meta->toArray() as $m) {
            $meta[$m['meta_key']] = $m['meta_value'];
        }
        return $meta;
    }
}

