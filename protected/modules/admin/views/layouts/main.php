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
	<?php Yii::app()->bootstrap->register(); ?>
	<?php $cs->registerCssFile(Yii::app()->baseUrl.'/css/styles.css'); ?>
	<?php $cs->registerCssFile(Yii::app()->baseUrl.'/css/fontawesome/css/font-awesome.min.css'); ?>
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
				array('label'=>'Anmälningar', 'url'=>array('registration/admin'),
					'active'=>Yii::app()->controller->route == 'admin/registration/admin'),
				array('label'=>'Användare', 'url'=>array('user/admin'),
					'active'=>Yii::app()->controller->route == 'admin/user/admin'),
				array('label'=>'LAN', 'items'=>array(
					array('label'=>'Hantera', 'url'=>array('lan/admin')),
					array('label'=>'Skapa nytt', 'url'=>array('lan/create')),
				), 'active'=>(strpos(Yii::app()->controller->route, 'admin/lan') !== false)),
				array('label'=>'Tävlingar', 'items'=>array(
					array('label'=>'Hantera', 'url'=>array('competition/admin')),
					array('label'=>'Skapa ny', 'url'=>array('competition/create')),
				), 'active'=>(strpos(Yii::app()->controller->route, 'admin/competition') !== false)),
				array('label'=>'Betalningar', 'items'=>array(
					array('label'=>'Hantera', 'url'=>array('payment/admin')),
					array('label'=>'Ny betalning', 'url'=>array('payment/create')),
				), 'active'=>(strpos(Yii::app()->controller->route, 'admin/payment') !== false)),
				array('label'=>'Översättningar', 'url'=>array('translation/translate'), 
					'active'=>(Yii::app()->controller->route == 'translation/translate')),
			),
		),
		array(
			'class'=>'bootstrap.widgets.TbMenu',
			'htmlOptions'=>array('class'=>'pull-right'),
			'items'=>array(
				array('label' => 'Tillbaka till sidan', 'url' =>$this->createUrl('//site/index')),
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