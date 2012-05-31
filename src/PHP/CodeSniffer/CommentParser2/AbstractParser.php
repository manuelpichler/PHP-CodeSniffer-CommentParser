<?php
/**
 * PHP_CodeSniffer_CommentParser2_AbstractParser.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer_Standards_EZC
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @author    Manuel Pichler <mapi@manuel-pichler.de>
 * @copyright 2007-2008 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version   SVN: $Id$
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

if (class_exists('PHP_CodeSniffer_CommentParser_SingleElement', true) === false) {
    $error = 'Class PHP_CodeSniffer_CommentParser_SingleElement not found';
    throw new PHP_CodeSniffer_Exception($error);
}

if (class_exists('PHP_CodeSniffer_CommentParser_CommentElement', true) === false) {
    $error = 'Class PHP_CodeSniffer_CommentParser_CommentElement not found';
    throw new PHP_CodeSniffer_Exception($error);
}

if (class_exists('PHP_CodeSniffer_CommentParser_ParserException', true) === false) {
    $error = 'Class PHP_CodeSniffer_CommentParser_ParserException not found';
    throw new PHP_CodeSniffer_Exception($error);
}

/**
 * PHP_CodeSniffer_CommentParser2_AbstractParser.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer_Standards_EZC
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @author    Manuel Pichler <mapi@manuel-pichler.de>
 * @copyright 2008 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
abstract class PHP_CodeSniffer_CommentParser2_AbstractParser
{
    /**
     * An array of unknown tags.
     *
     * @var array(string)
     */
    public $unknown = array();

    /**
     * The order of tags.
     *
     * @var array(string)
     */
    public $orders = array();
    
    /**
     * The string content of the comment.
     *
     * @var string
     */
    protected $commentString = '';

    /**
     * The file that the comment exists in.
     *
     * @var PHP_CodeSniffer_File
     */
    protected $phpcsFile = null;

    /**
     * The word tokens that appear in the comment.
     *
     * Whitespace tokens also appear in this stack, but are separate tokens
     * from words.
     *
     * @var array(string)
     */
    protected $words = array();

    /**
     * The previous doc element that was processed.
     *
     * null if the current element being processed is the first element in the
     * doc comment.
     *
     * @var PHP_CodeSniffer_CommentParser_DocElement
     */
    protected $previousElement = null;

    /**
     * True if the comment has been parsed.
     *
     * @var boolean
     */
    private $_hasParsed = false;
    
    /**
     * The parser tag configuration.
     * 
     * This property holds the context specific tag configuration with tag name,
     * class and number of occurrence.
     *
     * @var array(string => array)
     */
    private $_config = null;
    
    /**
     * List of all allowed tags.
     *
     * @var array
     */
    private $_allowedTags = array();
    
    /**
     * List of all parsed comment doc elements.
     *
     * @var array(PHP_CodeSniffer_CommentParser_DocElement)
     */
    private $_elements = array();

    /**
     * Constructs a Doc Comment Parser.
     *
     * @param string               $comment   The comment to parse.
     * @param PHP_CodeSniffer_File $phpcsFile The file that this comment is in.
     */
    public function __construct($comment, PHP_CodeSniffer_File $phpcsFile)
    {
        $this->commentString = $comment;
        $this->phpcsFile     = $phpcsFile;
    }//end __construct()
    
    public function registerTag($tagName, array $tagConfig)
    {
        $this->getElementConfig();
        
        $this->_config[$tagName] = $tagConfig;
    }
    
    public function unregister($tagName)
    {
        $this->getElementConfig();
        
        unset($this->_config[$tagName]);
    }

    /**
     * Initiates the parsing of the doc comment.
     *
     * @return void
     * @throws PHP_CodeSniffer_CommentParser_ParserException If the parser finds a
     *                                                       problem with the
     *                                                       comment.
     */
    public function parse()
    {
        if ($this->_hasParsed === false) {
            
            $this->getElementConfig();
            
            $this->_elements    = array();
            $this->_allowedTags = array();
        
            foreach ($this->_config as $tagName => $config) {
            
                $name = strtolower($config['name']);
            
                // Prepare parsed tag container
                if ($config['single']) {
                    $this->_elements[$name] = null;    
                } else {
                    $this->_elements[$name] = array();
                }
                
                $this->_allowedTags[$tagName] = $config['single'];
            }
            $this->_parse($this->commentString);
        }

    }//end parse()

    /**
     * Returns the tag orders (index => tagName).
     *
     * @return array
     */
    public function getTagOrders()
    {
        return $this->orders;

    }//end getTagOrders()


    /**
     * Returns the unknown tags.
     *
     * @return array
     */
    public function getUnknown()
    {
        return $this->unknown;

    }//end getUnknown()
    
    public function __call( $method, $args )
    {
        if (strpos($method, 'get') !== 0) {
            throw new Exception("Unknown method '{$method}' called.");
        }
        
        $name = strtolower(substr($method, 3));
        
        if (array_key_exists($name, $this->_elements)) {
            return $this->_elements[$name];
        }
        throw new Exception("Unknown tag '{$name}' requested.");
    }

    /**
     * Parse the comment.
     *
     * @param string $comment The doc comment to parse.
     *
     * @return void
     * @see _parseWords()
     */
    private function _parse($comment)
    {
        // Firstly, remove the comment tags and any stars from the left side.
        $lines = split($this->phpcsFile->eolChar, $comment);
        foreach ($lines as &$line) {
            $line = trim($line);

            if ($line !== '') {
                if (substr($line, 0, 3) === '/**') {
                    $line = substr($line, 3);
                } else if (substr($line, -2, 2) === '*/') {
                    $line = substr($line, 0, -2);
                } else if ($line{0} === '*') {
                    $line = substr($line, 1);
                }

                // Add the words to the stack, preserving newlines. Other parsers
                // might be interested in the spaces between words, so tokenize
                // spaces as well as separate tokens.
                $flags       = (PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
                $words       = preg_split('|(\s+)|', $line.$this->phpcsFile->eolChar, -1, $flags);
                $this->words = array_merge($this->words, $words);
            }
        }//end foreach

        $this->_parseWords();

    }//end _parse()


    /**
     * Parses each word within the doc comment.
     *
     * @return void
     * @see _parse()
     * @throws PHP_CodeSniffer_CommentParser_ParserException If more than the allowed
     *                                                       number of occurances of
     *                                                       a tag is found.
     */
    private function _parseWords()
    {
        $allowedTags     = $this->getAllowedTags();
        $allowedTagNames = array_keys($allowedTags);
        $foundTags       = array();
        $prevTagPos      = false;
        $wordWasEmpty    = true;

        foreach ($this->words as $wordPos => $word) {

            if (trim($word) !== '') {
                $wordWasEmpty = false;
            }

            if ($word{0} === '@') {

                $tag = substr($word, 1);

                // Filter out @ tags in the comment description.
                // A real comment tag should have a space and a newline before it.
                if (isset($this->words[($wordPos - 1)]) === false || $this->words[($wordPos - 1)] !== ' ') {
                    continue;
                }

                if (isset($this->words[($wordPos - 2)]) === false || $this->words[($wordPos - 2)] !== $this->phpcsFile->eolChar) {
                    continue;
                }

                $foundTags[] = $tag;

                if ($prevTagPos !== false) {
                    // There was a tag before this so let's process it.
                    $prevTag = substr($this->words[$prevTagPos], 1);
                    $this->parseTag($prevTag, $prevTagPos, ($wordPos - 1));
                } else {
                    // There must have been a comment before this tag, so
                    // let's process that.
                    $this->parseTag('comment', 0, ($wordPos - 1));
                }

                $prevTagPos = $wordPos;

                if (in_array($tag, $allowedTagNames) === false) {
                    // This is not a tag that we process, but let's check to
                    // see if it is a tag we know about. If we don't know about it,
                    // we add it to a list of unknown tags.
                    $knownTags = array(
                                  'abstract',
                                  'access',
                                  'example',
                                  'filesource',
                                  'global',
                                  'ignore',
                                  'internal',
                                  'name',
                                  'static',
                                  'staticvar',
                                  'todo',
                                  'tutorial',
                                  'uses',
                                  'package_version@',
                                 );

                    if (in_array($tag, $knownTags) === false) {
                        $this->unknown[] = array(
                                            'tag'  => $tag,
                                            'line' => $this->getLine($wordPos),
                                           );
                    }

                }//end if

            }//end if
        }//end foreach

        // Only process this tag if there was something to process.
        if ($wordWasEmpty === false) {
            if ($prevTagPos === false) {
                // There must only be a comment in this doc comment.
                $this->parseTag('comment', 0, count($this->words));
            } else {
                // Process the last tag element.
                $prevTag = substr($this->words[$prevTagPos], 1);
                $this->parseTag($prevTag, $prevTagPos, count($this->words));
            }
        }

    }//end _parseWords()


    /**
     * Returns the line that the token exists on in the doc comment.
     *
     * @param int $tokenPos The position in the words stack to find the line
     *                      number for.
     *
     * @return int
     */
    protected function getLine($tokenPos)
    {
        $newlines = 0;
        for ($i = 0; $i < $tokenPos; $i++) {
            $newlines += substr_count($this->phpcsFile->eolChar, $this->words[$i]);
        }

        return $newlines;

    }//end getLine()

    /**
     * Returns a list of tags that this comment parser allows for it's comment.
     *
     * Each tag should indicate if only one entry of this tag can exist in the
     * comment by specifying true as the array value, or false if more than one
     * is allowed. Each tag should ommit the @ symbol. Only tags other than
     * the standard tags should be returned.
     *
     * @return array(string => boolean)
     */
    protected function getAllowedTags()
    {
        return $this->_allowedTags;
    }//end getAllowedTags()
    
    protected function createElement($tag, array $tokens)
    {
        $config = $this->getElementConfig();
        
        if (!isset($config[$tag]['class'])) {
            throw new Exception("Unknown tag '{$tag}' requested.");
        }
        
        $className = $config[$tag]['class'];
        
        $element = null;
        
        switch ($className) {
            case 'PHP_CodeSniffer_CommentParser_PairElement':
            case 'PHP_CodeSniffer_CommentParser_SingleElement':
            case 'PHP_CodeSniffer_CommentParser2_LeafElement':
            case 'PHP_CodeSniffer_CommentParser2_PropertyElement':
                $element = new $className(
                    $this->previousElement, 
                    $tokens, 
                    $tag,
                    $this->phpcsFile
                );
                break;
                
            case 'PHP_CodeSniffer_CommentParser_CommentElement':
            case 'PHP_CodeSniffer_CommentParser_ParameterElement':
                $element = new $className($this->previousElement, $tokens, $this->phpcsFile);
                break;
                
            default: 
                throw new Exception("Unknown class '{$className}'.");
        }
        
        $name = $config[$tag]['name'];
        
        if ($config[$tag]['single']) {
            $this->_elements[$name] = $element;
        } else {
            $this->_elements[$name][] = $element;
        }
        
        return $element;
    }
    
    /**
     * Parses the specified tag.
     *
     * @param string $tag   The tag name to parse (omitting the @ sybmol from
     *                      the tag)
     * @param int    $start The position in the word tokens where this element
     *                      started.
     * @param int    $end   The position in the word tokens where this element
     *                      ended.
     *
     * @return void
     * @throws Exception If the process method for the tag cannot be found.
     */
    protected function parseTag($tag, $start, $end)
    {
        $tokens = array_slice($this->words, ($start + 1), ($end - $start));

        $allowedTags     = $this->getAllowedTags();
        $allowedTagNames = array_keys($allowedTags);
        if ($tag === 'comment' || in_array($tag, $allowedTagNames) === true) {
            $this->previousElement = $this->createElement($tag, $tokens);
        } else {
            $this->previousElement = new PHP_CodeSniffer_CommentParser_SingleElement(
                $this->previousElement, $tokens, $tag, $this->phpcsFile
            );
        }

        $this->orders[] = $tag;

        if ($this->previousElement === null || ($this->previousElement instanceof PHP_CodeSniffer_CommentParser_DocElement) === false) {
            throw new Exception('Parse method must return a DocElement');
        }
    }
    
    protected function getElementConfig()
    {
        if ($this->_config === null) {
            $this->_config = array_merge(
                $this->getClassElementConfig(),
                array(
                    'comment'  =>  array(
                        'single'  =>  true,
                        'class'   =>  'PHP_CodeSniffer_CommentParser_CommentElement',
                        'name'    =>  'comment'
                    ),
                    'see'  =>  array(
                        'single'  =>  false,
                        'class'   =>  'PHP_CodeSniffer_CommentParser_SingleElement',
                        'name'    =>  'sees'  
                    ),
                    'deprecated'  =>  array(
                        'single'  =>  true,
                        'class'   =>  'PHP_CodeSniffer_CommentParser_SingleElement',
                        'name'    =>  'deprecated'
                    ),
                    'since'  =>  array(
                        'single'  =>  true,
                        'class'   =>  'PHP_CodeSniffer_CommentParser_SingleElement',
                        'name'    =>  'since'
                    ),
                    'link'  =>  array(
                        'single'  =>  false,
                        'class'   =>  'PHP_CodeSniffer_CommentParser_SingleElement',
                        'name'    =>  'link'
                    ),
                )
            );
        }
        
        return $this->_config; 
/*
        array(
            'mainclass'  =>  array(
                'single'  =>  true,
                'class'   =>  'PHP_CodeSniffer_Standards_EZC_CommentParser_LeafElement',
                'name'    =>  'mainclass',
            ),
            'tutorial'  =>  array(
                'single'  =>  true,
                'class'   =>  'PHP_CodeSniffer_CommentParser_SingleElement',
                'name'    =>  'tutorial',
            ),
            'uses'  =>  array(
                'single'  =>  false,
                'class'   =>  'PHP_CodeSniffer_CommentParser_SingleElement',
                'name'    =>  'usess'
            ),
            'property'  =>  array(
                'single'  =>  false,
                'class'   =>  'PHP_CodeSniffer_Standards_EZC_CommentParser_PropertyElement',
                'name'    =>  'properties'
            ),
            'property-read'  =>  array(
                'single'  =>  false,
                'class'   =>  'PHP_CodeSniffer_Standards_EZC_CommentParser_PropertyElement',
                'name'    =>  'properties'
            ),
            'property-write'  =>  array(
                'single'  =>  false,
                'class'   =>  'PHP_CodeSniffer_Standards_EZC_CommentParser_PropertyElement',
                'name'    =>  'properties'
            ),
            
            // {{{ AbstractParser

            // }}}
            
            // {{{ PEAR ClassCommentParser
            'license'  =>  array(
                'single'  =>  true,
                'class'   =>  'PHP_CodeSniffer_CommentParser_PairElement',
                'name'    =>  'license'
            ),
            'copyright'  =>  array(
                'single'  =>  false,
                'class'   =>  'PHP_CodeSniffer_CommentParser_SingleElement',
                'name'    =>  'copyright'
            ),
            'category'  =>  array(
                'single'  =>  true,
                'class'   =>  'PHP_CodeSniffer_CommentParser_SingleElement',
                'name'    =>  'category'
            ),
            'author'  =>  array(
                'single'  =>  false,
                'class'   =>  'PHP_CodeSniffer_CommentParser_SingleElement',
                'name'    =>  'author'
            ),
            'version'  =>  array(
                'single'  =>  true,
                'class'   =>  'PHP_CodeSniffer_CommentParser_SingleElement',
                'name'    =>  'version'
            ),
            'package'  =>  array(
                'single'  =>  true,
                'class'   =>  'PHP_CodeSniffer_CommentParser_SingleElement',
                'name'    =>  'package'
            ),
            'subpackage'  =>  array(
                'single'  =>  true,
                'class'   =>  'PHP_CodeSniffer_CommentParser_SingleElement',
                'name'    =>  'subpackage'
            ),
            // }}}
        );
*/
    }
    
    protected abstract function getClassElementConfig();
}
