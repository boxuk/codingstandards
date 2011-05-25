<?php
/**
 * @package testdata.shouldfail
 */
class ValidDefaultValues {
    
    /**
     * If a param has defaults, it should be at the end
     */
    public function foo($baz = 'FOO', $bip) {
        return $baz;
    }
}