<?php
/**
 * @package     Modules
 * @subpackage  mod_articles_reslider
 * @copyright   Copyright (C) 2013 TiagoGarcia, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

// Exibe a barra de links
$mostrar_barra = $params->get('mostrar_barra', '1');

// Exibe link + e o titulo
$link_categoria = $params->get('link_categoria', '1');
$titulo_mais_conteudo = $params->get('titulo_mais_conteudo', '');

// Exibe link de feed/rss
$link_feed = $params->get('link_feed', '1');

// Exibe link_opcional1
$link_opcional1 = $params->get('link_opcional1', '0');

// Exibe titulo_link1
$titulo_link1 = $params->get('titulo_link1', '');

// Exibe link1
$link1 = $params->get('link1', '');

// Exibe link_opcional2
$link_opcional2 = $params->get('link_opcional2', '0');

// Exibe titulo_link2
$titulo_link2 = $params->get('titulo_link2', '');

// Exibe link2
$link2 = $params->get('link2', '');

?>


<!-- Carrossel de imagens -->
<div class="flexslider <?php echo $params->get('moduleclass_sfx', ''); ?>">
  <ul class="slides">
	<?php if (!empty($items)) {
		foreach($items as $item){
			echo $item->metadesc;
		}
	} ?>
  </ul>


<!-- Barra de links -->
<?php if (!empty($items)) { ?>
  <?php if ($mostrar_barra==1): ?>
  <?php $i=0; ?>
	<div id="slideshowDisplayNav" class="hidden-phone">

	  <?php if ($link_categoria==1): ?>
	  <?php $i++ ; ?>
		<span>
		  <a title="<?php echo $titulo_mais_conteudo ?>" href="<?php echo $item->category_route; ?>" class="leiaMais">
			<?php echo $titulo_mais_conteudo ?>
		  </a>
		</span>
	  <?php endif ?>

	  <?php if ($link_feed==1): ?>
	  <?php $i++ ; ?>
		<?php if($i>1){ ?>
		  <span class="separador"></span>
		<?php } ?>
		<a class="" href="<?php echo $item->category_route . '&type=rss&format=feed'; ?>" target="_BLANK">
		  <img alt="RSS" class="rss" src="modules/mod_articles_slideshow_responsive_ms/assets/img/rss.jpg">
		</a>
	  <?php endif ?>

	  <?php if ($link_opcional1==1): ?>
	  <?php $i++ ; ?>
		<?php if($i>1){ ?>
		  <span class="separador"></span>
		<?php } ?>
		<span>
		  <a title="<?php echo $titulo_link1 ?>" target="_parent" href="<?php echo $link1 ?>" class="linkNoticia">
			<?php echo $titulo_link1 ?>
		  </a>
		</span>
	  <?php endif ?>

	  <?php if ($link_opcional2==1): ?>
	  <?php $i++ ; ?>
		<?php if($i>1){ ?>
		  <span class="separador"></span>
		<?php } ?>
		<span>
		  <a title="<?php echo $titulo_link2 ?>" target="_parent" href="<?php echo $link2 ?>" class="linkNoticia">
			<?php echo $titulo_link2 ?>
		  </a>
		</span>
	  <?php endif ?>

	</div>
  <?php endif ?>
  <?php } ?>
</div>


<script type="text/javascript" charset="utf-8">
jQuery(window).load(function() {
	jQuery('.flexslider').flexslider({
		<?php if($params->get('fadeorslide') == 0){?> animation: "slide", <?php } else if ($params->get('fadeorslide') == 1){ ?> animation: "fade", <?php } ?>
		<?php if($params->get('directionNav') == 1){?> directionNav: true, <?php } else if ($params->get('directionNav') == 0){ ?> directionNav: false, <?php } ?>
		<?php if($params->get('controlNav') == 1){?> controlNav: true, <?php } else if ($params->get('controlNav') == 0){ ?> controlNav:false, <?php } ?>
		<?php if($params->get('keyboardNav') == 1){?> keyboardNav: true, <?php } else if ($params->get('keyboardNav') == 0){ ?> keyboardNav:false, <?php } ?>
		<?php if($params->get('direction') == 0){?> direction: "horizontal", <?php } else if ($params->get('direction') == 1){ ?> direction: "vertical", <?php } ?>
		<?php if($params->get('slidespeed')){ echo "slideshowSpeed:".$params->get('slidespeed')."," ;} else { ?> slideshowSpeed: 7000, <?php } ?>
		<?php if($params->get('animatespeed')){ echo "animationSpeed:".$params->get('animatespeed')."," ;} else { ?> animationSpeed: 600, <?php } ?>
		<?php if($params->get('randomorder') == 0){?> randomize: false <?php } else if ($params->get('randomorder') == 1){ ?> randomize: true <?php } ?>
	});
});
</script>
