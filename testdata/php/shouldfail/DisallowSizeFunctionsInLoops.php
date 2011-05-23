<?php
/**
 * @package testdata.shouldfail
 */
class DisallowSizeFunctionsInLoops {
    /**
     * no comment
     */
    public function stuff() {
        $a = array('a', 'b', 'c');
        for($i = 0; $i < count($a); $i++) {
            echo $a;
        }
    }   
}