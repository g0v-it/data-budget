<?php

/*
 * This file is part of the uSilex framework.
 *
 * (c) Enrico Fagnoni <enrico@linkeddata.center>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace uSilex\Tests;

use PHPUnit\Framework\TestCase;
use uSilex\Application;

class ContainerAwareTraitTest extends TestCase
{
    public function testTrait()
    {
        $psr11Container = new Application(['a_key'=>'a_value']);
        $obj = new class() {
            use \uSilex\ContainerAwareTrait;
        };
        $obj->setContainer($psr11Container);
        
        $actualContainer = $obj->getContainer();
        $this->assertEquals('a_value', $obj->getContainer()->get('a_key'));
        $this->assertEquals('a_value', $obj->containerGet('a_key'));
        $this->assertEquals('a_value', $obj->containerGet('a_key', 'ignore default'));
        $this->assertEquals('default', $obj->containerGet('not__key', 'default'));
    }
    
    public function testcontainerGetWithEmptyContainer()
    {
        $obj = new class() {
            use \uSilex\ContainerAwareTrait;
        };
        $this->assertEquals('default', $obj->containerGet('not_key', 'default'));
    }
}
