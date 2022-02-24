<?php

namespace tests\units;

use \app\routing\Route;

class RouteTest extends \PHPUnit\Framework\TestCase
{
	private Route $route;

	public function testItCanReturnsValidCredentials()
	{
		$this->route = new Route('GET', '/about', 'test', 'about_us');

		$this->assertEquals(
			'GET',
			$this->route->getMethod()
		);

		$this->assertEquals(
			'/about',
			$this->route->getPath()
		);

		$this->assertEquals(
			'test',
			$this->route->getTarget()
		);

		$this->assertEquals(
			'about_us',
			$this->route->getSlug()
		);
	}

	public function testItTrimSlug()
	{
		$this->route = new Route('GET', '/about', 'test', '   about_us   ');

		$this->assertEquals(
			'about_us',
			$this->route->getSlug()
		);
	}

	public function testItConvertSlugToLowerCase()
	{
		$this->route = new Route('GET', '/about', 'test', 'ABOUT_US');

		$this->assertEquals(
			'about_us',
			$this->route->getSlug()
		);
	}

	public function testItConvertMethodToUpperCase()
	{
		$this->route = new Route('get', '/about', 'test', 'about_us');

		$this->assertEquals(
			'GET',
			$this->route->getMethod()
		);
	}

	public function testItRemoveNotValidCharactersFromSlug()
	{
		$this->route = new Route('GET', '/about', 'test', '/ab$out_us');

		$this->assertEquals(
			'about_us',
			$this->route->getSlug()
		);
	}

	public function testItThrowsSlugIsEmptyExceptionIfSlugIsEmpty()
	{
		$this->expectException(\app\exceptions\SlugIsEmptyException::class);

		$this->route = new Route('GET', '/about', 'test', '');
	}
}
