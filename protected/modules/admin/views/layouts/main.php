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
	<?php $cs->registerCssFile(Yii::app()->baseUrl.'/css/admin.css'); ?>
</head>
<body>
<?php

$this->widget('bootstrap.widgets.TbNavbar', array(
	'type'=>'inverse',
	'brand'=>'Administration',
	'brandUrl'=>$this->createUrl('//admin'),
	'collapse'=>true,
	'fixed'=>false,
	'fluid'=>true,
	'items'=>array(
		array(
			'class'=>'bootstrap.widgets.TbMenu',
			'encodeLabel'=>false,
			'items'=>array(
				array('label'=>'Anmälningar', 'url'=>array('registration/admin')),
//				array('label'=>'Styrelsen', 'url'=>Yii::app()->cms->createUrl('committee'), 'active'=>Yii::app()->cms->isActive('committee')),
//				array('label'=>'<b>Anmälning</b>', 'url'=>array('/registration/create')),
//				array('label'=>'Röstning', 'url'=>'#', 'items'=>array(
//					array('label'=>'Rösta', 'url'=>array('/vote/create')),
//					array('label'=>'Resultat', 'url'=>array('/vote/results')),
//				)),
//				array('label'=>'Tidtabell', 'url'=>Yii::app()->cms->createUrl('timetable'), 'active'=>Yii::app()->cms->isActive('timetable')),
//				array('label'=>'Tävlingar', 'url'=>'#', 'items'=>array(
//					array('label'=>'Anmäl (under LAN)', 'url'=>array('/competition/register')),
//					array('label'=>'Regler', 'url'=>Yii::app()->cms->createUrl('rules')),
//					array('label'=>'Serverinformation', 'url'=>Yii::app()->cms->createUrl('serverinfo')),
//				), 'active'=>(Yii::app()->cms->isActive('rules') || Yii::app()->cms->isActive('serverinfo'))),
//				array('label'=>'Submissions', 'url'=>array('/submission'), 'items'=>array(
//					array('label'=>'Ny submission', 'url'=>array('/submission/create')),
//					array('label'=>'Arkiv', 'url'=>array('/submission/archive')),
//				), 'active'=>in_array(Yii::app()->controller->route, array('submission/archive', 'submission/create'))),
			),
		),
		array(
			'class'=>'bootstrap.widgets.TbMenu',
			'htmlOptions'=>array('class'=>'pull-right'),
			'items'=>array(
				array('label' => 'Tillbaka till sidan', 'url' => Yii::app()->homeUrl),
				array('label' => 'Logga ut', 'url' => array('/site/logout'))
			),
		),
	),
));

?>
<div class="container">
	<?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
		'homeLink'=>CHtml::link('Administration', $this->createUrl('//admin')),
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
            Design och kod: <b>negge</b>
        </p>
        <p>
            Teknologi:
            <a href="http://www.yiiframework.com">Yii framework</a> /
            <a href="http://www.cniska.net/yii-bootstrap/">Yii-Bootstrap</a> /
            <a href="http://www.yiiframework.com/extension/less">Yii-LESS</a> /
            <a href="https://bitbucket.org/NordLabs/nordcms">NordCms</a> /
            <a href="http://jquery.com/">jQuery</a>
        </p>
    </div>

</div>
</body>
</html>