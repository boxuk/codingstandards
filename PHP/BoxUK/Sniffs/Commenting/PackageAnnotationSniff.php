<?php
/**
 * Checks that classes have a package annotation
 *
 * @package Amaxus
 */
class BoxUK_Sniffs_Commenting_PackageAnnotationSniff implements PHP_CodeSniffer_Sniff {

    /**
     * Registers tokens we're sniffing for
     * 
     * @return array
     */
    public function register() {

        return array( T_CLASS );

    }

    /**
     * Sniffs for @package annotations
     * 
     * @param PHP_CodeSniffer_File $phpcsFile Current file being sniffed
     * @param type $stackPtr Current stack pointer
     */
    public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {
        
        $tokens = $phpcsFile->getTokens();
        $index = $this->getDocStart( $tokens );

        if ( $index == false ) {
            $phpcsFile->addError( 'Missing PHPDoc for class', $stackPtr );
        }
        
        else {
            if ( !$this->classIsNamespaced($tokens) ) {
                $this->sniffForPackage( $phpcsFile, $tokens, $index, $stackPtr );
            }
        }

    }
    
    /**
     * Indicates if the file we're checking is namespaced
     * 
     * @param array $tokens The tokens that make up the file
     * 
     * @return bool
     */
    protected function classIsNamespaced( $tokens ) {
        
        foreach ( $tokens as $token => $value ) {
            
            if ( $value['type'] == 'T_NAMESPACE' ) {
                return true;
            }
            
            // try and fail fast, if we hit much else apart from the namespace
            // then there can't be a namespace
            if ( $value['type'] == 'T_STRING' ) {
                return false;
            }
            
        }
        
        return false;
        
    }

    /**
     * @param PHP_CodeSniffer_File $phpcsFile File we're sniffing
     * @param array $tokens All tokens
     * @param type $index Token index
     * @param type $stackPtr Current stack pointer
     */
    protected function sniffForPackage( PHP_CodeSniffer_File $phpcsFile, array $tokens, $index, $stackPtr ) {

        while ( true && isset($tokens[$index]) ) {
            if ( strstr($tokens[$index]['content'],'@package') !== false ) {
                return;
            }
            if ( $tokens[++$index]['type'] != 'T_DOC_COMMENT' ) {
                break;
            }
        }

        $phpcsFile->addError( 'Missing @package annotation for class', $stackPtr );

    }

    /**
     * Get the index of the docblock at the start of the file
     * 
     * @param array $tokens All tokens
     * 
     * @return int
     */
    private function getDocStart( array $tokens ) {

        foreach ( $tokens as $index => $token ) {
            if ( $token['type'] == 'T_DOC_COMMENT' ) {
                return $index;
            }
        }

        return false;

    }

}
