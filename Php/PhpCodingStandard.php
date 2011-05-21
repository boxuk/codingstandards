<?php
/**
 * Box UK PHP Coding Standard.
 *
 * @deprecated
 *
 * This file has been superseded by the ruleset.xml file in newer versions
 * of PHP_CodeSniffer, it should be removed when everyone is upgraded.
 * 
 */

if (class_exists('PHP_CodeSniffer_Standards_CodingStandard', true) === false) {
    throw new PHP_CodeSniffer_Exception('Class PHP_CodeSniffer_Standards_CodingStandard not found');
}

class PHP_CodeSniffer_Standards_Php_PhpCodingStandard extends PHP_CodeSniffer_Standards_CodingStandard {

    /**
     * Return a list of external sniffs to include with this standard.
     *
     * The PEAR standard uses some generic sniffs.
     *
     * @return array
     */
    public function getIncludedSniffs() {

        return array(
        
            'Generic/Sniffs/WhiteSpace/DisallowTabIndentSniff.php', 
            'Generic/Sniffs/CodeAnalysis/EmptyStatementSniff.php',
            'Generic/Sniffs/CodeAnalysis/JumbledIncrementerSniff.php',
            'Generic/Sniffs/CodeAnalysis/UnconditionalIfStatementSniff.php',
            'Generic/Sniffs/CodeAnalysis/UnusedFunctionParameterSniff.php',
            'Generic/Sniffs/CodeAnalysis/UselessOverridingMethodSniff.php',

            'Generic/Sniffs/Formatting/DisallowMultipleStatementsSniff.php',

            'Generic/Sniffs/Metrics/CyclomaticComplexitySniff.php',
            'Generic/Sniffs/Metrics/NestingLevelSniff.php',

            'Generic/Sniffs/PHP/ForbiddenFunctionsSniff.php'

        );

    }

}
