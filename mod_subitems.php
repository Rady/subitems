<?php
/**
* Author: Rady Huang
* Email: hycn119@gmail.com
* Module: Sub Items
* Version: 1.2
*/

// No Direct Access
defined('_JEXEC') or die('Direct Access to this location is not allowed.');
 
require_once(dirname(__FILE__).'/helper.php');

$app = JFactory::getApplication();
$menu	= $app->getMenu();
$active	= $menu->getActive();

$parent_id = isset($active) ? $active->parent_id : $menu->getDefault()->id;
$active_id = isset($active) ? $active->id : $menu->getDefault()->id;

$path	= isset($active) ? $active->tree : array();
 
$items = ModSubItemsHelper::getList($params);
$parent = ModSubItemsHelper::getParent($params);

$class_sfx	= htmlspecialchars($params->get('moduleclass_sfx'));

if(count($items)) {
	require JModuleHelper::getLayoutPath('mod_subitems', $params->get('layout', 'default'));
}
