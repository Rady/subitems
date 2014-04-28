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
$class = $citem->anchor_css ? 'class="'.$citem->anchor_css.'" ' : '';
$title = $citem->anchor_title ? 'title="'.$citem->anchor_title.'" ' : '';
if ($citem->menu_image) {
		$citem->params->get('menu_text', 1 ) ?
		$linktype = '<img src="'.$citem->menu_image.'" alt="'.$citem->title.'" /><span class="image-title">'.$citem->title.'</span> ' :
		$linktype = '<img src="'.$citem->menu_image.'" alt="'.$citem->title.'" />';
}
else { $linktype = $citem->title;
}

switch ($citem->browserNav) :
	default:
	case 0:
?><a <?php echo $class; ?>href="<?php echo $citem->flink; ?>" <?php echo $title; ?>><?php echo $linktype; ?></a><?php
		break;
	case 1:
		// _blank
?><a <?php echo $class; ?>href="<?php echo $citem->flink; ?>" target="_blank" <?php echo $title; ?>><?php echo $linktype; ?></a><?php
		break;
	case 2:
	// window.open
?><a <?php echo $class; ?>href="<?php echo $citem->flink; ?>" onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes');return false;" <?php echo $title; ?>><?php echo $linktype; ?></a>
<?php
		break;
endswitch;
