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
use Pimple\Container;

class Psr11TraitTest extends TestCase
{
    public function testTrait()
    {
        $container = new Container(['a_key'=>'a_value']);
        $obj = new class($container) {
            use \uSilex\Psr11Trait;
        };
        
        $this->assertTrue($obj->has('a_key'));
        $this->assertEquals('a_value', $obj->get('a_key'));
    }
}
