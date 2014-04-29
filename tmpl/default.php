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

$isShowChild = $params->get('showchild');

$exmenuChildArr = array();

if($params->get('excludechildids')){
	$exmenuChildArr   = explode(",", $params->get('excludechildids'));
}

?>

<ul class="nav<?php echo $class_sfx;?>"<?php
	$tag = '';
	if ($params->get('parentid')!=NULL) {
		$tag = 'menu'.$params->get('parentid');
		echo ' id="'.$tag.'"';
	}
?>>
<?php
foreach ($items as $i => &$item) :
	$class = 'item-'.$item->id;
	if ($item->id == $active_id) {
		$class .= ' current';
	}

	if (in_array($item->id, $path)) {
		$class .= ' active';
	}
	elseif ($item->type == 'alias') {
		$aliasToId = $item->params->get('aliasoptions');
		if (count($path) > 0 && $aliasToId == $path[count($path)-1]) {
			$class .= ' active';
		}
		elseif (in_array($aliasToId, $path)) {
			$class .= ' alias-parent-active';
		}
	}

	if ($item->deeper) {
		$class .= ' deeper';
	}

	if ($item->parent) {
		$class .= ' parent';
	}

	if (!empty($class)) {
		$class = ' class="'.trim($class) .'"';
	}

	echo '<li'.$class.'>';

	// Render the menu item.
	switch ($item->type) :
		case 'separator':
		case 'url':
		case 'component':
			require JModuleHelper::getLayoutPath('mod_subitems', 'default_'.$item->type);
			break;

		default:
			require JModuleHelper::getLayoutPath('mod_subitems', 'default_url');
			break;
	endswitch;

	// The next item is deeper.
	if ($item->parent) {
		// deal the child
		$childItems = null;
		if( $isShowChild ){
			if(!in_array($item->id,$exmenuChildArr)){
				$childItems = ModSubItemsHelper::getList($params,$item->id);
				if($childItems){
					echo "<ul>";
					foreach ($childItems as $citem) {
            $classChild = 'item-'.$citem->id;
						if ($citem->id == $active_id) {
							$classChild .= ' current';
						}

						if (in_array($citem->id, $path)) {
							$classChild .= ' active';
						}
						elseif ($citem->type == 'alias') {
							$aliasToId = $citem->params->get('aliasoptions');
							if (count($path) > 0 && $aliasToId == $path[count($path)-1]) {
								$classChild .= ' active';
							}
							elseif (in_array($aliasToId, $path)) {
								$classChild .= ' alias-parent-active';
							}
						}
						if (!empty($classChild)) {
							$classChild = ' class="'.trim($classChild) .'"';
						}

						echo '<li'.$classChild.'>';
             // Render the menu item.
							switch ($citem->type) :
								case 'separator':
								case 'url':
								case 'component':
									require JModuleHelper::getLayoutPath('mod_subitems', 'default_child_'.$citem->type);
									break;

								default:
									require JModuleHelper::getLayoutPath('mod_subitems', 'default_child_url');
									break;
							endswitch;
						echo "</li>";
					}
					echo "</ul>";
				}
			}
		}

	}
	// The next item is shallower.
	elseif ($item->shallower) {
		echo '</li>';
		echo str_repeat('</ul></li>', $item->level_diff);
	}
	// The next item is on the same level.
	else {
		echo '</li>';
	}
endforeach;
?></ul>
