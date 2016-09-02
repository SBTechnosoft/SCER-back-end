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
		
		//accessing multiple components dynamically from url
		$controllerPath = 'ERP\Api\V1_0\\'.$splitUri[1].'\\Controllers'; 
        $router->group([ 
            'namespace' => $controllerPath
        ],function (Router $router) {
            $packages = $this->app->make('config')->get('app.packages');
			$splitUriRoute = explode("/", $_SERVER['REQUEST_URI']); 
			
			foreach ($packages as $package) {			
				//condition for going to particular route file as per url	
				if(!strcmp($package,$splitUriRoute[1])) 
				{
					$path = app_path('Api\V1_0\\' . str_replace('\\', '/', $package) .'\\Routes');		
					$namespace = 'ERP\Api\V1_0\\' . $package ;				
					if (! file_exists($path)){						
						continue;
					}				
					//go to the register method from particular Route class 
					$this->app->make($namespace . '\\Routes\\' . $splitUriRoute[2])
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