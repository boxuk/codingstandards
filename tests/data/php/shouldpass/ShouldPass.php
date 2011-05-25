<?php
/**
 * Some sample class that should pass our sniffs
 * @package testdata.shouldpass
 */
class ShouldPass {
    
    private $foo; // no documentation is necessary, although it is encouraged.
                  // hopefully our var names will be clear enough!
    
    /**
     * @var String bar But if we DO document, we get it right
     */
    public $bar;
    
    private $baz = 'baz';
    
    /**
     * @return string baz
     */
    private function bazzer() {
        echo $GLOBALS['boom'];
        return $this->baz;
    }
    
    /**
     * see DisallowSizeFunctionsInLoops
     */
    private function saneLoop() {
        $a = array('a', 'b', 'c');
        $sizeA = count($a);
        for($i = 0; $i < $sizeA; $i++) {
            echo $a;
        }
    }
    
    /**
     * @return mixed $foo
     */
    public function getFoo() {
        // should not need any docs, is just a getter
        return $foo;
    }
    
    // comment is optional
    public function validDefaultValues($foo, $bar = 'baz') {
        $bar = 1; // required use
        return $foo;
    }
    
    /**
     * @param string $foo Some info
     * @param string $bar Other
     */
    public function fooBar($foo, $bar) {
        echo "$foo, $bar";
    }   
    
    // make sure excluded regions work
    //@codingStandardsIgnoreStart
    function horrorOfHorrors() {
if(true)        {
	// <== tabs be here!!
    	// <== tabs be here!!
    	// <== tabs be here!!i
    if(true) {
        if(true) {
            if(true) {
            if(true) {
                if(true) {
            if(true) {if(true) {
            if(true) {if(true) {
            if(true) {if(true) {
            if(true) {if(true) {
            if(true) {
                
    	// <== tabs be here!!i the horror!!! the HORROR!!!
                }
            }
            }}
            }}
            }}
            }}
            }    
            }
            }
        }
    }
    }
    //@codingStandardsIgnoreEnd
    
    /**
     * @throws Exception
     */
    public function throwAnException() {
        $x = true;
        if ($x) {
            throw new Exception();
        }
    }
    

}