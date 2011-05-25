<?php
/**
 * @package testdata.shouldfail
 * 
 * @todo Sniff doesn't seem to work..?
 */
class EmptyStatement {
    /**
     * no dox
     */
    public function stuff() {
        if($foo) {}
    }
}