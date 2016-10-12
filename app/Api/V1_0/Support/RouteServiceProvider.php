<?php
namespace ERP\Api\V1_0\Support;

use Illuminate\Routing\Router;
use Illuminate\Http\Request;
use ERP\Http\Requests;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router as IlluminateRouter;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class RouteServiceProvider extends ServiceProvider
{
	/**
     * @var namespace    
     */
	protected $namespace ="";
	/**
	 * below function is for going to the particular Routes file for get,post,patch and delete Request 
	 * @param Router $router	 
	 */
	protected function define(Router $router)
    {	
		//splitting components from url
		$splitUri = explode("/", $_SERVER['REQUEST_URI']);
		$convertedString = str_replace(' ', '', ucwords(str_replace('-', ' ', $splitUri[1])));
		
		//accessing multiple components dynamically from url
		$controllerPath = 'ERP\Api\V1_0\\'.$convertedString.'\\Controllers'; 
		$router->group([ 
            'namespace' => $controllerPath
        ],function (Router $router) {
            $packages = $this->app->make('config')->get('app.packages');
			$splitUriRoute = explode("/", $_SERVER['REQUEST_URI']); 
			$convertedString1 = str_replace(' ', '', ucwords(str_replace('-', ' ', $splitUriRoute[1])));
			$convertedString2= str_replace(' ', '', ucwords(str_replace('-', ' ', $splitUriRoute[2])));
			
			foreach ($packages as $package) {			
				//condition for going to particular route file as per url	
				if(!strcmp($package,$convertedString1)) 
				{
					$path = app_path('Api\V1_0\\' . str_replace('\\', '/', $package) .'\\Routes');		
					$namespace = 'ERP\Api\V1_0\\' . $package ;		
					//go to the register method from particular Route class 
					$this->app->make($namespace .'\\Routes\\' . $convertedString2)
					->register($router);	
					break;
				}							
            }		
				
        });
    }
	
    /**
     * @param IlluminateRouter $router
     */
    public function map(IlluminateRouter $router)	
    {		
        /**
         * @var Router $proxy
         */		 
		$proxy = $this->app->make(Router::class);		
        $this->define($proxy);
        $routes = $router->getRoutes();	
			
        foreach ($proxy->getRoutes() as $route) {	
			$routes->add($route);				
        }
		$router->setRoutes($routes);
    }
}