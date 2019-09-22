<?php
declare(strict_types = 1);

namespace Middlewares;

use Psr\Http\Server\MiddlewareInterface;

class DeflateEncoder extends Encoder implements MiddlewareInterface
{
    /**
     * @var string
     */
    protected $encoding = 'deflate';

    /**
     * {@inheritdoc}
     */
    protected function encode(string $content): string
    {
        return gzdeflate($content);
    }
}
