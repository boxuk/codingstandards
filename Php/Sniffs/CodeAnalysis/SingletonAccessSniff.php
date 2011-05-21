<?php
/**
 * Sniff to detect singleton access (calls to getInstance() or setInstance())
 * 
 * @package Amaxus
 */
class Php_Sniffs_CodeAnalysis_SingletonAccessSniff implements PHP_CodeSniffer_Sniff {

    private static $aMethods = array( 'getInstance', 'setInstance' );

    public function register() {

        return array( T_FUNCTION );
        
    }

    public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {

        $tokens = $phpcsFile->getTokens();

        if (isset($tokens[$stackPtr]['scope_opener']) === false) {
            return;
        }

        $openingBrace = $tokens[$stackPtr]['scope_opener'];
        $closingBrace = $tokens[$stackPtr]['scope_closer'];

        for ( $i=$openingBrace; $i<$closingBrace; $i++ ) {

            if ( $this->isSingletonAccess($tokens,$i) ) {
                $error = sprintf(
                    'Singleton access (%s::%s)',
                    $tokens[ $i-1 ][ 'content' ],
                    $tokens[ $i+1 ][ 'content' ]
                );
                $phpcsFile->addWarning( $error, $stackPtr );
            }

        }

    }

    private function isSingletonAccess( array $tokens, $i ) {

        return $tokens[$i][ 'type' ] == 'T_DOUBLE_COLON'
               &&
               in_array( $tokens[$i+1]['content'], self::$aMethods );

    }

}
