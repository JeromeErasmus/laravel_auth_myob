<?php

namespace Creativecurtis\Laramyob;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Creativecurtis\Laramyob\Skeleton\SkeletonClass
 */
class LaramyobFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laramyob';
    }
}
