<?php
declare(strict_types = 1);
namespace LinkedDataCenter\Tests;
use LinkedDataCenter\UrlRewriter;
use Middlewares\Utils\Dispatcher;
use Middlewares\Utils\Factory;
use PHPUnit\Framework\TestCase;

class UrlRewriterTest extends TestCase
{
    public function pathProvider()
    {
        return [
            [
                'http://localhost/pippo',
                'http://localhost/pippo/pluto'
            ],
            [
                'http://localhost:800/pippo',
                'http://localhost:800/pippo/pluto'
            ],
            [
                '//localhost:800/pippo',
                '//localhost:800/pippo/pluto'
            ],
            [
                'https://user:password@localhost:800/pippo',
                'https://user:password@localhost:800/pippo/pluto'
            ],
            [
                'https://example.com/document/master/title.csv#part1',
                'https://example.com/document/docstore?db=master&table=title&format=csv#part1'
            ],
        ];
    }
    /**
     * @dataProvider pathProvider
     */
    public function testUrlRewriter(  string $uri, string $result)
    {
        $rules =  [
            '/(\w+)' => '/$1/pluto',
            '/(\w+)/(\w+)/(\w+).(csv|json|xml)(.*)' =>'/$1/docstore?db=$2&table=$3&format=$4$5',
        ];
        $request = Factory::createServerRequest('GET', $uri);
        $response = Dispatcher::run([
            new UrlRewriter($rules),
            function ($request){
                echo $request->getUri();
                $response = Factory::createResponse();
                return $response;
            },
        ], $request);
        $this->assertEquals($result, (string) $response->getBody());
    }
}