<?php

/*
 * This file is part of the uSilex framework.
 *
 * (c) Enrico Fagnoni <enrico@linkeddata.center>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace uSilex;

use Pimple\Container as PimpleContainer;

/**
 * Psr11 trait. Add a Container interface implementation using Pimple
 *
 * @author Enrico Fagnoni <enrico@linkeddata.center>
 */
trait Psr11Trait
{
    private $pimple;
    
    public function __construct(PimpleContainer $pimple)
    {
        $this->pimple = $pimple;
    }
    
    public function get($id)
    {
        return $this->pimple[$id];
    }
    
    public function has($id)
    {
        return isset($this->pimple[$id]);
    }
}
