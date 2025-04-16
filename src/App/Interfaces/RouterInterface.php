<?php 
namespace Httpstack\App\Interfaces;

interface RouterInterface{
    public function get(array $arrPath, callable|array $mixHandler) : void;
    public function post(array $arrPath, callable|array $mixHandler) : void;
    public function delete(array $arrPath, callable|array $mixHandler) : void;
    public function put(array $arrPath, callable|array $mixHandler) : void;
    public function before(array $arrPath, callable|array $mixMidHandler) : void;
    public function authenticate(array $arrPath, callable|array $mixAuthHandler) : void;
    public function run(Response $objRes, Request $objReq) : void;
    public function add(string $strMethod, string $strPath, callable|array $mixHandler) :void;
}