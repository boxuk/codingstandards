<?php
/**
 * @package testdata.shouldfail
 */
class GlobalKeyword {
    /**
     * no comment, no cry
     */
    public function stuff() {
        global $boom;
    }
}