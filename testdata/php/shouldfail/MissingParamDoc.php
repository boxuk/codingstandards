<?php
/**
 * @package testdata.shouldfail
 */
class MissingParamDoc {
    /**
     * @param type $bar
     */
    public function stuff($foo, $bar) {
        echo $foo;
        echo $bar;
    }
}