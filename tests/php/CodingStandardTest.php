<?php

class CodingStandardTest extends PHPUnit_Framework_TestCase {

    public function testBadIndentationSniffed() {
        $this->assertSniffFails( 'BadIndentation' );
    }
    
    public function testGlobalKeywordSniffed() {
        $this->assertSniffFails( 'GlobalKeyword' );
    }
    
    public function testNoPackageAnnotationSniffed() {
        $this->assertSniffFails( 'NoPackageAnnotation' );
    }

    public function testConstantCaseSniffed() {
        $this->assertSniffFails( 'ConstantCase' );
    }

    public function testLongMethodSniffed() {
        $this->assertSniffFails( 'LongMethod' );
    }
    
    public function testNonExecutableSniffed() {
        $this->assertSniffFails( 'NonExecutable' );
    }
    
    public function testConstructorNameSniffed() {
        $this->assertSniffFails( 'ConstructorName' );
    }
    
    public function testMemberVarScopeSniffed() {
        $this->assertSniffFails( 'MemberVarScope' );
    }
    
    public function testStaticThisUsageSniffed() {
        $this->assertSniffFails( 'StaticThisUsage' );
    }
    
    public function testDisallowsSizeFunctionsInLoopsSniffed() {
        $this->assertSniffFails( 'DisallowSizeFunctionsInLoops' );
    }
    
    public function testMethodScopeSniffed() {
        $this->assertSniffFails( 'MethodScope' );
    }
    
    public function testUnusedParamSniffed() {
        $this->assertSniffFails( 'UnusedParam' );
    }
    
    public function testDisallowTabIndentSniffed() {
        $this->assertSniffFails( 'DisallowTabIndent' );
    }
    
    public function testMissingDocSniffed() {
        $this->assertSniffFails( 'MissingDoc' );
    }
    
    public function testValidClassNameSniffed() {
        $this->assertSniffFails( 'ValidClassName' );
    }
    
    public function testEmptyStatementSniffed() {
        $this->assertSniffFails( 'EmptyStatement' );
    }
    
    public function testMissingParamDocSniffed() {
        $this->assertSniffFails( 'MissingParamDoc' );
    }
    
    public function testValidDefaultValuesSniffed() {
        $this->assertSniffFails( 'ValidDefaultValues' );
    }
    
    public function testFunctionCommentThrowTagSniffed() {
        $this->assertSniffFails( 'FunctionCommentThrowTag' );
    }
    
    public function testNoPPrefixSniffed() {
        $this->assertSniffFails( 'NoPPrefix' );
    }
    
    public function testWrongParamDocSniffed() {
        $this->assertSniffFails( 'WrongParamDoc' );
    }
        
    public function testFileWithCorrectCodingStandardsDoesNotReportAnyErrors() {
        $this->assertSniffPasses( 'ShouldPass' );
    }

    ////////////////////////////////////////////////////////////////////////////
    
    /**
     * Runs a sniff and return the output of phpcs and the return code
     * 
     * @param string $type Either a 'shouldpass' or a 'shouldfail'
     * @param string $name The name of the test data file
     * 
     * @return array
     */
    private function runSniff( $type, $name ) {
        
        $path = sprintf( 'tests/data/php/%s/%s.php', $type, $name );
        $command = sprintf( 'phpcs %s --standard=PHP/BoxUK', $path );
        
        exec( $command, $output, $returnCode );

        return array( $returnCode, $output );

    }
    
    /**
     * Assert a named test data file should fail
     * 
     * @param string $name The name of the test data file
     */
    protected function assertSniffFails( $name ) {
        
        list( $returnCode, $output ) = $this->runSniff( 'shouldfail', $name );
        
        if ( !$returnCode ) {
            $this->fail(sprintf( "Expected '%s' to fail, but it passed:\n\n%s", $name, implode($output) ));
        }
        
    }
    
    /**
     * Assert a named test data file should pass
     * 
     * @param string $name The name of the test data file
     */
    protected function assertSniffPasses( $name ) {
        
        list( $returnCode, $output ) = $this->runSniff( 'shouldpass', $name );
       
        if ( $returnCode ) {
            $this->fail(sprintf( "Expected '%s' to pass, but it failed:\n\n%s", $name, implode($output,"\n") ));
        }
        
    }

}
