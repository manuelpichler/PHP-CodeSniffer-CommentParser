<?php
/**
 * Parses class member comments.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version   CVS: $Id: MemberCommentParser.php,v 1.8 2007/11/30 01:18:41 squiz Exp $
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

if (class_exists('PHP_CodeSniffer_CommentParser2_ClassCommentParser', true) === false) {
    $error = 'Class PHP_CodeSniffer_CommentParser2_ClassCommentParser not found';
    throw new PHP_CodeSniffer_Exception($error);
}

/**
 * Parses class member comments.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version   Release: 1.0.0
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class PHP_CodeSniffer_CommentParser2_MemberCommentParser extends PHP_CodeSniffer_CommentParser2_ClassCommentParser
{
    protected function getClassElementConfig()
    {
        return array(
            'var'  =>  array(
                'single'  =>  true,
                'class'   =>  'PHP_CodeSniffer_CommentParser_SingleElement',
                'name'    =>  'var'
            )
        );
    }
}