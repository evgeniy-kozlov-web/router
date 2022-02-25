<?php

namespace tests\units;

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

	public function testItCanGetRouteBySlug()
	{
		$route = new Route('GET', '/about', 'test', 'about_us');
		$this->router->addRoute($route);

		$this->assertTrue($route->equals($this->router->getRoute('about_us')));
	}

	public function testItReturnsRouteByPath()
	{
		$route = new Route('GET', '/about', 'about', 'about_us');
		$this->assertTrue($route->equals($this->router->addRoute($route)->getRouteByRule('/about', 'GET')));
	}

	public function testItThrowsSlugIsExistsExceptionIfSlugIsRepeats()
	{
		$this->expectException(\app\exceptions\SlugIsAlreadyExistsException::class);

		$this->router->addRoute(new Route('GET', '/about', 'test', 'about_us'))->addRoute(new Route('GET', '/about', 'test', 'about_us'));
	}

	public function testItThrowsRuleIsExistsExceptionIfPathIsRepeats()
	{
		$this->expectException(\app\exceptions\RuleIsAlreadyExistsException::class);

		$this->router->addRoute(new Route('GET', '/about', 'test', 'about_us'))->addRoute(new Route('GET', '/about', 'test', 'not_about_us'));
	}

	public function testItReturnsFalseIfSlugIsNotExists()
	{
		$this->assertFalse($this->router->getRoute('test'));
	}

	public function testItReturnsFalseIfPathIsNotExists()
	{
		$this->assertFalse($this->router->getRouteByRule('test', 'GET'));
	}
}
