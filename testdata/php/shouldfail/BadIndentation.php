<?php
/**
 * @package testdata.shouldfail
 */
class BadIndentation {
    /**
     * no dox
     */
    public function stuff() {
$foo = 'bar';
  $foo = 'bar';
  if(true) {
                        $baz = 'bip';  
  }
                        
    }
}