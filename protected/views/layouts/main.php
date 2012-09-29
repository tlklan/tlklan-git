<?php

// Register main Javascript
$cs = Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/main.js', CClientScript::POS_HEAD);

?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="robots" content="noindex, nofollow" />
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<?php $cs->registerCssFile(Yii::app()->baseUrl.'/css/styles.css'); ?>
</head>
<body>
	<?php

	// Determine right-hand side items
	if(Yii::app()->user->isGuest) {
		$rightItems = array(
			array('label'=>'Logga in', 'url'=>array('/site/login')),
		);
	}
	else {
		$rightItems = array(
			array('label'=>'Logga ut', 'url'=>array('/site/logout')),
		);
	}
	
	$this->widget('bootstrap.widgets.TbNavbar', array(
		'type'=>'inverse',
		'brand'=>Yii::app()->name,
		'brandUrl'=>Yii::app()->homeUrl,
		'collapse'=>true,
		'fixed'=>false,
		'fluid'=>true,
		'items'=>array(
			array(
				'class'=>'bootstrap.widgets.TbMenu',
				'items'=>array(
					array('label'=>'Allmänt', 'url'=>Yii::app()->cms->createUrl('home'), 'active'=>Yii::app()->cms->isActive('home')),
					array('label'=>'Styrelsen', 'url'=>Yii::app()->cms->createUrl('committee'), 'active'=>Yii::app()->cms->isActive('committee')),
					array('label'=>'Anmälning', 'url'=>array('/registration/create')),
					array('label'=>'Tidtabell', 'url'=>Yii::app()->cms->createUrl('timetable'), 'active'=>Yii::app()->cms->isActive('timetable')),
					array('label'=>'Tävlingar', 'url'=>'#', 'items'=>array(
						array('label'=>'Anmäl (under LAN)', 'url'=>'http://werket.tlk.fi/tlklan_old/competitions/register/'),
						array('label'=>'Regler', 'url'=>Yii::app()->cms->createUrl('rules')),
						array('label'=>'Serverinformation', 'url'=>Yii::app()->cms->createUrl('serverinfo')),
					), 'active'=>(Yii::app()->cms->isActive('rules') || Yii::app()->cms->isActive('serverinfo'))),
					array('label'=>'Submissions', 'url'=>array('/submission'), 'items'=>array(
						array('label'=>'Ny submission', 'url'=>array('/submission/create')),
						array('label'=>'Arkiv', 'url'=>array('/submission/archive')),
					), 'active'=>in_array(Yii::app()->controller->route, array('submission/archive', 'submission/create'))),
				),
			),
			array(
				'class'=>'bootstrap.widgets.TbMenu',
				'htmlOptions'=>array('class'=>'pull-right'),
				'items'=>$rightItems,
			),
		),
	));

	?>
	<div class="container">
		<?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
			'homeLink'=>CHtml::link(Yii::app()->name, Yii::app()->homeUrl),
			'links'=>$this->breadcrumbs,
		)); ?>
		
		<?php $this->widget('bootstrap.widgets.TbAlert', array(
			'block'=>true,
			'fade'=>true,
			'closeText'=>'&times;',
			'alerts'=>array(
				'success'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'),
				'error'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'),
			),
		)); ?>
		
		<div class="content">
			<?php echo $content; ?>
		</div>
		
		<div class="footer">
			Copyright 2010-<?php echo date("Y"); ?> &copy; <b>TLK LAN-klubben</b><br />
			Design och kod: <b>negge</b><br />
		</div>
		
	</div>
</body>
</html>
