<?php
/**
 * @package testdata.shouldfail
 * 
 * @todo This sniff doesn't seem to work
 */
class NonExecutableCode {
    /**
     * no comment, no cy
     */
    public function stuff() {
        return 'foo';
        
        $baz = 'bip';
        return $baz;
    }
}