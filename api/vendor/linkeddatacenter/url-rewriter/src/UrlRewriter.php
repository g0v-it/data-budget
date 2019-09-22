<?php
namespace LinkedDataCenter;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use RuntimeException;

/**
 * This middleware rewrite urls using the rules in 'urlRewriting.rules' container parameter
 */
class UrlRewriter implements MiddlewareInterface
{ 
    
    /**
     * @var array of rules to be executed on Response uri
     */
    private $rules;
    
    
    /**
     * Configure the rewriting rules.
     */
    public function __construct(array $rules)
    {        
        $this->rules = $rules;
    }
    
    
    /**
     * Process a server request and return a response.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $uri = $request->getUri();
        if ($scheme = $uri->getScheme()) { $scheme .= ':';}
        $authority = $uri->getAuthority();
        $path = $uri->getPath();
        if ($query = $uri->getQuery()) { $query = '?'.$query;};
        if ($fragment = $uri->getFragment()) { $fragment = '#'.$fragment;};
        
        // invariant part is not considered in match
        $invariantUriPart = "$scheme//$authority";
        
        // $matchSubject is the portion of the uri we are interest in
        $replacedUriPart=$this->appyRules($path.$query.$fragment);
        
        $parsedNewUrl = parse_url($invariantUriPart.$replacedUriPart );
        
        $request = $request->withUri(
            $uri->withPath($parsedNewUrl['path']?:'')
            ->withQuery($parsedNewUrl['query']?:'')
            ->withFragment($parsedNewUrl['fragment']?:'')
        );
        
        return $handler->handle($request);
    }
    
    
    /**
     * Apply all matching rules to a path.
     */
    private function appyRules(string $subject): string
    {
        foreach($this->rules as $pattern=>$replacement) {
            $pattern = '#^'. $pattern.'$#';
            $subject = preg_replace($pattern, $replacement, $subject);
            if( is_null($subject)){
                throw new RuntimeException("Bad replacement '$pattern', '$replacement'");
            }
        }
        
        return $subject;
    }
    
}