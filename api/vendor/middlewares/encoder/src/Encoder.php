<?php
declare(strict_types = 1);

namespace Middlewares;

use Middlewares\Utils\Traits\HasStreamFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

abstract class Encoder
{
    use HasStreamFactory;

    /**
     * @var string
     */
    protected $encoding;

    /**
     * Process a request and return a response.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        if (stripos($request->getHeaderLine('Accept-Encoding'), $this->encoding) !== false
            && !$response->hasHeader('Content-Encoding')
        ) {
            $stream = $this->createStream($this->encode((string) $response->getBody()));

            return $response
                ->withHeader('Content-Encoding', $this->encoding)
                ->withoutHeader('Content-Length')
                ->withBody($stream);
        }

        return $response;
    }

    /**
     * Encode the body content.
     */
    abstract protected function encode(string $content): string;
}
