<?php
/**
 * Parses function doc comments.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version   CVS: $Id: FunctionCommentParser.php,v 1.8 2007/11/30 01:18:41 squiz Exp $
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

if (class_exists('PHP_CodeSniffer_CommentParser2_AbstractParser', true) === false) {
    $error = 'Class PHP_CodeSniffer_CommentParser2_AbstractParser not found';
    throw new PHP_CodeSniffer_Exception($error);
}

if (class_exists('PHP_CodeSniffer_CommentParser_ParameterElement', true) === false) {
    $error = 'Class PHP_CodeSniffer_CommentParser_ParameterElement not found';
    throw new PHP_CodeSniffer_Exception($error);
}

if (class_exists('PHP_CodeSniffer_CommentParser_PairElement', true) === false) {
    $error = 'Class PHP_CodeSniffer_CommentParser_PairElement not found';
    throw new PHP_CodeSniffer_Exception($error);
}

if (class_exists('PHP_CodeSniffer_CommentParser_SingleElement', true) === false) {
    $error = 'Class PHP_CodeSniffer_CommentParser_SingleElement not found';
    throw new PHP_CodeSniffer_Exception($error);
}

/**
 * Parses function doc comments.
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
class PHP_CodeSniffer_CommentParser2_FunctionCommentParser extends PHP_CodeSniffer_CommentParser2_AbstractParser
{
    protected function getClassElementConfig()
    {
        return array(
            'param'  =>  array(
                'single'  =>  false,
                'class'   =>  'PHP_CodeSniffer_CommentParser_ParameterElement',
                'name'    =>  'params'
            ),
            'return'  =>  array(
                'single'  =>  true,
                'class'   =>  'PHP_CodeSniffer_CommentParser_PairElement',
                'name'    =>  'return'
            ),
            'throws'  =>  array(
                'single'  =>  false,
                'class'   =>  'PHP_CodeSniffer_CommentParser_PairElement',
                'name'    =>  'throws'
            ),
        );
    }
}