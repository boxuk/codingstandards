<?php
/**
 * @package Amaxus
 */
class Php_Sniffs_CodeAnalysis_LongMethodSniff implements PHP_CodeSniffer_Sniff {
    
    private $methodLengthLimit = 50;

    public function register() {
        return array(T_FUNCTION);
    }

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

    private function getClass(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $tokens) {
        $stack = $phpcsFile->findNext(array(T_CLASS), 0);
        while ($stack !== false) {
            $nameToken = $phpcsFile->findNext(T_STRING, $stack);
            $name = $tokens[$nameToken]['content'];
            return $name;
        }
    }

    private function getMethod(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $tokens) {
        $nameToken = $phpcsFile->findNext(T_STRING, $stackPtr);
        $name = $tokens[$nameToken]['content'];
        return $name;
    }


}
