<?php
/**
 * Sniff to detect singleton access (calls to getInstance() or setInstance())
 * 
 * @package BoxUK
 */
class PHP_Sniffs_CodeAnalysis_SingletonAccessSniff implements PHP_CodeSniffer_Sniff {

    private static $aMethods = array( 'getInstance', 'setInstance' );

    /**
     * Registers the types of tokens we're sniffing for
     * 
     * @return array
     */
    public function register() {

        return array( T_FUNCTION );
        
    }

    /**
     * Sniff for singleton calls
     * 
     * @param PHP_CodeSniffer_File $phpcsFile File we're sniffing
     * @param type $stackPtr Current stack pointer
     */
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

    /**
     * Indicates if the :: access is calling a singleton method
     * 
     * @param array $tokens All tokens
     * @param type $i Current position in tokens
     * 
     * @return bool
     */
    private function isSingletonAccess( array $tokens, $i ) {

        return $tokens[$i][ 'type' ] == 'T_DOUBLE_COLON'
               &&
               in_array( $tokens[$i+1]['content'], self::$aMethods );

    }

}
