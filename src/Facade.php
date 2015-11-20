<?php

namespace Axn\Crudivor;

use Illuminate\Support\Facades\Facade as BaseFacade;

class Facade extends BaseFacade
{
    public static function getFacadeAccessor()
    {
        return 'crudivor';
    }
}
