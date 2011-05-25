<?php
/**
 * @package testdata.shouldfail
 */
class WrongParamDoc {
    /**
     * @param mixed $foo This isn't the right parameter name!
     */
    public function foo($bar) {
        echo $bar;
    }
}