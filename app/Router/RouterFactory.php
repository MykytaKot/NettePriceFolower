<?php

declare(strict_types=1);

namespace App\Router;

use Nette;
use Nette\Application\Routers\RouteList;


final class RouterFactory
{
	use Nette\StaticClass;

	public static function createRouter(): RouteList
	{
		$router = new RouteList;
		$router->addRoute('<presenter>/<action>[/<id>]', 'Home:default');
		$router->addRoute('page/<page>', 'Home:paged');
		$router->addRoute('product/<id>', 'Product:default');
		$router->addRoute('shops/<id>', 'Shop:default');
		
		return $router;
	}
}
