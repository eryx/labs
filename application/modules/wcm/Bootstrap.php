<?php
/**
 * 
 * @category   Module
 * @package    Wcm
 * @subpackage Wcm_Bootstrap
 * @version    $Id: Bootstrap.php 850 2010-04-26 08:45:16Z evorui $
 */
 

/**
 * ensure this file is being included by a parent file
 */
defined('SYS_ENTRY') or die('Access Denied!');


/**
 * @see Zend_Application_Module_Bootstrap
 */
require_once 'Zend/Application/Module/Bootstrap.php';


/**
 * Class Wcm_Bootstrap
 * 
 * @category   Module
 * @package    Wcm
 * @subpackage Wcm_Bootstrap
 */
class Wcm_Bootstrap extends Zend_Application_Module_Bootstrap
{
}
