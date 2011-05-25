<?php
/**
 * Sniff to prevent p_ prefixes to parameters
 * 
 * @package BoxUK
 */
class PHP_Sniffs_CodeAnalysis_PPrefixSniff implements PHP_CodeSniffer_Sniff {
    
    /**
     * Registers the type of tokens we're sniffing for
     * 
     * @return array
     */
    public function register() {
        return array(T_FUNCTION);
    }

    /**
     * Sniff for params with p_ prefix
     * 
     * @param PHP_CodeSniffer_File $phpcsFile File being sniffed
     * @param type $stackPtr Current stack pointer
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr) {
        $tokens = $phpcsFile->getTokens();

        $argStart = $tokens[$stackPtr]['parenthesis_opener'];
        $argEnd   = $tokens[$stackPtr]['parenthesis_closer'];

        // Flag for when we have found a default in our arg list.
        // If there is a value without a default after this, it is an error.
        $defaultFound = false;

        $nextArg = $argStart;
        while (($nextArg = $phpcsFile->findNext(T_VARIABLE, ($nextArg + 1), $argEnd)) !== false) {
            
            if(self::argIsPrefixedWithAP($phpcsFile, $nextArg)) {
                
                return;
            }
        }
    }
    
    /**
     * Indicates if the argument has a p_ prefix
     * 
     * @param PHP_CodeSniffer_File $phpcsFile The file being sniffed
     * @param type $argPtr Stack pointer to argument tokens
     * 
     * @return bool
     */
    private static function argIsPrefixedWithAP(PHP_CodeSniffer_File $phpcsFile, $argPtr) {
        $tokens    = $phpcsFile->getTokens();
        $token = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, ($argPtr), null, true);
        $parameterName = $tokens[$token]['content'];
        
        if (strpos($parameterName, '$p_') === 0) {
            $error  = "Arguments must not be prefixed with \$p_ (found '$parameterName')";
            $phpcsFile->addError($error, $argPtr, 'InvalidPrefix');
            return true;
        } else {
            return false;
        }
    }

}
