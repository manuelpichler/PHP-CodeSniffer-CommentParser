<?php
/**
 * A class to represent single element doc tags.
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

if (class_exists('PHP_CodeSniffer_CommentParser_AbstractDocElement', true) === false) {
    $error = 'Class PHP_CodeSniffer_CommentParser_AbstractDocElement not found';
    throw new PHP_CodeSniffer_Exception($error);
}

/**
 * A class to represent single element doc tags.
 *
 * A single element doc tag contains only one value after the tag itself. An
 * example would be the \@package tag.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer_Standards_EZC
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @author    Manuel Pichler <mapi@manuel-pichler.de>
 * @copyright 2007-2008 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class PHP_CodeSniffer_CommentParser2_LeafElement extends PHP_CodeSniffer_CommentParser_AbstractDocElement
{
    /**
     * Constructs a SingleElement doc tag.
     *
     * @param PHP_CodeSniffer_CommentParser_DocElement $previousElement The element
     *                                                                  before this
     *                                                                  one.
     * @param array                                    $tokens          The tokens
     *                                                                  that comprise
     *                                                                  this element.
     * @param string                                   $tag             The tag that
     *                                                                  this element
     *                                                                  represents.
     * @param PHP_CodeSniffer_File                     $phpcsFile       The file that
     *                                                                  this element
     *                                                                  is in.
     */
    public function __construct($previousElement, $tokens, $tag, PHP_CodeSniffer_File $phpcsFile)
    {
        parent::__construct($previousElement, $tokens, $tag, $phpcsFile);
    }//end __construct()


    /**
     * Returns the element names that this tag is comprised of, in the order
     * that they appear in the tag.
     *
     * @return array(string)
     * @see processSubElement()
     */
    protected function getSubElements()
    {
        return array( null );
    }//end getSubElements()


    /**
     * Processes the sub element with the specified name.
     *
     * @param string $name             The name of the sub element to process.
     * @param string $content          The content of this sub element.
     * @param string $whitespaceBefore The whitespace that exists before the
     *                                 sub element.
     *
     * @return void
     * @see getSubElements()
     */
    protected function processSubElement($name, $content, $whitespaceBefore)
    {
    }//end processSubElement()


    /**
     * Processes a content check for single doc element.
     *
     * @param PHP_CodeSniffer_File $phpcsFile    The file being scanned.
     * @param int                  $commentStart The line number where
     *                                           the error occurs.
     * @param string               $docBlock     Whether this is a file or
     *                                           class comment doc.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $commentStart, $docBlock)
    {
    }//end process()

}//end class