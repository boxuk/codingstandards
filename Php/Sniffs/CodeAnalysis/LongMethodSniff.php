<?php
/**
 * @package BoxUK
 */
class Php_Sniffs_CodeAnalysis_LongMethodSniff implements PHP_CodeSniffer_Sniff {
    
    private $methodLengthLimit = 50;

    /**
     * Register the type if tokens we are sniffing for
     * 
     * @return array
     */
    public function register() {
        return array(T_FUNCTION);
    }

    /**
     * Function sniffed, check for long methods
     * 
     * @param PHP_CodeSniffer_File $phpcsFile The file being sniffed
     * @param type $stackPtr Current stack pointer
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr) {
        $tokens = $phpcsFile->getTokens();

        if (isset($tokens[$stackPtr]['scope_opener']) === false) {
            return;
        }

        $openingBrace = $tokens[$stackPtr]['scope_opener'];
        $closingBrace = $tokens[$stackPtr]['scope_closer'];

        $openBraceLine = $tokens[$openingBrace]['line'];
        $closeBraceLine = $tokens[$closingBrace]['line'];

        $lineDifference = ($closeBraceLine - $openBraceLine);

        if ($lineDifference > $this->methodLengthLimit) {
            $class = $this->getClass($phpcsFile, $stackPtr, $tokens);
            $method = $this->getMethod($phpcsFile, $stackPtr, $tokens);

            $error = "$class::$method() exceeds allowed maximum length of  " . $this->methodLengthLimit . " lines ($lineDifference lines)";
            $phpcsFile->addError($error, $stackPtr);

        }
    }

    /**
     * Gets the class the method sniffed is in
     * 
     * @param PHP_CodeSniffer_File $phpcsFile File being sniffed
     * @param type $stackPtr Current stack pointer
     * @param type $tokens All tokens
     * 
     * @return string
     */
    private function getClass(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $tokens) {
        $stack = $phpcsFile->findNext(array(T_CLASS), 0);
        while ($stack !== false) {
            $nameToken = $phpcsFile->findNext(T_STRING, $stack);
            $name = $tokens[$nameToken]['content'];
            return $name;
        }
    }

    /**
     * Gets the name of the method we're sniffing
     * 
     * @param PHP_CodeSniffer_File $phpcsFile File being sniffed
     * @param type $stackPtr Current stack pointer
     * @param type $tokens All tokens
     * 
     * @return string
     */
    private function getMethod(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $tokens) {
        $nameToken = $phpcsFile->findNext(T_STRING, $stackPtr);
        $name = $tokens[$nameToken]['content'];
        return $name;
    }

}
