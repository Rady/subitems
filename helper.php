<?php
/**
* Author: Rady Huang
* Email: hycn119@gmail.com
* Module: Sub Items
* Version: 1.2
*/

// No Direct Access
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

jimport( 'joomla.html.parameter' );

class ModSubItemsHelper
{
    
  static function getList($params,$pid=null)
	{
		
		// Initialise variables.
		$list		= array();
		$db			= JFactory::getDbo();
		$user		= JFactory::getUser();
		$app		= JFactory::getApplication();
		$menu		= $app->getMenu();

		// If no active menu, use default
		$active = ($menu->getActive()) ? $menu->getActive() : $menu->getDefault();

		$path		= $active->tree;
		$start		= (int) $params->get('startLevel');
		$end		= (int) $params->get('endLevel');
		$showAll	= $params->get('showAllChildren');
		$maxdepth	= $params->get('maxdepth');
		if($pid){
		    $items 		= $menu->getItems('parent_id',$pid);
		}else
		{
			$items 		= $menu->getItems('parent_id',$params->get('parentid'));			
		}
		
		$exmenuArr = array();

		if($params->get('excludeids'))
		{
		    $exmenuArr   = explode(",", $params->get('excludeids'));
		}

		$lastitem	= 0;	
		
		if ($items) {
			foreach($items as $i => $item)
			{				
				$item->deeper = false;
				$item->shallower = false;
				$item->level_diff = 0;

				if (isset($items[$lastitem])) {
					$items[$lastitem]->deeper		= ($item->level > $items[$lastitem]->level);
					$items[$lastitem]->shallower	= ($item->level < $items[$lastitem]->level);
					$items[$lastitem]->level_diff	= ($items[$lastitem]->level - $item->level);
				}

				if($exmenuArr && in_array($item->id,$exmenuArr))
				{
				    unset($items[$i]);
				}

				$item->parent = (boolean) $menu->getItems('parent_id', (int) $item->id, true);

				$lastitem			= $i;
				$item->active		= false;
				$item->flink = $item->link;

				switch ($item->type)
				{
					case 'separator':
						// No further action needed.
						continue;

					case 'url':
						if ((strpos($item->link, 'index.php?') === 0) && (strpos($item->link, 'Itemid=') === false)) {
							// If this is an internal Joomla link, ensure the Itemid is set.
							$item->flink = $item->link.'&Itemid='.$item->id;
						}
						break;

					case 'alias':
						// If this is an alias use the item id stored in the parameters to make the link.
						$item->flink = 'index.php?Itemid='.$item->params->get('aliasoptions');
						break;

					default:
						$router = JSite::getRouter();
						if ($router->getMode() == JROUTER_MODE_SEF) {
							$item->flink = 'index.php?Itemid='.$item->id;
						}
						else {
							$item->flink .= '&Itemid='.$item->id;
						}
						break;
				}

				if (strcasecmp(substr($item->flink, 0, 4), 'http') && (strpos($item->flink, 'index.php?') !== false)) {
					$item->flink = JRoute::_($item->flink, true, $item->params->get('secure'));
				}
				else {
					$item->flink = JRoute::_($item->flink);
				}

				$item->title = htmlspecialchars($item->title);
				$item->anchor_css = htmlspecialchars($item->params->get('menu-anchor_css', ''));
				$item->anchor_title = htmlspecialchars($item->params->get('menu-anchor_title', ''));
				$item->menu_image = $item->params->get('menu_image', '') ? htmlspecialchars($item->params->get('menu_image', '')) : '';
			}

			if (isset($items[$lastitem])) {
				$items[$lastitem]->deeper     = (($start?$start:1) > $items[$lastitem]->level);
				$items[$lastitem]->shallower  = (($start?$start:1) < $items[$lastitem]->level);
				$items[$lastitem]->level_diff = ($items[$lastitem]->level - ($start?$start:1));
			}
		}

		return $items;
	}	  
	
	static public function getParent( $params )
	{
		$app    = JFactory::getApplication();
		$menu   = $app->getMenu();
		$parent = $menu->getItem($params->get('parentid')); 
		return $parent;
	}
}
