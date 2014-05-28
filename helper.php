<?php
/**
 * @package     Reslider
 * @subpackage  mod_articles_reslider
 * @copyright   Copyright (C) 2013 MS, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Tiago Garcia modulo reescrito 90% do original.
 */

// No direct access.
defined('_JEXEC') or die;

require_once JPATH_SITE.'/components/com_content/helpers/route.php';

JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_content/models', 'ContentModel');

/**
 * Helper for mod_articles_reslider
 *
 * @package     Joomla.Site
 * @subpackage  mod_articles_reslider
 */
class modArticlesResliderHelper
{
	public static function getList(&$params){

		$app = JFactory::getApplication();

		// Get an instance of the generic articles model
		$model = JModelLegacy::getInstance('Articles', 'ContentModel', array('ignore_request' => true));
		// Set application parameters in model
		$appParams = JFactory::getApplication()->getParams();

		$model->setState('params', $appParams);
		$model->setState('list.start', 0);
		$model->setState('list.limit', (int) $params->get('count', 5));
		$model->setState('filter.published', 1);
		$model->setState('list.select');
		$cat = $params->get('catid');
		$model->setState('filter.category_id', (int) $cat[0]);
		$model->setState('filter.subcategories', true);

		// set only featured
		$model->setState('filter.featured', 'only');

		// Set ordering
		$ordering = 'fp.ordering';
		// var_dump($ordering);
		$model->setState('list.ordering', $ordering);

		if (trim($ordering) == 'rand()')
		{
			$model->setState('list.direction', '');
		}
		else
		{
			$model->setState('list.direction', 'ASC');
		}

		//	Retrieve Content
		$items = $model->getItems();

		if (!empty($items)){
			$linkcat = JRoute::_(ContentHelperRoute::getCategoryRoute($cat[0]));

			foreach ($items as &$item)
			{
				$item->slug = $item->id.':'.$item->alias;
				// $item->catslug = $item->catid.':'.$item->category_alias;

				$item->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid));
				$item->category_route = $linkcat;

				$item->linkText = JText::_('MOD_ARTICLES_NOTICIAS_READMORE');


				$item->introtext = JHtml::_('content.prepare', $item->introtext, '', 'mod_articles_news.content');

				$images = json_decode($item->images);  // decode da imagem.

				// exibe o titulo do artigo(item)
				$item_title = $params->get('item_title', 1);
				$uselinks = $params->get('uselinks', 1);
				$linkArtigoExternoA = $params->get('linkArtigoExternoA', 1);

				// define se o link vem do artigo ou vem de um link externo.
				if ($linkArtigoExternoA == 1) {
					$link = $item->link;
				}else{
					$link = modArticlesResliderHelper::default_link($params, $item);
					if (empty($link)) {
						$link = $item->link;
					}
				}

				// Usar titulo no caption e links
				if($item_title && $uselinks ){
					$item->metadesc = "<li><a href='".$link."'><img src='". JUri::base() . htmlspecialchars($images->image_intro)."' alt='".$item->title."'><p class='flex-caption'>".$item->title."</p></a></li>";
				}

				// Usar somente links sem caption titulo.
				if(!$item_title && $uselinks ){
					$item->metadesc = "<li><a href='".$link."'><img src='". JUri::base() . htmlspecialchars($images->image_intro)."' alt='".$item->title."'></a></li>";
				}

				// usar somente imagem, sem caption, sem link.
				if(!$item_title && !$uselinks ){
					$item->metadesc = "<li><img src='". JUri::base() . htmlspecialchars($images->image_intro)."' alt='".$item->title."'></li>";
				}


				// mostrar imagens dentro do artigo.
				if (!$params->get('image'))
				{
					$item->introtext = preg_replace('/<img[^>]*>/', '', $item->introtext);
				}

				$results = $app->triggerEvent('onContentAfterDisplay', array('com_content.article', &$item, &$params, 1));
				$item->afterDisplayTitle = trim(implode("\n", $results));

				$results = $app->triggerEvent('onContentBeforeDisplay', array('com_content.article', &$item, &$params, 1));
				$item->beforeDisplayContent = trim(implode("\n", $results));
			}
		}
		return $items;
	}


	public static function load_jquery(&$params)
	{
		if($params->get('load_jquery')){
			JLoader::import( 'joomla.version' );
			$version = new JVersion();
			if (version_compare( $version->RELEASE, '2.5', '<=')) {
				$doc = JFactory::getDocument();
				$app = JFactory::getApplication();
				$file= JURI::root(true).'/modules/mod_articles_reslider/assets/js/jquery-1.7.2.min.js';
				$file2= JURI::root(true).'/modules/mod_articles_reslider/assets/js/noconflict.js';
				$doc->addScript($file);
				$doc->addScript($file2);
			} else {
				JHtml::_('jquery.framework');
			}
		}
	}

	public static function default_link(&$params, $item){
		// Create shortcut
		$urls = json_decode($item->urls);
		// Create shortcuts to some parameters.
		if ($urls && (!empty($urls->urla) || !empty($urls->urlb) || !empty($urls->urlc))){

			$urlarray = array(
				array($urls->urla, $urls->urlatext, $urls->targeta, 'a'),
				array($urls->urlb, $urls->urlbtext, $urls->targetb, 'b'),
				array($urls->urlc, $urls->urlctext, $urls->targetc, 'c')
				);

			foreach ($urlarray as $url){
				$link = $url[0];
				$label = $url[1];
				$target = $url[2];
				$id = $url[3];

				if ( ! $link){
					continue;
				}

				// If no label is present, take the link
				$label = ($label) ? $label : $link;

				// If no target is present, use the default
				$target = ($target) ? $target : $params->get('target'.$id);
				if (!empty($target)) {
					$target = $params->get('target'.$id);
				}

				// Compute the correct link
				switch ($target)
				{
					case 1:
					// open in a new window
					htmlspecialchars($link) .'" target="_blank"  rel="nofollow">';
					break;

					case 2:
					// open in a popup window
					$attribs = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=600,height=600';
					htmlspecialchars($link) . "\" onclick=\"window.open(this.href, 'targetWindow', '".$attribs."'); return false;\"";
					break;
					default:
					// open in parent window
					htmlspecialchars($link) . '" rel="nofollow">';
					break;
				}
				return $link;
			}
		}
	}
}