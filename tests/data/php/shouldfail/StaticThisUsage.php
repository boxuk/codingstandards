<?php
/**
 * @package testdata.shouldfail
 */
class StaticThisUsage {
    
    /**
     * This tries to access a member method, which will throw an error in the
     * sniff and in the software
     */
    private static function bar() {
        $this->stuff();
    }
    
    /**
     * Some member method
     */
    private function stuff() {
        return "xx";
    }
}