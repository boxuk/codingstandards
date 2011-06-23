<?php
/**
 * @package testdata.shouldpass
 */
class NamespacedException {
    /**
     * @throws \Exception
     */
    public function foo() {
        throw new \Exception();
    }
}