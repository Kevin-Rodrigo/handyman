<?php

namespace App\Lib;

interface QueryInterface
{
    public function find($column, $value);
}
