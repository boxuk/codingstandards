<?php
/**
 * @package testdata.shouldfail
 */
class MethodScope {
    /**
     * This method has no scope (public|private|protected)
     * @return string 
     */
    function hasNoScope() {
        return "oh dear";
    }
}