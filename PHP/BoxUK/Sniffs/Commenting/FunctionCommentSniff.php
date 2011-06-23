<?php
/**
 * Originally from Squizz standard, customized for Box UK.
 *
 * Parses and verifies the doc comments for functions.
 *
 * PHP version 5
 *
 * @package BoxUK
 */
class BoxUK_Sniffs_Commenting_FunctionCommentSniff implements PHP_CodeSniffer_Sniff {

    /**
     * The name of the method that we are currently processing.
     *
     * @var string
     */
    private $_methodName = '';

    /**
     * The position in the stack where the fucntion token was found.
     *
     * @var int
     */
    private $_functionToken = null;

    /**
     * The position in the stack where the class token was found.
     *
     * @var int
     */
    private $_classToken = null;

    /**
     * The function comment parser for the current method.
     *
     * @var PHP_CodeSniffer_Comment_Parser_FunctionCommentParser
     */
    protected $commentParser = null;

    /**
     * The current PHP_CodeSniffer_File object we are processing.
     *
     * @var PHP_CodeSniffer_File
     */
    protected $currentFile = null;


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register() {
        
        return array( T_FUNCTION );

    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $find = array( T_COMMENT, T_DOC_COMMENT, T_CLASS, T_FUNCTION, T_OPEN_TAG );

        $commentEnd = $phpcsFile->findPrevious($find, ($stackPtr - 1));

        if ( $commentEnd === false ) { return; }

        $this->currentFile = $phpcsFile;
        $tokens            = $phpcsFile->getTokens();
        $code = $tokens[$commentEnd]['code'];

        if ( $this->isComment($phpcsFile,$code,$tokens,$stackPtr)
                || $this->codeBetweenFuncAndDoc($phpcsFile,$stackPtr,$commentEnd) ) {
            return;
        }

        $this->_functionToken = $stackPtr;
        $this->_classToken = null;
        
        $this->findClassToken( $tokens, $stackPtr );
        
        // If the first T_OPEN_TAG is right before the comment, it is probably
        // a file comment.
        $commentStart = ($phpcsFile->findPrevious(T_DOC_COMMENT, ($commentEnd - 1), null, true) + 1);
        $prevToken    = $phpcsFile->findPrevious(T_WHITESPACE, ($commentStart - 1), null, true);

        $comment           = $phpcsFile->getTokensAsString($commentStart, ($commentEnd - $commentStart + 1));
        $this->_methodName = $phpcsFile->getDeclarationName($stackPtr);

        try {
            $this->commentParser = new PHP_CodeSniffer_CommentParser_FunctionCommentParser($comment, $phpcsFile);
            $this->commentParser->parse();
        } catch (PHP_CodeSniffer_CommentParser_ParserException $e) {
            $line = ($e->getLineWithinComment() + $commentStart);
            $phpcsFile->addError($e->getMessage(), $line);
            return;
        }

        $comment = $this->commentParser->getComment();
        if (is_null($comment) === true) {
            $error = 'Function doc comment is empty';
            $phpcsFile->addError($error, $commentStart);
            return;
        }

        $this->processParams($commentStart);
        $this->processReturn($commentStart, $commentEnd);
        $this->processThrows($commentStart);

    }
    
    /**
     * Sets the _classToken to the token for the class definition in this file
     * 
     * @param type $tokens All tokens
     * @param type $stackPtr Current stack pointer
     */
    protected function findClassToken( $tokens, $stackPtr ) {
        
        foreach ($tokens[$stackPtr]['conditions'] as $condPtr => $condition) {
            if ($condition === T_CLASS || $condition === T_INTERFACE) {
                $this->_classToken = $condPtr;
                break;
            }
        }

    }

    /**
     * Indicates if the code is a comment.
     * 
     * @param PHP_CodeSniffer_File $phpcsFile The file we're sniffing
     * @param type $code Token to check
     * @param type $tokens All tokens
     * @param type $stackPtr Current stack pointer
     * 
     * @return bool
     */
    protected function isComment( PHP_CodeSniffer_File $phpcsFile, $code, $tokens, $stackPtr ) {
        
        if ($code === T_COMMENT) {
            return true;
        }
        
        else if ($code !== T_DOC_COMMENT) {
            // try and check this is not an anonymous function
            if ( !in_array('T_OPEN_PARENTHESIS',array($tokens[$stackPtr+1]['type'],$tokens[$stackPtr+2]['type'])) ) {
                $phpcsFile->addError('Missing function doc comment', $stackPtr);
            }
            return true;
        }
        
        return false;

    }

    /**
     * Checks if there's any code between the function and the next docblock
     * 
     * @param PHP_CodeSniffer_File $phpcsFile File we're sniffing
     * @param int $stackPtr Current stack pointer
     * @param int $commentEnd Token where comment ends
     * 
     * @return bool
     */
    protected function codeBetweenFuncAndDoc( PHP_CodeSniffer_File $phpcsFile, $stackPtr, $commentEnd ) {

        // If there is any code between the function keyword and the doc block
        // then the doc block is not for us.
        $ignore    = PHP_CodeSniffer_Tokens::$scopeModifiers;
        $ignore[]  = T_STATIC;
        $ignore[]  = T_WHITESPACE;
        $ignore[]  = T_ABSTRACT;
        $ignore[]  = T_FINAL;
        $prevToken = $phpcsFile->findPrevious($ignore, ($stackPtr - 1), null, true);
        if ($prevToken !== $commentEnd) {
            $phpcsFile->addError('Missing function doc comment', $stackPtr);
            return true;
        }
        
        return false;

    }

    /**
     * Process any throw tags that this function comment has.
     *
     * @param int $commentStart The position in the stack where the
     *                          comment started.
     *
     * @return void
     */
    protected function processThrows($commentStart)
    {
        if (count($this->commentParser->getThrows()) === 0) {
            return;
        }

        foreach ($this->commentParser->getThrows() as $throw) {

            $exception = $throw->getValue();
            $errorPos  = ($commentStart + $throw->getLine());

            if ($exception === '') {
                $error = '@throws tag must contain the exception class name';
                $this->currentFile->addError($error, $errorPos);
            }
        }

    }//end processThrows()


    /**
     * Process the return comment of this function comment.
     *
     * @param int $commentStart The position in the stack where the comment started.
     * @param int $commentEnd   The position in the stack where the comment ended.
     *
     * @return void
     */
    protected function processReturn($commentStart, $commentEnd)
    {
        // Skip constructor and destructor.
        $className = '';
        if ($this->_classToken !== null) {
            $className = $this->currentFile->getDeclarationName($this->_classToken);
            $className = strtolower(ltrim($className, '_'));
        }

        $methodName      = strtolower(ltrim($this->_methodName, '_'));
        $isSpecialMethod = ($this->_methodName === '__construct' || $this->_methodName === '__destruct');

        if ($isSpecialMethod === false && $methodName !== $className) {
            // Report missing return tag.
            if ($this->commentParser->getReturn() === null) {
                /*
                $error = 'Missing @return tag in function comment';
                $this->currentFile->addError($error, $commentEnd);
                 *  GD
                 */
            } else if (trim($this->commentParser->getReturn()->getRawContent()) === '') {
                $error    = '@return tag is empty in function comment';
                $errorPos = ($commentStart + $this->commentParser->getReturn()->getLine());
                $this->currentFile->addError($error, $errorPos);
            }
        }

    }//end processReturn()


    /**
     * Process the function parameter comments.
     *
     * @param int $commentStart The position in the stack where
     *                          the comment started.
     *
     * @return void
     */
    protected function processParams($commentStart) {
        
        $realParams = $this->currentFile->getMethodParameters($this->_functionToken);
        $params      = $this->commentParser->getParams();
        $foundParams = array();

        if (empty($params) === false) {

            $lastParm = (count($params) - 1);
            $previousParam      = null;
            $spaceBeforeVar     = 10000;
            $spaceBeforeComment = 10000;
            $longestType        = 0;
            $longestVar         = 0;

            foreach ($params as $param) {

                $paramComment = trim($param->getComment());
                $errorPos     = ($param->getLine() + $commentStart);
                $spaceCount = substr_count($param->getWhitespaceBeforeVarName(), ' ');
                
                if ($spaceCount < $spaceBeforeVar) {
                    $spaceBeforeVar = $spaceCount;
                    $longestType    = $errorPos;
                }

                $spaceCount = substr_count($param->getWhitespaceBeforeComment(), ' ');

                if ($spaceCount < $spaceBeforeComment && $paramComment !== '') {
                    $spaceBeforeComment = $spaceCount;
                    $longestVar         = $errorPos;
                }

                if ($previousParam !== null) {
                    $previousName = ($previousParam->getVarName() !== '') ? $previousParam->getVarName() : 'UNKNOWN';
                }
                
                $foundParams = $this->checkParam( $realParams, $foundParams, $param, $paramComment, $errorPos );

                $previousParam = $param;

            }

        }

    }
    
    /**
     * Checks the parameter for cniff errors, adding the parameter to the foundParams
     * array and returning that.
     * 
     * @return array
     */
    protected function checkParam( array $realParams, array $foundParams, $param, $paramComment, $errorPos ) {
        
        $pos = $param->getPosition();
        $paramName = ($param->getVarName() !== '') ? $param->getVarName() : '[ UNKNOWN ]';

        // Make sure the names of the parameter comment matches the
        // actual parameter.
        if (isset($realParams[($pos - 1)]) === true) {
            $realName      = $realParams[($pos - 1)]['name'];
            $foundParams[] = $realName;
            // Append ampersand to name if passing by reference.
            if ($realParams[($pos - 1)]['pass_by_reference'] === true) {
                $realName = '&'.$realName;
            }

            if ($realName !== $paramName) {
                $error  = 'Doc comment for var '.$paramName;
                $error .= ' does not match ';
                if (strtolower($paramName) === strtolower($realName)) {
                    $error .= 'case of ';
                }

                $error .= 'actual variable name '.$realName;
                $error .= ' at position '.$pos;

                $this->currentFile->addError($error, $errorPos);
            }
        } else {
            // We must have an extra parameter comment.
            $error = 'Superfluous doc comment at position '.$pos;
            $this->currentFile->addError($error, $errorPos);
        }

        if ($param->getVarName() === '') {
            $error = 'Missing parameter name at position '.$pos;
             $this->currentFile->addError($error, $errorPos);
        }

        if ($param->getType() === '') {
            $error = 'Missing type at position '.$pos;
            $this->currentFile->addError($error, $errorPos);
        }

        if ($paramComment === '' && !$this->isScalarParam($param)) {
            $error = 'Missing comment for param "'.$paramName.'" at position '.$pos;
            $this->currentFile->addError($error, $errorPos);
        }
        
        return $foundParams;

    }
    
    /**
     * Indicates if the paramater type is a scalar
     * 
     * @param object $param Parameter to check
     * 
     * @return bool
     */
    protected function isScalarParam( $param ) {
        
        $scalarTypes = array( 'integer', 'float', 'string', 'bool' );
        
        return in_array(
            $param->getType(),
            $scalarTypes
        );
        
    }

}
