<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_menu
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

// Note. It is important to remove spaces between elements.
$title = $citem->anchor_title ? 'title="'.$citem->anchor_title.'" ' : '';
if ($citem->menu_image) {
		$citem->params->get('menu_text', 1 ) ?
		$linktype = '<img src="'.$citem->menu_image.'" alt="'.$citem->title.'" /><span class="image-title">'.$citem->title.'</span> ' :
		$linktype = '<img src="'.$citem->menu_image.'" alt="'.$citem->title.'" />';
}
else { $linktype = $citem->title;
}

?><span class="separator"><?php echo $title; ?><?php echo $linktype; ?></span>
