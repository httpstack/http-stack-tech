<?php
namespace Httpstack\App;
use Httpstack\App\Interfaces\ContainerInterface;
class Container implements ContainerInterface {
    protected $bindings = [];
    protected $instances = [];
    private array $props = [];

    public function bind(string $abstract, $concrete) {
        $this->bindings[$abstract] = $concrete;
    }
    public function addProperty(string $name, $value) {
        $this->props[$name] = $value;
    }
    public function removeProperty(string $name) {
        unset($this->props[$name]);
    }
    public function getProperty(string $name) {
        return $this->props[$name] ?? null;
    }
    public function hasProperty(string $name) {
        return isset($this->props[$name]);
    }
    public function singleton(string $abstract, $concrete) {
        $this->bindings[$abstract] = $concrete;
        $this->instances[$abstract] = null; // Mark it as a singleton
    }

    public function make(string $abstract, array $params = []) {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        return $this->resolve($abstract, $params);
    }

    public function resolve(string $abstract, array $params = []) {
        if (!isset($this->bindings[$abstract])) {
            throw new Exception("No binding found for {$abstract}");
        }

        $concrete = $this->bindings[$abstract];

        if (is_callable($concrete)) {
            return $concrete($this, ...$params);
        }

        if (is_string($concrete)) {
            return $this->build($concrete, $params);
        }

        return $concrete;
    }

    protected function build(string $concrete, array $params = []) {
        $reflector = new ReflectionClass($concrete);

        if (!$reflector->isInstantiable()) {
            throw new Exception("Cannot instantiate {$concrete}");
        }

        $constructor = $reflector->getConstructor();

        if (is_null($constructor)) {
            return new $concrete;
        }

        $dependencies = $constructor->getParameters();
        $resolvedParams = [];

        foreach ($dependencies as $dependency) {
            $resolvedParams[] = $this->resolve($dependency->getType()->getName());
        }

        return $reflector->newInstanceArgs(array_merge($resolvedParams, $params));
    }
}
