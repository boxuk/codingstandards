<?php
/**
 * Checks that classes have a package annotation
 *
 * @package Amaxus
 */
class Php_Sniffs_Commenting_PackageAnnotationSniff implements PHP_CodeSniffer_Sniff {

    public function register() {

        return array( T_CLASS );

    }

    public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {
        
        $tokens = $phpcsFile->getTokens();
        $index = $this->getDocStart( $tokens );

        if ( $index == false ) {
            $phpcsFile->addError( 'Missing PHPDoc for class', $stackPtr );
        }
        
        else {
            $this->sniffForPackage( $phpcsFile, $tokens, $index, $stackPtr );
        }

    }

    private function sniffForPackage( PHP_CodeSniffer_File $phpcsFile, array $tokens, $index, $stackPtr ) {

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

    private function getDocStart( array $tokens ) {

        foreach ( $tokens as $index => $token ) {
            if ( $token['type'] == 'T_DOC_COMMENT' ) {
                return $index;
            }
        }

        return false;

    }

}
