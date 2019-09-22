<?php

namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Pimple\Container;
use EnvProvider\EnvProvider;


class EnvProviderTest extends TestCase
{

    public function testParametersWithEnvironment ()
	{
	    $parameters = array(
				array('EKB_DEBUG',					'debug',				'true',	true),
				array('EKB_NAMESPACE',				'namespace',			'testnamespace',	'testnamespace'),
				array('EKB_TRUSTED_PROXIES',		'trusted.proxies', 		'["abc","def"]',	array("abc","def")),
				array('EKB_MONOLOG_LEVEL',			'monolog.level',		'1234',				'1234'),
				array('EKB_MONOLOG_NAME',			'monolog.name',			'testmonologname',	'testmonologname'),
				array('EKB_PREDIS_PARAMETERS',		'predis.parameters',	'testpredisparam',	'testpredisparam'),
				array('EKB_SPARQL_CACHE_DIR',		'sparql.cache.dir',		'/temp/cache',		'/temp/cache'),
				array('EKB_PROFILER_PROFILE_ENDPOINT',			'profiler.profile.endpoint',			'/temp/url',		'/temp/url'),
				array('EKB_PROFILER_USER_ENDPOINT',			'profiler.profile.endpoint',			'/temp/url',		'/temp/url'),
				array('EKB_PROFILER_CACHE_TTL',		'profiler.cache.ttl',	1,					1),
				array('EKB_PROFILER_CACHE_TTL',		'profiler.cache.ttl',	1,					1),
				array('EKB_PROFILER_CONFIG',		'profiler.config',		'{ "a": "b" }',		'{ "a": "b" }'),
				array('EKB_SPARQL_CACHE_ENABLED',	'sparql.cache.enabled',	'false',			'false'),
				array('EKB_SPARQL_CACHE_TTL',		'sparql.cache.ttl',		2,					2),
				array('EKB_CORS_ALLOWORIGIN',		'cors.allowOrigin',	 	"testorigin",		"testorigin"),
	        );

		$app = new Container;
				
		// load environment and dumm app values
		foreach ($parameters as $parameter) {
			list($envName, $name, $envValue, $expected) = $parameter;
			putenv("$envName=$envValue");
			$app[$name]= 'dummy_value_'.uniqid();
		}
		

		$app->register(new EnvProvider(), array( 
			'env.prefix' => 'EKB_',
			'env.vars' => array(
				'debug'						=> 'env.cast.boolean',		// <= (boolean) EKB_DEBUG
				'namespace'					=> 'env.cast.strval',		// <= (string) EKB_NAMESPACE
				'trusted.proxies'			=> 'env.cast.json_decode',	// <= (Json array) EKB_TRUSTED_PROXIES 
				'monolog.level'				=> 'env.cast.strval',		// <= (string) EKB_MONOLOG_LEVEL 
				'monolog.name'				=> 'env.cast.strval',		// <= (string) EKB_MONOLOG_NAME 
				'predis.parameters'			=> 'env.cast.strval',		// <= (string) EKB_PREDIS_PARAMETERS
				'profiler.profile.endpoint'	=> 'env.cast.strval',		// <= (string) EKB_PROFILER_PROFILE_ENDPOINT
				'profiler.user.endpoint'	=> 'env.cast.strval',		// <= (string) EKB_PROFILER_USER_ENDPOINT
				'profiler.cache.ttl'		=> 'env.cast.json_decode',	// <= (Json array) EKB_PROFILER_CACHE_TTL
				'profiler.config'			=> 'env.cast.strval',		// <= (Json object) EKB_PROFILER_CONFIG
				'sparql.cache.dir'			=> 'env.cast.strval',		// <= (string) EKB_SPARQL_CACHE_DIR 
				'sparql.cache.enabled'		=> 'env.cast.strval',		// <= ('true' or 'false' or 'auto') EKB_SPARQL_CACHE_ENABLED
				'sparql.cache.ttl'			=> 'env.cast.intval',		// <= (integer) EKB_SPARQL_CACHE_TTL
				'cors.allowOrigin'			=> 'env.cast.strval',		// <= (string) EKB_CORS_ALLOWORIGIN
			)
		));
		$app['env.overload'];
		
		foreach ($parameters as $parameter) {
			list($envName, $name, $envValue, $expected) = $parameter;
	
			$this->assertEquals($envValue,getenv($envName), "Environment variable $envName has a value.");
			$this->assertEquals($expected,$app[$name], "App variable $name has a value.");
		}	
	}

    public function testParametersWithNoEnvironment ()
	{
		
		putenv("TEST2=xxxx");
		$app = new Container( array(
			'test1' => 'a not overriden test value',		
			'test2' => 'overriden test value by xxxx',
		));

		$app->register(new EnvProvider(), array( 
			'env.vars' => array(
				'test1'						=> 'env.cast.strval',		// not overridden because TEST1 environment var does not exist
				'test2'						=> 'env.cast.strval',		// overridden by TEST2 environment var
			)
		));
		
		
		$app['env.overload'];
		
		$this->assertEquals('a not overriden test value', $app['test1']);
		$this->assertEquals('xxxx', $app['test2']);
	}
	
    /**
     * @expectedException PHPUnit\Framework\Error\Error
     */
    public function testParametersWithUnexistentCast ()
	{
		putenv("TEST=xxxx");
		$app = new Container( array(	
			'test' => 'a value',
		));
		$app->register(new EnvProvider(), array( 
			'env.vars' => array(
				'test'						=> 'unknown',		// unexisting cast service
			)
		));
		$app['env.overload']; // here a E_NOTICE php error is thrown
	}
}
	

