<?php
/**
 * @package testdata.shouldfail
 */
class FunctionCommentThrowTag {
    /**
     * @throws Exceptionx
     */
    public function foo() {
        if (true) {
            throw new Exception();
        }
    }
    
    /**
     * no comment
     */
    public function foo() {
        if (true) {
            throw new Exception();
        }
    }
    
    
    /**
     * Incorrect exception type
     * 
     * @throws RandomException
     */
    public function fooBar() {
        throw new FooBarException();
    }
    
    /**
     * Incorrect exception type; namespaced
     * @throws \Foo\Bar\RandomException
     */
    public function fooBar2() {
        throw new \Foo\Bar\FooBarException();
    }
    
    
}