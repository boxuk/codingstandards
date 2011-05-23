<?php
/**
 * @package testdata.shouldfail
 * 
 * @todo PLEASE NOTE: this sniff does not seem to work
 */
class UnusedParam {
    /**
     * @param type $foo no comment
     */
    public function stuff($foo) {
        echo "x";
        $bar = "baz";
        return;
    }
}