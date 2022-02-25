<?php

namespace app\routing;

class Router
{
	private array $routes = [];

	public function getRoutes(): array
	{
		return $this->routes;
	}

	public function getRoute(string $slug): Route | false
	{
		return $this->routes[$slug] ?? false;
	}

	public function getRouteByRule(string $path, string $method): Route | false
	{
		$method = strtoupper($method);

		foreach ($this->routes as $route) {
			if ($route->getPath() === $path && $route->getMethod() === $method) return $route;
		}

		return false;
	}

	public function getCurrentRoute(): Route | bool
	{
		return $this->getRouteByRule($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
	}

	public function addRoute(Route $route): Router
	{
		if (array_key_exists($route->getSlug(), $this->routes)) throw new \app\exceptions\SlugIsAlreadyExistsException();

		if ($this->getRouteByRule($route->getPath(), $route->getMethod())) throw new \app\exceptions\RuleIsAlreadyExistsException();

		$this->routes[$route->getSlug()] = $route;

		return $this;
	}
}
