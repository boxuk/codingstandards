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
}