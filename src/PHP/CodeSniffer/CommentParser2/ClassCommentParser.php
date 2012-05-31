<?php
/**
 * PHP_CodeSniffer_Standards_EZC_CommentParser_ClassCommentParser.
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

if (class_exists('PHP_CodeSniffer_CommentParser2_AbstractParser', true) === false) {
    throw new PHP_CodeSniffer_Exception(
        'Class PHP_CodeSniffer_CommentParser2_AbstractParser not found'
    );
}

if (class_exists('PHP_CodeSniffer_CommentParser2_PropertyElement', true) === false) {
    throw new PHP_CodeSniffer_Exception(
        'Class PHP_CodeSniffer_CommentParser2_PropertyElement not found'
    );
}

/**
 * PHP_CodeSniffer_Standards_EZC_CommentParser_ClassCommentParser.
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
class PHP_CodeSniffer_CommentParser2_ClassCommentParser extends PHP_CodeSniffer_CommentParser2_AbstractParser
{
    protected function getClassElementConfig()
    {
        return array(
            'license'  =>  array(
                'single'  =>  true,
                'class'   =>  'PHP_CodeSniffer_CommentParser_PairElement',
                'name'    =>  'license'
            ),
            'copyright'  =>  array(
                'single'  =>  false,
                'class'   =>  'PHP_CodeSniffer_CommentParser_SingleElement',
                'name'    =>  'copyrights'
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
        );
    }
}
