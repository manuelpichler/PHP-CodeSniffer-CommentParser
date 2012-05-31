<?php
/**
 * Represents virtual property annotations \@property, \@property-read and \@property-write.
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

if (class_exists('PHP_CodeSniffer_CommentParser_ParameterElement', true) === false) {
    throw new PHP_CodeSniffer_Exception('Class PHP_CodeSniffer_CommentParser_ParameterElement not found');
}

/**
 * Represents virtual property annotations \@property, \@property-read and \@property-write.
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
class PHP_CodeSniffer_CommentParser2_PropertyElement extends PHP_CodeSniffer_CommentParser_ParameterElement
{
    /**
     * Constructs a PHP_CodeSniffer_CommentParser_ParameterElement.
     *
     * @param PHP_CodeSniffer_CommentParser_DocElement $previousElement The element previous to this one.
     * @param array                                    $tokens          The tokens that make up this element.
     * @param string                                   $tag             ...
     * @param PHP_CodeSniffer_File                     $phpcsFile       The file that this element is in.
     */
    public function __construct($previousElement, $tokens, $tag, PHP_CodeSniffer_File $phpcsFile)
    {
        parent::__construct($previousElement, $tokens, $phpcsFile);
        
        $this->tag = $tag;
    }
}