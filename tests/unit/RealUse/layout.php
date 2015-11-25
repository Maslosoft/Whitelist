<?php

use Maslosoft\Ilmatar\Components\Controller;
use Maslosoft\Ilmatar\Widgets\Breadcrumbs;
use Maslosoft\Ilmatar\Widgets\Html\Decorator;
use Maslosoft\Ilmatar\Widgets\I18N\Flags;
use Maslosoft\Ilmatar\Widgets\Search\SearchBox;
use Maslosoft\Menulis\Content\Models\PageItem;
use Maslosoft\Menulis\Widgets\Menu\LinkBlocks;
use Maslosoft\Menulis\Widgets\Menu\PageLinks;

/* @var $this Controller */
?>

<div id="mainWrapper">
	<div class="container">

		<header id="topBg" class="layout">
			<div class="row">
				<div class="col-sm-9" >
					<a class="logo-img" href="/" title="Maslosoft">
					</a>
					<a class="logo-text" href="/" title="Maslosoft - from bit to system">
						<div>from bit to system</div>
					</a>
				</div>
				<div class="col-sm-3 hidden-xs" id="searchBox">
					<?= new SearchBox(); ?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12" >
					<nav id="menuBg">
						<?php
						echo PageLinks::widget([
							'code' => 'mainMenu',
//							'authLink' => true,
							'flags' => true
						]);
						?>
					</nav>
				</div>
			</div>
		</header>
		<div class="layout visible-xs-block" style="background: white;">
			<div class="row">
				<div class="col-xs-12">
					<?= new SearchBox(); ?>
				</div>
			</div>
		</div>
		<?= $this->renderMessages(); ?>
		<?php $bind = Yii::app()->controller->id == 'page' && Yii::app()->controller->action->id == 'update'; ?>
		<?php $hideBreadcrumbs = Yii::app()->controller->id == 'page' && Yii::app()->controller->action->id == 'view' && PageItem::model()->findByPk($_GET['id'])->url === '/'; ?>
		<?php if (!$hideBreadcrumbs): ?>
			<div class="layout">
				<?php
				$this->widget(Breadcrumbs::class, array(
					'homeLink' => [tx('Home page') => (string) Yii::app()->baseUrl . '/'],
					'links' => $this->breadcrumbs,
					'encodeLabel' => false,
					'separator' => ' <i class="fa fa-angle-right"></i> '
				));
				?>
			</div>
		<?php endif; ?>
		<div id="contentBg" class="layout">
			<div id="content" class="row">
				<?= $content; ?>
			</div>
		</div>
		<footer id="bottomBg" class="layout">
			<div>
				<div class="row">
					<div class="col-xs-6">
						<?php
						echo PageLinks::msWidget([
							'code' => 'mainMenu',
						]);
						?>
					</div>
					<div class="col-xs-6">
						<div class="networkIcons">
							<?php
							$links = [
								['github-square', 'https://github.com/Maslosoft', 'GitHub'],
								['bitbucket-square', 'https://bitbucket.org/maslosoft/', 'BitBucket'],
								['twitter-square', 'https://twitter.com/Maslosoft/', 'Twitter'],
								//								['facebook-square', 'https://www.facebook.com/Maslosoft', 'Facebook'],
								['linkedin-square', 'https://www.linkedin.com/company/maslosoft', 'LinkedIn'],
								['google-plus-square', 'https://plus.google.com/+Maslosoft/', 'Google+']
							];
							?>
							<?php foreach ($links as $link): ?>
								<?= CHtml::link(Decorator::fa(array_shift($link), '2x', '<div></div>'), array_shift($link), ['title' => array_shift($link), 'target' => '_blank', 'rel' => 'tooltip']); ?>
								<!--<div style="background: white;width: 35px;height: 35px;position: absolute;top: 4px;z-index: -10;border-radius: 6px;box-shadow: 1px 1px 1px black;"></div>-->
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		</footer>
	</div>

	<div id="bottomWrapper">
		<div class="container">
			<div id="leafBot" class="layout">
				<div class="row">
					<?php
					echo LinkBlocks::widget([
						'columns' => '4',
						'showTitles' => true,
						'codes' => [
							'bottomLeft',
							'bottomCenter',
							'bottomRight'
					]]);
					?>
				</div>
				<div id="copyright" class="row">
					<div class="col-md-3 pull-left">
						<p>&copy; <?= Decorator::copyright('2012'); ?> <a href="http://maslosoft.com/">Maslosoft</a></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
<?php $this->beginClip('menu-stick'); ?>
	jQuery(document).ready(function () {
		var menu = $("#menuBg");
		menu.stick_in_parent();
		$('body').tooltip({container: 'body', selector: '[rel="tooltip"]'});
		$('pre').each(function (i, block) {
			$(block).addClass('php').find('code').addClass('php');
			hljs.highlightBlock(block);
		});
	});
<?php $this->endClip(); ?>
<?php Yii::app()->getClientScript()->registerScript(__FILE__, $this->clips['menu-stick']); ?>
</script>
