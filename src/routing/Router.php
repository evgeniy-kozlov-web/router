<?php

namespace app\routing;

class Router
{
	private array $routes = [];

	public function getRoutes(): array
	{
		return $this->routes;
	}

	public function getPaths(): array
	{
		$paths = [];

		foreach ($this->routes as $route) {
			$paths[] = $route->getPath();
		}

		return $paths;
	}

	public function getRoute(string $slug): Route
	{
		if (!array_key_exists($slug, $this->routes)) throw new \app\exceptions\SlugIsNotExistsException();

		return $this->routes[$slug];
	}

	public function getRouteByPath(string $path): Route
	{
		foreach ($this->routes as $route) {
			if ($route->getPath() === $path) return $route;
		}

		throw new \app\exceptions\PathIsNotExistsException();
	}

	public function check(): bool
	{
		$path = $_SERVER['REQUEST_URI'];

		if (!in_array($path, $this->getPaths())) return false;

		$route = $this->getRouteByPath($path);
		$method = $route->getMethod();

		return $_SERVER['REQUEST_METHOD'] === $method;
	}

	public function addRoute(Route $route): Router
	{
		if (array_key_exists($route->getSlug(), $this->routes)) throw new \app\exceptions\SlugIsAlreadyExistsException();

		$paths = $this->getPaths();

		if (in_array($route->getPath(), $paths)) throw new \app\exceptions\PathIsAlreadyExistsException();

		$this->routes[$route->getSlug()] = $route;

		return $this;
	}
}
