<?php

namespace Httpstack\App;


class Router {
    private array $routes = [];
    private $container;

    public function __construct($container = null) {
        $this->container = $container;
    }

    public function addRoute(string $method, string $path, $handler): void {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $this->parsePath($path),
            'handler' => $handler,
        ];
    }

    public function get(string $path, $handler): void {
        $this->addRoute('GET', $path, $handler);
    }

    private function parsePath(string $path): string {
        // Convert placeholders like /user/{id} to regex /user/([^/]+)
        return preg_replace('/\{([^}]+)\}/', '([^/]+)', $path);
    }
 
    public function dispatch($request, $response): void {
        // Get the method and URI from the Request object
        $method = $request->getMethod();
        $uri = $request->getUri(); // Normalize URI
    
        // Separate the path and query string
        $uriParts = explode('?', $uri, 2);
        $path = $uriParts[0]; // The path part of the URI
        $queryString = $uriParts[1] ?? ''; // The query string part, if present
    
        // Parse query string into an associative array
        parse_str($queryString, $queryParams);
    
        foreach ($this->routes as $route) {
            if ($method === $route['method'] && preg_match('#^' . $route['path'] . '$#', $path, $matches)) {
                array_shift($matches); // Remove the full match
    
                // Resolve the handler
                $handler = $route['handler'];
    
                // If the handler is a class method, resolve it from the container
                if (is_array($handler) && is_string($handler[0]) && $this->container) {
                    $handler[0] = $this->container->resolve($handler[0]);
                }
    
                // Add URL parameters to the Request object
                $request->setParams($matches);
    
                // Add query string parameters to the Request object
                $request->setQueryParams($queryParams);
    
                // Call the handler with Request, Response, and Container
                call_user_func($handler, $request, $response, ...$matches);
                return;
            }
        }
    
        // If no route matches, return a 404 response
        $response->setStatusCode(404);
        $response->setBody('<h1>404 Not Found</h1>');
        $response->send();
    }
}