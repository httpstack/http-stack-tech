<?php
namespace Httpstack\App\Interfaces;
interface ContainerInterface {
    public function bind(string $abstract, $concrete);
    public function make(string $abstract, array $params = []);
    public function singleton(string $abstract, $concrete);
    public function resolve(string $abstract);

    public function addProperty(string $name, $value);
    public function removeProperty(string $name);
    public function getProperty(string $name);
    public function hasProperty(string $name);
}