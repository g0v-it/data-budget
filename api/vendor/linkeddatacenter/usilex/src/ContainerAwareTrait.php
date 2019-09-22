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

use Psr\Container\ContainerInterface;
use Pimple\Container as PimpleContainer;

/**
 * Psr11 trait. Realize a Container aware interfce from a pimple container
 *
 * @author Enrico Fagnoni <enrico@linkeddata.center>
 */
trait ContainerAwareTrait
{
    private $container = null;
    
    public function __construct(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    
    public function getContainer() : ContainerInterface
    {
        return $this->container;
    }
    
    
    public function setContainer(ContainerInterface $container) : self
    {
        $this->container = $container;
        
        return $this;
    }
    
    
    /**
     * A shortcut to get a value with a default
     */
    public function containerGet(String $id, $value = null)
    {
        return ($this->container && $this->container->has($id))
            ? $this->container->get($id)
            : $value;
    }
}
