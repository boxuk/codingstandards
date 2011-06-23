<?php
/**
 * @package testdata.shouldpass
 */
namespace Foo\Bar;

class FooBarException extends \Exception {
    
}

class NamespacedException {
    /**
     * @throws \Exception
     */
    public function foo() {
        throw new \Exception();
    }
    
    /**
     * @throws FooBarException
     */
    public function fooBar() {
        throw new FooBarException();
    }
    
    /**
     * @throws \Foo\Bar\FooBarException
     */
    public function fooBar2() {
        throw new \Foo\Bar\FooBarException();
    }
    
    
}