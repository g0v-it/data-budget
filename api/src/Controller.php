<?php
/*
 * This file is part of the LODMAP2D PHP SDK project.
 *
 * (c) Enrico Fagnoni <enrico@linkeddata.center>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace LODMAP2D;

use uSilex\Psr11Trait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;

class Controller implements MiddlewareInterface
{
    use Psr11Trait;
       
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $store = $this->get('store');
        $resource = $request->getAttribute('resource');
        $resourceId = $request->getAttribute('id');
        $queryTemplate = file_get_contents(__DIR__ . "/Queries/{$resource}.rq");
        
        // expand variables inside $query
        eval("\$query = \"$queryTemplate\";");
        
        return $store->request('POST', null, [
            'body' => $query,
            'headers' => [
                'Content-Type'  => 'application/sparql-query',
                'Accept'        => 'text/turtle'
            ]
        ]);
    }
}
