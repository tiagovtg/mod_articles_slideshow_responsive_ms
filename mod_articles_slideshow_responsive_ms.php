<?php
/**
 * @package     Articles_Reslider
 * @subpackage  mod_articles_slideshow_responsive_ms
 * @copyright   Copyright (C) 2013 MS, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Tiago Garcia.
 */

// No direct access.
defined('_JEXEC') or die;

require_once __DIR__ . '/helper.php';

// $moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

// carrega os dados da listagem
$items = modArticlesResliderHelper::getList($params);

// carrega os js e css
modArticlesResliderHelper::load_jquery($params);
$doc = JFactory::getDocument();
// $doc->addStyleSheet(JURI::base(true) . '/modules/mod_articles_slideshow_responsive_ms/assets/css/style.css', 'text/css' );
$doc->addStyleSheet(JURI::base(true) . '/modules/mod_articles_slideshow_responsive_ms/assets/css/flexslider.css', 'text/css' );
// $doc->addScript(JURI::base(true)     . '/modules/mod_articles_slideshow_responsive_ms/assets/js/jquery.flexslider-min.js');

require(JModuleHelper::getLayoutPath('mod_articles_slideshow_responsive_ms'));
