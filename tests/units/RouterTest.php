<?php

namespace tests\units;

require_once dirname(dirname(__DIR__)) . '/vendor/autoload.php';

use \app\routing\{Router, Route};

class RouterTest extends \PHPUnit\Framework\TestCase
{
	private Router $router;

	public function setUp(): void
	{
		$this->router = new Router();
	}

	public function testItIsDefaultByEmpty()
	{
		$this->assertEmpty($this->router->getRoutes());
	}

	public function testItCanAddRoutes()
	{
		$this->router->addRoute(new Route('GET', '/about', 'test', 'about_us'))->addRoute(new Route('GET', '/contacts', 'contacts', 'contacts'));

		$this->assertEquals(
			2,
			count($this->router->getRoutes())
		);
	}

	public function testItCanChaining()
	{
		$this->assertEquals(
			$this->router->addRoute(new Route('GET', '/about', 'test', 'about_us')),
			$this->router
		);
	}

	public function testItCanGetRouteBySlug()
	{
		$route = new Route('GET', '/about', 'test', 'about_us');
		$this->router->addRoute($route);

		$this->assertTrue($route->equals($this->router->getRoute('about_us')));
	}

	public function testItReturnsValidPaths()
	{
		$this->assertEquals(
			[
				'/about'
			],
			$this->router->addRoute(new Route('GET', '/about', 'about', 'about_us'))->getPaths()
		);
	}

	public function testItReturnsRouteByPath()
	{
		$route = new Route('GET', '/about', 'about', 'about_us');
		$this->assertTrue($route->equals($this->router->addRoute($route)->getRouteByPath('/about')));
	}

	public function testItThrowsSlugIsExistsExceptionIfSlugIsRepeats()
	{
		$this->expectException(\app\exceptions\SlugIsAlreadyExistsException::class);

		$this->router->addRoute(new Route('GET', '/about', 'test', 'about_us'))->addRoute(new Route('GET', '/about', 'test', 'about_us'));
	}

	public function testItThrowsPathIsExistsExceptionIfPathIsRepeats()
	{
		$this->expectException(\app\exceptions\PathIsAlreadyExistsException::class);

		$this->router->addRoute(new Route('GET', '/about', 'test', 'about_us'))->addRoute(new Route('GET', '/about', 'test', 'not_about_us'));
	}

	public function testItThrowsSlugIsNotExistsExceptionIfSlugIsNotExists()
	{
		$this->expectException(\app\exceptions\SlugIsNotExistsException::class);

		$this->router->getRoute('test');
	}

	public function testItThrowsPathIsNotExistsExceptionIfPathIsNotExists()
	{
		$this->expectException(\app\exceptions\PathIsNotExistsException::class);

		$this->router->getRouteByPath('test');
	}
}
