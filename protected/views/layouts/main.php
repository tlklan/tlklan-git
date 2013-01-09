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

	// Determine left-hand side items
	if (Yii::app()->user->isGuest)
	{
		$leftItems = array(
			array('label'=>'Information', 'url'=>'#', 'icon'=>'white info-sign', 'items'=>array(
				array('label'=>'Allmänt', 'url'=>Yii::app()->cms->createUrl('home'), 'active'=>Yii::app()->cms->isActive('home'), 'icon'=>'home'),
				array('label'=>'Styrelsen', 'url'=>Yii::app()->cms->createUrl('committee'), 'active'=>Yii::app()->cms->isActive('committee'), 'icon'=>'globe'),
			), 'active'=>(Yii::app()->cms->isActive('home') || Yii::app()->cms->isActive('committee'))),
			
			array('label'=>'<b>Anmälningar</b>', 'url'=>array('/registration/create'), 'icon'=>'white pencil'),
			array('label'=>'Tidtabell', 'url'=>Yii::app()->cms->createUrl('timetable'), 'active'=>Yii::app()->cms->isActive('timetable'), 'icon'=>'white time'),
			array('label'=>'Tävlingar', 'url'=>'#', 'icon'=>'white screenshot', 'items'=>array(
					array('label'=>'Regler', 'url'=>Yii::app()->cms->createUrl('rules')),
				), 'active'=>Yii::app()->cms->isActive('rules')),
			array('label'=>'Submissions', 'url'=>array('/submission/archive'),
				'active'=>Yii::app()->controller->route == 'submission/archive', 'icon'=>'white list-alt'),
		);
	}
	else
	{
		$leftItems = array(
			array('label'=>'Information', 'url'=>'#', 'icon'=>'white info-sign', 'items'=>array(
				array('label'=>'Allmänt', 'url'=>Yii::app()->cms->createUrl('home'), 'active'=>Yii::app()->cms->isActive('home'), 'icon'=>'home'),
				array('label'=>'Styrelsen', 'url'=>Yii::app()->cms->createUrl('committee'), 'active'=>Yii::app()->cms->isActive('committee'), 'icon'=>'globe'),
			), 'active'=>(Yii::app()->cms->isActive('home') || Yii::app()->cms->isActive('committee'))),
			array('label'=>'<b>Anmälningar</b>', 'url'=>array('/registration/create'), 'icon'=>'white pencil'),
			array('label'=>'Röstning', 'url'=>'#', 'items'=>array(
					array('label'=>'Rösta', 'url'=>array('/vote/create')),
					array('label'=>'Resultat', 'url'=>array('/vote/results')),
			), 'active'=>in_array(Yii::app()->controller->route, array('vote/create', 'vote/results')), 'icon'=>'white thumbs-up'),
			array('label'=>'Tidtabell', 'url'=>Yii::app()->cms->createUrl('timetable'), 'active'=>Yii::app()->cms->isActive('timetable'), 'icon'=>'white time'),
			array('label'=>'Tävlingar', 'url'=>'#', 'items'=>array(
					array('label'=>'Anmäl (under LAN)', 'url'=>array('/competition/register')),
					array('label'=>'Regler', 'url'=>Yii::app()->cms->createUrl('rules')),
					array('label'=>'Serverinformation', 'url'=>Yii::app()->cms->createUrl('serverinfo')),
					array('label'=>'Föreslå en tävling', 'url'=>array('/suggestion/create')),
				), 'active'=>(Yii::app()->cms->isActive('rules') || Yii::app()->cms->isActive('serverinfo') || Yii::app()->controller->route == 'suggestion/create'), 'icon'=>'white screenshot'),
			array('label'=>'Submissions', 'url'=>array('/submission'), 'items'=>array(
					array('label'=>'Ny submission', 'url'=>array('/submission/create')),
					array('label'=>'Arkiv', 'url'=>array('/submission/archive')),
				), 'active'=>in_array(Yii::app()->controller->route, array('submission/archive', 'submission/create')), 'icon'=>'white list-alt'),
		);
	}

	// Determine right-hand side items
	if (Yii::app()->user->isGuest)
	{
		$rightItems = array(
			array('label'=>'Registrera dig', 'url'=>array('/site/register'), 'icon'=>'white pencil'),
			array('label'=>'Logga in', 'url'=>array('/site/login'), 'icon'=>'white lock'),
		);
	}
	else
	{
		$rightItems = array(
			array('label'=>'Min profil', 'url'=>array('/user/profile'), 'icon'=>'white info-sign'));

		// Link to administration area
		if (Yii::app()->user->isAdmin())
			$rightItems[] = array('label'=>'Administration', 'url'=>array('//admin/'), 'icon'=>'white th');

		$rightItems[] = array('label'=>'Logga ut', 'url'=>array('/site/logout'), 'icon'=>'white off');
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
				'encodeLabel'=>false,
				'items'=>$leftItems,
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
			<p>
				Copyright 2010-<?php echo date("Y"); ?> &copy; <b>TLK LAN-klubben</b><br />
				Design och kod: <b>negge</b><br />
				<a href="https://bitbucket.org/negge/tlklan2">https://bitbucket.org/negge/tlklan2</a>
			</p>
		</div>
		
	</div>
</body>
</html>