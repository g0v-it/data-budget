<?php
namespace EnvProvider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class EnvProvider implements ServiceProviderInterface
{
    /**
     * @inheritdoc
     */
    public function register(Container $app)
    {
        $app['env.prefix'] = '';
		$app['env.vars'] = array();
		
		// predefined casting functions
		$app['env.cast.strval'] = $app->protect(function($str) {
			return strval($str);
		});
		
		$app['env.cast.boolean'] = $app->protect(function($str) {
			return ((0===strcasecmp('true',$str))||($str==='1'));
		});
		
		$app['env.cast.intval'] = $app->protect(function($str) {
			return intval($str);
		});
		
		$app['env.cast.json_decode'] = $app->protect(function($str) {
			return json_decode($str,true);
		});
		
		// default name builder
		$app['env.name.builder'] = $app->protect(function($parameterName) {
			return strtoupper((str_replace('.', '_', $parameterName)));;
		});
		
        $app['env.overload'] = function ($app) {
        	foreach($app['env.vars'] as $var => $cast){
        		$envVar =  $app['env.prefix'] . $app['env.name.builder']($var);
        		if( getenv($envVar)) {
        			if(isset($app[$cast])) {
        				$app[$var]= $app[$cast](getenv($envVar));
        			} else {
        				\trigger_error("Undefined cast service $cast");
        			}
        		}
        	}
			return $app;
        };
    }

}