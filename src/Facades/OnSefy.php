<?php

namespace OnSefy\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

class OnSefy extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'onsefy';
    }
}
