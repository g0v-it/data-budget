<?php
declare(strict_types = 1);

namespace Middlewares;

use Psr\Http\Server\MiddlewareInterface;

class GzipEncoder extends Encoder implements MiddlewareInterface
{
    /**
     * @var string
     */
    protected $encoding = 'gzip';

    /**
     * {@inheritdoc}
     */
    protected function encode(string $content): string
    {
        return gzencode($content);
    }
}
