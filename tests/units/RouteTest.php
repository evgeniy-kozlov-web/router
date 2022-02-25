<?php

namespace tests\units;

use \app\routing\Route;

class RouteTest extends \PHPUnit\Framework\TestCase
{
	public function testItCanReturnsValidCredentials()
	{
		$route = new Route('GET', '/about', 'test', 'about_us');

		$this->assertEquals(
			'GET',
			$route->getMethod()
		);

		$this->assertEquals(
			'/about',
			$route->getPath()
		);

		$this->assertEquals(
			'test',
			$route->getTarget()
		);

		$this->assertEquals(
			'about_us',
			$route->getSlug()
		);
	}

	public function testItDeleteSpacesInSlug()
	{
		$route = new Route('GET', '/about', 'test', ' _ _about_us_ _ ');

		$this->assertEquals(
			'about_us',
			$route->getSlug()
		);
	}

	public function testItTrimUnderscoresInSlug()
	{
		$route = new Route('GET', '/about', 'test', '___about_us___');

		$this->assertEquals(
			'about_us',
			$route->getSlug()
		);
	}

	public function testItConvertSlugToLowerCase()
	{
		$route = new Route('GET', '/about', 'test', 'ABOUT_US');

		$this->assertEquals(
			'about_us',
			$route->getSlug()
		);
	}

	public function testItConvertMethodToUpperCase()
	{
		$route = new Route('get', '/about', 'test', 'about_us');

		$this->assertEquals(
			'GET',
			$route->getMethod()
		);
	}

	public function testItRemoveNotValidCharactersFromSlug()
	{
		$route = new Route('GET', '/about', 'test', '/ab$out_us');

		$this->assertEquals(
			'about_us',
			$route->getSlug()
		);
	}

	public function testItThrowsSlugIsEmptyExceptionIfSlugIsEmpty()
	{
		$this->expectException(\app\exceptions\SlugIsEmptyException::class);

		$route = new Route('GET', '/about', 'test', '');
	}
}
